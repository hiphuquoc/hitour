<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;

use App\Models\ShipLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\District;
use App\Models\Province;
use App\Models\SystemFile;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\ShipLocationRequest;
use Illuminate\Support\Facades\Storage;

class AdminShipLocationController extends Controller {

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
        $list           = ShipLocation::getList($params);
        return view('admin.shipLocation.list', compact('list', 'params'));
    }

    public function view(Request $request){
        $id             = $request->get('id') ?? 0;
        $item           = ShipLocation::select('*')
                            ->where('id', $id)
                            ->with(['files' => function($query){
                                $query->where('relation_table', 'ship_location');
                            }], 'seo')
                            ->first();
        $provinces      = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts      = District::getItemByIdProvince($item->province_id ?? 0);
        $content        = Storage::get(config('admin.storage.contentShipLocation').$item->seo->slug.'.html');
        $message        = $request->get('message') ?? null; 
        $type           = !empty($item) ? 'edit' : 'create';
        $type           = $request->get('type') ?? $type;
        return view('admin.shipLocation.view', compact('item', 'content', 'type', 'provinces', 'districts', 'message'));
    }

    public function create(ShipLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'ship_location', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert ship_location */
            $insertShipLocation = $this->BuildInsertUpdateModel->buildArrayTableShipLocation($request->all(), $pageId);
            $idShipLocation     = ShipLocation::insertItem($insertShipLocation);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentShipLocation').$request->get('slug').'.html', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idShipLocation,
                    'relation_table'    => 'ship_location',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Điểm đến Tàu mới'
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
        return redirect()->route('admin.shipLocation.view', ['id' => $idShipLocation]);
    }

    public function update(ShipLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'ship_location', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update ShipLocation */
            $updateShipLocation = $this->BuildInsertUpdateModel->buildArrayTableShipLocation($request->all());
            ShipLocation::updateItem($request->get('ship_location_id'), $updateShipLocation);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentShipLocation').$request->get('slug').'.html', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $request->get('ship_location_id'),
                    'relation_table'    => 'ship_location',
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
        return redirect()->route('admin.shipLocation.view', ['id' => $request->get('ship_location_id')]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = ShipLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'ship_location');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng ship_location */
                ShipLocation::find($id)->delete();
                /* delete bảng seo */
                Seo::find($info->seo->id)->delete();
                /* xóa ảnh đại diện trong thư mục */
                if(!empty($info->seo->image)&&file_exists(public_path($info->seo->image))) @unlink(public_path($info->seo->image));
                if(!empty($info->seo->image_small)&&file_exists(public_path($info->seo->image_small))) @unlink(public_path($info->seo->image_small));
                /* delete files */
                if(!empty($info->files)){
                    foreach($info->files as $file){
                        /* delete image slider bảng system_file */
                        SystemFile::find($file->id)->delete();
                        /* delete trong thư mục */
                        if(!empty($file->file_path)&&file_exists(public_path($file->file_path))) unlink(public_path($file->file_path));
                    }
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
