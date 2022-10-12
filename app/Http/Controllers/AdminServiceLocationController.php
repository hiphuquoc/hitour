<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Models\ServiceLocation;
use App\Models\ShipLocation;
use App\Models\CarrentalLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\District;
use App\Models\Province;
use App\Models\Guide;
use App\Models\SystemFile;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\ServiceLocationRequest;

class AdminServiceLocationController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo vùng miền */
        if(!empty($request->get('search_region'))) $params['search_region'] = $request->get('search_region');
        /* lấy dữ liệu */
        $list           = ServiceLocation::getList($params);
        return view('admin.serviceLocation.list', compact('list', 'params'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = ServiceLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'service_location');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'service_location');
                                }])
                                ->with('seo')
                                ->first();
        $provinces          = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts          = District::getItemByIdProvince($item->province_id ?? 0);
        $content            = null;
        if(!empty($item->seo->slug)){
            $content        = Storage::get(config('admin.storage.contentServiceLocation').$item->seo->slug.'.blade.php');
        }
        $message            = $request->get('message') ?? null; 
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.serviceLocation.view', compact('item', 'type', 'provinces', 'districts', 'content', 'message'));
    }

    public function create(ServiceLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'service_location', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert service_location */
            $insertServiceLocation = $this->BuildInsertUpdateModel->buildArrayTableServiceLocation($request->all(), $pageId);
            $idServiceLocation     = ServiceLocation::insertItem($insertServiceLocation);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'service_location',
                            'reference_id'      => $idServiceLocation
                        ]);
                    }
                }
            }
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentServiceLocation').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idServiceLocation,
                    'relation_table'    => 'service_location',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Điểm đến dịch vụ mới'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        $request->session()->put('message', $message);
        return redirect()->route('admin.serviceLocation.view', ['id' => $idServiceLocation]);
    }

    public function update(ServiceLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'service_location', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update ServiceLocation */
            $idServiceLocation     = $request->get('service_location_id');
            $updateServiceLocation = $this->BuildInsertUpdateModel->buildArrayTableServiceLocation($request->all());
            ServiceLocation::updateItem($idServiceLocation, $updateServiceLocation);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                        ->where('relation_table', 'service_location')
                        ->where('reference_id', $idServiceLocation)
                        ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'service_location',
                            'reference_id'      => $idServiceLocation
                        ]);
                    }
                }
            }
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentServiceLocation').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idServiceLocation,
                    'relation_table'    => 'service_location',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
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
        $request->session()->put('message', $message);
        return redirect()->route('admin.serviceLocation.view', ['id' => $idServiceLocation]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = ServiceLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'service_location');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng service_location */
                ServiceLocation::find($id)->delete();
                /* delete bảng seo */
                Seo::find($info->seo->id)->delete();
                /* xóa ảnh đại diện trong thư mục */
                $imageSmallPath     = Storage::path(config('admin.images.folderUpload').basename($info->seo->image_small));
                if(file_exists($imageSmallPath)) @unlink($imageSmallPath);
                $imagePath          = Storage::path(config('admin.images.folderUpload').basename($info->seo->image));
                if(file_exists($imagePath)) @unlink($imagePath);
                /* delete files */
                if(!empty($info->files)){
                    foreach($info->files as $file) AdminSliderController::removeSliderById($file->id);
                }
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
