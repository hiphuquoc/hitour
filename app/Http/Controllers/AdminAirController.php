<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Models\AirLocation;
use App\Models\AirDeparture;
use App\Models\Air;
use App\Models\RelationAirStaff;
use App\Models\RelationAirPartner;
use App\Models\Staff;
use App\Models\AirPartner;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\AirRequest;
use App\Models\AirPort;
use App\Models\QuestionAnswer;
use App\Jobs\CheckSeo;

class AdminAirController extends Controller {

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
        $list                           = Air::getList($params);
        /* khu vực Tàu */
        $airLocations                   = AirLocation::all();
        /* đối tác */
        $partners                       = AirPartner::all();
        /* nhân viên */
        $staffs                         = Staff::all();
        return view('admin.air.list', compact('list', 'params', 'airLocations', 'partners', 'staffs'));
    }

    public function view(Request $request){
        $item               = Air::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'air_info');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'air_info');
                                }])
                                ->with('seo', 'location', 'departure', 'partners.infoPartner', 'staffs')
                                ->first();
        $airDepartures      = AirDeparture::all();
        $idPortDeparture    = $item->departure->id ?? 0;
        $airPortDepartures  = AirPort::getAirPortByAirDepartureId($idPortDeparture);
        $airLocations       = AirLocation::all();
        $idPortLocation     = $item->location->id ?? 0;
        $airPortLocations   = AirPort::getAirPortByAirLocationId($idPortLocation);
        $staffs             = Staff::all();
        $airPartners        = AirPartner::all();
        $parents            = AirLocation::select('*')
                                ->with('seo')
                                ->get();
        $message            = $request->get('message') ?? null;
        $content            = null;
        if(!empty($item->seo->slug)){
            $content        = Storage::get(config('admin.storage.contentAir').$item->seo->slug.'.blade.php');
        }
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.air.view', compact('parents', 'item', 'content', 'type', 'airDepartures', 'airPortDepartures', 'airLocations', 'airPortLocations', 'staffs', 'airPartners'));
    }

    public function create(AirRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert seo */
            $insertSeo          = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'air_info', $dataPath);
            $seoId              = Seo::insertItem($insertSeo);
            /* insert air_info */
            $insertAirInfo     = $this->BuildInsertUpdateModel->buildArrayTableAirInfo($request->all(), $seoId);
            $idAir             = Air::insertItem($insertAirInfo);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentAir').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'air_info',
                            'reference_id'      => $idAir
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idAir)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idAir,
                    'relation_table'    => 'air_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idAir)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idAir,
                    'relation_table'    => 'air_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_Air_staff */
            if(!empty($idAir)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'air_info_id'      => $idAir,
                        'staff_info_id'     => $staff
                    ];
                    RelationAirStaff::insertItem($params);
                }
            }
            /* insert relation_Air_partner */
            if(!empty($idAir)&&!empty($request->get('partner'))){
                foreach($request->get('partner') as $partner){
                    $params     = [
                        'air_info_id'      => $idAir,
                        'partner_info_id'   => $partner
                    ];
                    RelationAirPartner::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo chuyến bay mới'
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
        return redirect()->route('admin.air.view', ['id' => $idAir]);
    }

    public function update(AirRequest $request){
        try {
            DB::beginTransaction();
            $idAir              = $request->get('air_info_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'air_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update air_info */
            $updateTourInfo     = $this->BuildInsertUpdateModel->buildArrayTableAirInfo($request->all(), $request->get('seo_id'));
            Air::updateItem($idAir, $updateTourInfo);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentAir').$request->get('slug').'.blade.php', $request->get('content'));
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'air_info')
                            ->where('reference_id', $idAir)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'air_info',
                            'reference_id'      => $idAir
                        ]);
                    }
                }
            }
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idAir)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idAir,
                    'relation_table'    => 'air_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idAir)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idAir,
                    'relation_table'    => 'air_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* update relation_Air_staff */
            RelationAirStaff::deleteAndInsertItem($idAir, $request->get('staff'));
            /* update relation_Air_partner */
            RelationAirPartner::deleteAndInsertItem($idAir, $request->get('partner'));
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
        return redirect()->route('admin.air.view', ['id' => $idAir]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idAir     = $request->get('id');
                /* lấy thông tin */
                $infoAir   = Air::select('*')
                                    ->where('id', $idAir)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'air_info');
                                    }])
                                    ->with('seo', 'staffs', 'partners')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục */
                $imageSmallPath     = Storage::path(config('admin.images.folderUpload').basename($infoAir->seo->image_small));
                if(file_exists($imageSmallPath)) @unlink($imageSmallPath);
                $imagePath          = Storage::path(config('admin.images.folderUpload').basename($infoAir->seo->image));
                if(file_exists($imagePath)) @unlink($imagePath);
                /* delete files - dùng removeSliderById cũng remove luôn cả gallery */
                if(!empty($infoAir->files)){
                    foreach($infoAir->files as $file) AdminSliderController::removeSliderById($file->id);
                }
                /* xóa tour_staff */
                $arrayIdStaff           = [];
                foreach($infoAir->staffs as $staff) $arrayIdStaff[] = $staff->id;
                RelationAirStaff::select('*')->whereIn('id', $arrayIdStaff)->delete();
                /* xóa tour_partner */
                $arrayIdPartner         = [];
                foreach($infoAir->partners as $partner) $arrayIdPartner[] = $partner->id;
                RelationAirPartner::select('*')->whereIn('id', $arrayIdPartner)->delete();
                /* xóa seo */
                Seo::find($infoAir->seo->id)->delete();
                /* xóa air_info */
                $infoAir->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
