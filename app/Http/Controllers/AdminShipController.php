<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;

use App\Models\ShipLocation;
use App\Models\ShipDeparture;
use App\Models\Ship;
use App\Models\RelationShipLocation;
use App\Models\RelationShipStaff;
use App\Models\RelationShipPartner;
use App\Models\Staff;
use App\Models\ShipPartner;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;

use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\DB;

use App\Http\Requests\ShipRequest;
use App\Models\SystemFile;

class AdminShipController extends Controller {

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
        $list                           = Ship::getList($params);
        /* khu vực Tàu */
        $shipLocations                  = ShipLocation::all();
        /* đối tác */
        $partners                       = ShipPartner::all();
        /* nhân viên */
        $staffs                         = Staff::all();
        return view('admin.ship.list', compact('list', 'params', 'shipLocations', 'partners', 'staffs'));
    }

    public function view(Request $request){
        $shipLocations      = ShipLocation::all();
        $shipDepartures     = ShipDeparture::all();
        $staffs             = Staff::all();
        $shipPartners       = ShipPartner::all();
        $parents            = ShipLocation::select('*')
                                ->with('seo')
                                ->get();
        $message            = $request->get('message') ?? null;
        $id                 = $request->get('id') ?? 0;
        $item               = Ship::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'ship_info');
                                }])
                                ->with('seo', 'location', 'departure', 'partners.infoPartner', 'staffs')
                                ->first();
        $content            = Storage::get(config('admin.storage.contentShip').$item->seo->slug.'.html');
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.ship.view', compact('parents', 'item', 'content', 'type', 'shipLocations', 'shipDepartures', 'staffs', 'shipPartners'));
    }

    public function create(ShipRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert seo */
            $insertSeo          = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'ship_info', $dataPath);
            $seoId              = Seo::insertItem($insertSeo);
            /* insert ship_info */
            $insertShipInfo     = $this->BuildInsertUpdateModel->buildArrayTableShipInfo($request->all(), $seoId);
            $idShip             = Ship::insertItem($insertShipInfo);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentShip').$request->get('slug').'.html', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idShip)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idShip,
                    'relation_table'    => 'ship_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idShip)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idShip,
                    'relation_table'    => 'ship_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_ship_staff */
            if(!empty($idShip)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'ship_info_id'      => $idShip,
                        'staff_info_id'     => $staff
                    ];
                    RelationShipStaff::insertItem($params);
                }
            }
            /* insert relation_ship_partner */
            if(!empty($idShip)&&!empty($request->get('partner'))){
                foreach($request->get('partner') as $partner){
                    $params     = [
                        'ship_info_id'      => $idShip,
                        'partner_info_id'   => $partner
                    ];
                    RelationShipPartner::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Chuyến tàu mới'
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
        return redirect()->route('admin.ship.view', ['id' => $idShip]);
    }

    public function update(ShipRequest $request){
        try {
            DB::beginTransaction();
            $idShip             = $request->get('ship_info_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'ship_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update ship_info */
            $updateTourInfo     = $this->BuildInsertUpdateModel->buildArrayTableShipInfo($request->all(), $request->get('seo_id'));
            Ship::updateItem($idShip, $updateTourInfo);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentShip').$request->get('slug').'.html', $request->get('content'));
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idShip)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idShip,
                    'relation_table'    => 'ship_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idShip)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idShip,
                    'relation_table'    => 'ship_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* update relation_ship_staff */
            RelationShipStaff::deleteAndInsertItem($idShip, $request->get('staff'));
            /* update relation_ship_partner */
            RelationShipPartner::deleteAndInsertItem($idShip, $request->get('partner'));
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
        return redirect()->route('admin.ship.view', ['id' => $idShip]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idShip     = $request->get('id');
                /* lấy thông tin */
                $infoShip   = Ship::select('*')
                                    ->where('id', $idShip)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'ship_info');
                                    }])
                                    ->with('seo', 'staffs', 'partners')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoShip->seo->image)&&file_exists(public_path($infoShip->seo->image))) unlink(public_path($infoShip->seo->image));
                if(!empty($infoShip->seo->image_small)&&file_exists(public_path($infoShip->seo->image_small))) unlink(public_path($infoShip->seo->image_small));
                /* xóa tour_staff */
                $arrayIdStaff           = [];
                foreach($infoShip->staffs as $staff) $arrayIdStaff[] = $staff->id;
                RelationShipStaff::select('*')->whereIn('id', $arrayIdStaff)->delete();
                /* xóa tour_partner */
                $arrayIdPartner         = [];
                foreach($infoShip->partners as $partner) $arrayIdPartner[] = $partner->id;
                RelationShipPartner::select('*')->whereIn('id', $arrayIdPartner)->delete();
                /* xóa file trên system_file */
                $arrayIdFiles           = [];
                foreach($infoShip->files as $file) $arrayIdFiles[] = $file->id;
                SystemFile::select('*')->whereIn('id', $arrayIdFiles)->delete();
                /* xóa file trong thư mục upload */
                $files      = $infoShip->files;
                foreach($files as $file) if(!empty($file->file_path)&&file_exists(public_path($file->file_path))) @unlink(public_path($file->file_path));
                /* xóa seo */
                Seo::find($infoShip->seo->id)->delete();
                /* xóa ship_info */
                $infoShip->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
