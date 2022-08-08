<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;

use App\Models\TourDeparture;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;

use App\Models\SystemFile;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\TourDepartureRequest;

class AdminTourDepartureController extends Controller {

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
        $list               = TourDeparture::getList($params);
        return view('admin.tourDeparture.list', compact('list', 'params'));
    }

    public function viewEdit(Request $request, $id){
        if(!empty($id)){
            $item           = TourDeparture::select('*')
                                            ->where('id', $id)
                                            ->with('seo', 'files')
                                            ->first();
            $message        = $request->get('message') ?? null; 
            $type           = 'edit';
            if(!empty($request->get('type'))) $type = $request->get('type');
            if(!empty($item)) return view('admin.tourDeparture.view', compact('item', 'type', 'message'));

        }
        return redirect()->route('admin.tourDeparture.list');
    }

    public function viewInsert(Request $request){
        $type               = 'create';
        return view('admin.tourDeparture.view', compact('type'));
    }

    public function create(TourDepartureRequest $request){
        /* upload image */
        $dataPath               = [];
        if($request->hasFile('image')) {
            $name               = !empty($request->get('slug')) ? $request->get('slug') : time();
            $dataPath           = Upload::uploadThumnail($request->file('image'), $name);
        }
        /* insert page */
        $insertPage             = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_departure', $dataPath);
        $pageId                 = Seo::insertItem($insertPage);
        /* insert tour_departure */
        $insertTourDeparture    = $this->BuildInsertUpdateModel->buildArrayTableTourDeparture($request->all(), $pageId);
        $idTourDeparture        = TourDeparture::insertItem($insertTourDeparture);
        /* insert slider và lưu CSDL */
        if($request->hasFile('slider')){
            $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
            $params         = [
                'attachment_id'     => $idTourDeparture,
                'relation_table'    => 'tour_location',
                'name'              => $name
            ];
            AdminSliderController::uploadSlider($request->file('slider'), $params);
        }
        /* Message */
        $message        = [
            'type'      => 'success',
            'message'   => '<strong>Thành công!</strong> Đã tạo Điểm khởi hành mới'
        ];
        return redirect()->route('admin.tourDeparture.viewEdit', [
            'id'        => $idTourDeparture,
            'message'   => $message
        ]);
    }

    public function update(TourDepartureRequest $request){
        /* upload image */
        $dataPath               = [];
        if($request->hasFile('image')) {
            $name               = !empty($request->get('slug')) ? $request->get('slug') : time();
            $dataPath           = Upload::uploadThumnail($request->file('image'), $name);
        }
        /* update page */
        $updatePage             = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_departure', $dataPath);
        Seo::updateItem($request->get('seo_id'), $updatePage);
        /* update TourDeparture */
        $updateTourDeparture    = $this->BuildInsertUpdateModel->buildArrayTableTourDeparture($request->all());
        TourDeparture::updateItem($request->get('tour_departure_id'), $updateTourDeparture);
        /* insert slider và lưu CSDL */
        if($request->hasFile('slider')){
            $name               = !empty($request->get('slug')) ? $request->get('slug') : time();
            $params             = [
                'attachment_id'     => $request->get('tour_departure_id'),
                'relation_table'    => 'tour_location',
                'name'              => $name
            ];
            AdminSliderController::uploadSlider($request->file('slider'), $params);
        }
        /* Message */
        $message        = [
            'type'      => 'success',
            'message'   => '<strong>Thành công!</strong> Các thay đổi đã được lưu'
        ];
        return redirect()->route('admin.tourDeparture.viewEdit', [
            'id'        => $request->get('tour_departure_id'),
            'message'   => $message
        ]);
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
                TourDeparture::find($id)->delete();
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
