<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;

use App\Models\TourLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;

use App\Helpers\Url;
use App\Models\District;
use App\Models\Province;
use App\Models\SystemFile;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\TourLocationRequest;

class AdminTourLocationController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params                         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo vùng miền */
        if(!empty($request->get('search_region'))) $params['search_region'] = $request->get('search_region');
        /* lấy dữ liệu */
        $list               = TourLocation::getList($params);
        return view('admin.tourLocation.list', compact('list', 'params'));
    }

    public function viewEdit(Request $request, $id){
        if(!empty($id)){
            $tourLocation   = TourLocation::all();
            $item           = TourLocation::select('*')
                                            ->where('id', $id)
                                            ->with('seo', 'files')
                                            ->first();
            $provinces      = Province::getItemByIdRegion($item->region_id);
            $districts      = District::getItemByIdProvince($item->province_id);
            $type           = 'edit';
            if(!empty($request->get('type'))) $type = $request->get('type');
            if(!empty($item)) return view('admin.tourLocation.view', compact('tourLocation', 'item', 'type', 'provinces', 'districts'));

        }
        return redirect()->route('admin.TourLocation.list');
    }

    public function viewInsert(Request $request){
        $tourLocation       = TourLocation::all();
        $type               = 'create';
        return view('admin.tourLocation.view', compact('tourLocation', 'type'));
    }

    public function create(TourLocationRequest $request){
        /* upload image */
        $dataPath           = [];
        if($request->hasFile('image')) {
            $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
            $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
        }
        /* insert page */
        $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), $dataPath);
        $pageId             = Seo::insertItem($insertPage);
        /* insert tour_location */
        $insertTourLocation = $this->BuildInsertUpdateModel->buildArrayTableTourLocation($request->all(), $pageId);
        $idTourLocation     = TourLocation::insertItem($insertTourLocation);
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
        /* Message */
        return redirect()->route('admin.tourLocation.list');
    }

    public function update(Request $request){
        /* upload image */
        $dataPath           = [];
        if($request->hasFile('image')) {
            $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
            $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
        }
        /* update page */
        $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), $dataPath);
        $flagUpdatePage     = Seo::updateItem($request->get('seo_id'), $updatePage);
        /* update TourLocation */
        $updateTourLocation = $this->BuildInsertUpdateModel->buildArrayTableTourLocation($request->all());
        $flagUpdateCate     = TourLocation::updateItem($request->get('tourLocation_id'), $updateTourLocation);
        /* insert slider và lưu CSDL */
        if($request->hasFile('slider')){
            $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
            $params         = [
                'attachment_id'     => $request->get('tourLocation_id'),
                'relation_table'    => 'tour_location',
                'name'              => $name
            ];
            AdminSliderController::uploadSlider($request->file('slider'), $params);
        }
        return redirect()->route('admin.tourLocation.list');
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

    // private function updateLevelChild($idPage){
    //     $child          = Seo::select('id')->where('parent', $idPage)->get();
    //     if(!empty($child)){
    //         $infoParent = Seo::select('level')->where('id', $idPage)->firstOrFail();
    //         $level      = $infoParent->level;
    //         $levelChild = $level + 1;
    //         foreach($child as $item){
    //             /* update level bảng seo */
    //             Seo::updateItem($item->id, ['level' => $levelChild]);
    //             /* update level bảng TourLocation_info */
    //             TourLocation::select('*')->where('page_id', $item->id)->update(['TourLocation_level' => $levelChild]);
    //             /* update tiếp phẩn tử con */
    //             $this->updateLevelChild($item->id);
    //         }
    //     }
    // }
}
