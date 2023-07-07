<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Controllers\AdminImageController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Models\HotelLocation;
use App\Models\HotelContent;
// use App\Models\TourDeparture;
use App\Models\Hotel;
use App\Models\RelationHotelLocation;
use App\Models\RelationHotelStaff;
use App\Models\Staff;
// use App\Models\ComboPrice;
// use App\Models\ComboOption;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\HotelRequest;
use App\Jobs\CheckSeo;

class AdminHotelInfoController extends Controller {

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
        /* paginate */
        $viewPerPage        = Cookie::get('viewHotelInfo') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list                           = Hotel::getList($params);
        /* khu vực Combo */
        $hotelLocations                 = HotelLocation::all();
        /* nhân viên */
        $staffs                         = Staff::all();
        return view('admin.hotel.list', compact('list', 'params', 'viewPerPage', 'hotelLocations', 'staffs'));
    }

    public function view(Request $request){
        $hotelLocations     = HotelLocation::all();
        $staffs             = Staff::all();
        $parents            = HotelLocation::select('*')
                                ->with('seo')
                                ->get();
        $message            = $request->get('message') ?? null;
        $id                 = $request->get('id') ?? 0;
        $item               = Hotel::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'hotel_info');
                                }])
                                ->with('seo', 'contents')
                                ->first();
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.hotel.view', compact('item', 'type', 'hotelLocations', 'staffs', 'parents', 'message'));
    }

    public function create(HotelRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'hotel_info', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert hotel_info */
            $insertHotelInfo    = $this->BuildInsertUpdateModel->buildArrayTableHotelInfo($request->all(), $pageId);
            $idHotel            = Hotel::insertItem($insertHotelInfo);
            /* insert hotel_content */
            if(!empty($request->get('contents'))){
                foreach($request->get('contents') as $content){
                    if(!empty($content['name'])&&!empty($content['content'])){
                        HotelContent::insertItem([
                            'hotel_info_id' => $idHotel,
                            'name'          => $content['name'],
                            'content'       => $content['content']
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_hotel_staff */
            if(!empty($idHotel)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'hotel_info_id'     => $idHotel,
                        'staff_info_id'     => $staff
                    ];
                    RelationHotelStaff::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Hotel mới'
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
        return redirect()->route('admin.hotel.view', ['id' => $idHotel]);
    }

    public function update(HotelRequest $request){
        try {
            DB::beginTransaction();
            $idHotel             = $request->get('hotel_info_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'hotel_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update hotel_info */
            $updateHotelInfo     = $this->BuildInsertUpdateModel->buildArrayTableHotelInfo($request->all(), $request->get('seo_id'));
            Hotel::updateItem($idHotel, $updateHotelInfo);
            /* update hotel_content */
            HotelContent::select('*')
                            ->where('hotel_info_id', $idHotel)
                            ->delete();
            if(!empty($request->get('contents'))){
                foreach($request->get('contents') as $content){
                    if(!empty($content['name'])&&!empty($content['content'])){
                        HotelContent::insertItem([
                            'hotel_info_id' => $idHotel,
                            'name'          => $content['name'],
                            'content'       => $content['content']
                        ]);
                    }
                }
            }
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* update relation_hotel_staff */
            RelationHotelStaff::select('*')
                ->where('hotel_info_id', $idHotel)
                ->delete();
            if(!empty($idHotel)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'hotel_info_id'     => $idHotel,
                        'staff_info_id'     => $staff
                    ];
                    RelationHotelStaff::insertItem($params);
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
        return redirect()->route('admin.hotel.view', ['id' => $idHotel]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idHotel     = $request->get('id');
                /* lấy hotel_option (with hotel_price) */
                $infoCombo   = Hotel::select('*')
                                    ->where('id', $idHotel)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'hotel_info');
                                    }])
                                    ->with('seo', 'locations', 'staffs', 'partners', 'options.prices')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoCombo->seo->image)&&file_exists(public_path($infoCombo->seo->image))) unlink(public_path($infoCombo->seo->image));
                if(!empty($infoCombo->seo->image_small)&&file_exists(public_path($infoCombo->seo->image_small))) unlink(public_path($infoCombo->seo->image_small));
                /* xóa hotel_content */
                HotelContent::select('*')
                            ->where('hotel_info_id', $idHotel)
                            ->delete();
                /* xóa hotel_option và hotel_price */
                $arrayIdOption  = [];
                $arrayIdPrice   = [];
                foreach($infoCombo->options as $option){
                    $arrayIdOption[]    = $option->id;
                    foreach($option->prices as $price){
                        $arrayIdPrice[] = $price->id;
                    }
                }
                ComboPrice::select('*')->whereIn('id', $arrayIdPrice)->delete();
                ComboOption::select('*')->whereIn('id', $arrayIdOption)->delete();
                /* xóa relation hotel_location */
                $arrayIdhotelLocation    = [];
                foreach($infoCombo->locations as $location) $arrayIdhotelLocation[] = $location->id;
                RelationHotelLocation::select('*')->whereIn('id', $arrayIdhotelLocation)->delete();
                /* xóa hotel_staff */
                $arrayIdStaff           = [];
                foreach($infoCombo->staffs as $staff) $arrayIdStaff[] = $staff->id;
                RelationHotelStaff::select('*')->whereIn('id', $arrayIdStaff)->delete();
                /* xóa hotel_partner */
                $arrayIdPartner         = [];
                foreach($infoCombo->partners as $partner) $arrayIdPartner[] = $partner->id;
                RelationComboPartner::select('*')->whereIn('id', $arrayIdPartner)->delete();
                /* delete files - dùng removeSliderById cũng remove luôn cả gallery */
                if(!empty($infoCombo->files)){
                    foreach($infoCombo->files as $file) AdminSliderController::removeSliderById($file->id);
                }
                /* xóa seo */
                Seo::find($infoCombo->seo->id)->delete();
                /* xóa hotel_info */
                $infoCombo->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
