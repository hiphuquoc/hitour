<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;

use App\Models\TourLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\District;
use App\Models\Province;
use App\Models\SystemFile;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\TourLocationRequest;

class AdminTourLocationController extends Controller {

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
        $list           = TourLocation::getList($params);
        return view('admin.tourLocation.list', compact('list', 'params'));
    }

    public function view(Request $request){
        $id             = $request->get('id') ?? 0;
        $item           = TourLocation::select('*')
                            ->where('id', $id)
                            ->with(['files' => function($query){
                                $query->where('relation_table', 'tour_location');
                            }], 'seo')
                            ->first();
        $provinces      = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts      = District::getItemByIdProvince($item->province_id ?? 0);
        $message        = $request->get('message') ?? null; 
        $type           = !empty($item) ? 'edit' : 'create';
        $type           = $request->get('type') ?? $type;
        return view('admin.tourLocation.view', compact('item', 'type', 'provinces', 'districts', 'message'));
    }

    public function create(TourLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_location', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert tour_location */
            $insertTourLocation = $this->BuildInsertUpdateModel->buildArrayTableTourLocation($request->all(), $pageId);
            $idTourLocation     = TourLocation::insertItem($insertTourLocation);
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourLocation').$request->get('slug').'.html', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourLocation,
                    'relation_table'    => 'tour_location',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Điểm đến Tour mới'
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
        return redirect()->route('admin.tourLocation.view', ['id' => $idTourLocation]);
    }

    public function update(TourLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_location', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update TourLocation */
            $updateTourLocation = $this->BuildInsertUpdateModel->buildArrayTableTourLocation($request->all());
            TourLocation::updateItem($request->get('tour_location_id'), $updateTourLocation);
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourLocation').$request->get('slug').'.html', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $request->get('tour_location_id'),
                    'relation_table'    => 'tour_location',
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
        return redirect()->route('admin.tourLocation.view', ['id' => $request->get('tour_location_id')]);
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                /* lấy thông tin seo */
                $infoSeo    = DB::table('seo')
                                ->join('tour_location', 'tour_location.seo_id', '=', 'seo.id')
                                ->select('seo.id')
                                ->where('tour_location.id', $id)
                                ->first();
                $idSeo      = $infoSeo->id ?? 0;
                /* lấy thông tin slider */
                $infoSlider = DB::table('system_file')
                                ->join('tour_location', 'tour_location.id', '=', 'system_file.attachment_id')
                                ->select('system_file.id', 'system_file.file_path')
                                ->where('system_file.attachment_id', $id)
                                ->get();
                /* delete bảng tour_location */
                TourLocation::find($id)->delete();
                /* delete bảng seo */
                Seo::find($idSeo)->delete();
                
                if(!empty($infoSlider)){
                    foreach($infoSlider as $slider) {
                        /* delete image slider bảng system_file */
                        SystemFile::find($slider->id)->delete();
                        /* xóa ảnh slider trong thư mục upload */
                        if(!empty($slider->file_path)&&file_exists(public_path($slider->file_path))) unlink(public_path($slider->file_path));
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
