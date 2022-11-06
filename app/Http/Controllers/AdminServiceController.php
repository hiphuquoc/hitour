<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Models\Staff;
use App\Models\ServiceLocation;
use App\Models\Service;
use App\Models\RelationServiceStaff;
use App\Models\Seo;
use App\Models\QuestionAnswer;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ServiceRequest;
use App\Jobs\CheckSeo;

class AdminServiceController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params                         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo vùng miền */
        if(!empty($request->get('search_location'))) $params['search_location'] = $request->get('search_location');
        /* Search theo đối tác */
        if(!empty($request->get('search_partner'))) $params['search_partner'] = $request->get('search_partner');
        /* Search theo nhân viên */
        if(!empty($request->get('search_staff'))) $params['search_staff'] = $request->get('search_staff');
        /* lấy dữ liệu */
        $list                           = Service::getList($params);
        /* khu vực Service */
        $serviceLocations               = ServiceLocation::all();
        return view('admin.service.list', compact('list', 'params', 'serviceLocations'));
    }

    public function view(Request $request){
        $serviceLocations   = ServiceLocation::all();
        $staffs             = Staff::all();
        $message            = $request->get('message') ?? null;
        $id                 = $request->get('id') ?? 0;
        $item               = Service::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'service_info');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'service_info');
                                }])
                                ->with('seo', 'serviceLocation', 'staffs.infoStaff')
                                ->first();
        $parents            = ServiceLocation::select('*')
                                ->with('seo')
                                ->get();
        $content            = null;
        if(!empty($item->seo->slug)){
            $content        = Storage::get(config('admin.storage.contentService').$item->seo->slug.'.blade.php');
        }
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.service.view', compact('item', 'type', 'parents', 'staffs', 'serviceLocations', 'content', 'message'));
    }

    public function create(ServiceRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'service_info', $dataPath);
            $seoId              = Seo::insertItem($insertPage);
            /* insert service_info */
            $insertServiceInfo  = $this->BuildInsertUpdateModel->buildArrayTableServiceInfo($request->all(), $seoId);
            $idService          = Service::insertItem($insertServiceInfo);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentService').$request->get('slug').'.blade.php', $content);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'service_info',
                            'reference_id'      => $idService
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idService)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idService,
                    'relation_table'    => 'service_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idService)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idService,
                    'relation_table'    => 'service_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_Service_staff */
            if(!empty($idService)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'service_info_id'   => $idService,
                        'staff_info_id'     => $staff
                    ];
                    RelationServiceStaff::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Service mới'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($seoId);
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.service.view', ['id' => $idService]);
    }

    public function update(ServiceRequest $request){
        try {
            DB::beginTransaction();
            $idService             = $request->get('service_info_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'service_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update service_info */
            $updateServiceInfo     = $this->BuildInsertUpdateModel->buildArrayTableServiceInfo($request->all(), $request->get('seo_id'));
            Service::updateItem($idService, $updateServiceInfo);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentService').$request->get('slug').'.blade.php', $content);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'service_info')
                            ->where('reference_id', $idService)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'service_info',
                            'reference_id'      => $idService
                        ]);
                    }
                }
            }
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idService)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idService,
                    'relation_table'    => 'service_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idService)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idService,
                    'relation_table'    => 'service_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_Service_staff */
            RelationServiceStaff::select('*')
                                    ->where('service_info_id', $idService)
                                    ->delete();
            if(!empty($idService)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'service_info_id'   => $idService,
                        'staff_info_id'     => $staff
                    ];
                    RelationServiceStaff::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Các thay đổi đã được lưu'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($request->get('seo_id'));
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.service.view', ['id' => $idService]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idService     = $request->get('id');
                /* lấy Service_option */
                $infoService   = Service::select('*')
                                    ->where('id', $idService)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'service_info');
                                    }])
                                    ->with('seo', 'staffs')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoService->seo->image)&&file_exists(public_path($infoService->seo->image))) unlink(public_path($infoService->seo->image));
                if(!empty($infoService->seo->image_small)&&file_exists(public_path($infoService->seo->image_small))) unlink(public_path($infoService->seo->image_small));
                /* xóa Service_staff */
                $arrayIdStaff           = [];
                foreach($infoService->staffs as $staff) $arrayIdStaff[] = $staff->id;
                RelationServiceStaff::select('*')->whereIn('id', $arrayIdStaff)->delete();
                /* delete files - dùng remove luôn cả slider && gallery */
                if(!empty($infoService->files)){
                    foreach($infoService->files as $file) AdminSliderController::removeSliderById($file->id);
                }
                /* xóa seo */
                Seo::find($infoService->seo->id)->delete();
                /* xóa service_info */
                $infoService->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
