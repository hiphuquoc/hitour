<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Models\TourLocation;
use App\Models\ShipLocation;
use App\Models\CarrentalLocation;
use App\Models\ServiceLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\District;
use App\Models\Province;
use App\Models\Guide;
use App\Models\RelationTourLocationGuide;
use App\Models\SystemFile;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use App\Http\Requests\TourLocationRequest;
use App\Models\RelationTourLocationShipLocation;
use App\Models\RelationTourLocationCarrentalLocation;
use App\Models\RelationTourLocationServiceLocation;

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
        $id                 = $request->get('id') ?? 0;
        $item               = TourLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_location');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'tour_location');
                                }])
                                ->with('seo', 'guides', 'shipLocations.infoShipLocation', 'carrentalLocations.infoCarrentalLocation')
                                ->first();
        $provinces          = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts          = District::getItemByIdProvince($item->province_id ?? 0);
        $guides             = Guide::all();
        $shipLocations      = ShipLocation::all();
        $carrentalLocations = CarrentalLocation::all();
        $serviceLocations   = ServiceLocation::all();
        // $content        = null;
        // if(!empty($item->seo->slug)){
        //     $content    = Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php');
        // }
        $message            = $request->get('message') ?? null; 
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.tourLocation.view', compact('item', 'type', 'provinces', 'districts', 'guides', 'shipLocations', 'carrentalLocations', 'serviceLocations', 'message'));
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
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_location',
                            'reference_id'      => $idTourLocation
                        ]);
                    }
                }
            }
            /* relation tour và guide (cẩm nang du lịch) */
            if(!empty($request->get('guide_info_id'))){
                foreach($request->get('guide_info_id') as $idGuideInfo){
                    $insertRelationTourLocationGuide    = [
                        'tour_location_id'  => $idTourLocation,
                        'guide_info_id'     => $idGuideInfo
                    ];
                    RelationTourLocationGuide::insertItem($insertRelationTourLocationGuide);
                }
            }
            /* relation tour_location và ship_location */
            if(!empty($request->get('ship_location_id'))){
                foreach($request->get('ship_location_id') as $idShipLocation){
                    $insertRelationTourLocationShipLocation    = [
                        'tour_location_id'  => $idTourLocation,
                        'ship_location_id'  => $idShipLocation
                    ];
                    RelationTourLocationShipLocation::insertItem($insertRelationTourLocationShipLocation);
                }
            }
            /* relation tour_location và carrental_location */
            if(!empty($request->get('carrental_location_id'))){
                foreach($request->get('carrental_location_id') as $idCarrentalLocation){
                    $insertRelationTourLocationCarrentalLocation    = [
                        'tour_location_id'      => $idTourLocation,
                        'carrental_location_id' => $idCarrentalLocation
                    ];
                    RelationTourLocationCarrentalLocation::insertItem($insertRelationTourLocationCarrentalLocation);
                }
            }
            /* relation tour_location và service_location */
            if(!empty($request->get('service_location_id'))){
                foreach($request->get('service_location_id') as $idServiceLocation){
                    $insertRelationTourLocationServiceLocation    = [
                        'tour_location_id'      => $idTourLocation,
                        'service_location_id'   => $idServiceLocation
                    ];
                    RelationTourLocationServiceLocation::insertItem($insertRelationTourLocationServiceLocation);
                }
            }
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourLocation').$request->get('slug').'.blade.php', $request->get('content'));
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
            $idTourLocation     = $request->get('tour_location_id');
            $updateTourLocation = $this->BuildInsertUpdateModel->buildArrayTableTourLocation($request->all());
            TourLocation::updateItem($idTourLocation, $updateTourLocation);
            /* lưu content vào file */
            // /* tính năng convert content từ website cũ */
            // $tmp = explode(PHP_EOL, $request->get('content'));
            // for($i=0;$i<count($tmp);++$i){
            //     if(!empty(preg_match('/<img src="/', $tmp[$i]))&&!empty(preg_match('/<div class="imgNote">/', $tmp[$i+1]))) {
            //         $tmp[$i] = '<div class="imageBox">'.$tmp[$i];
            //     }
            //     if(!empty(preg_match('/<div class="imgNote">/', $tmp[$i]))) {
            //         $tmp[$i] = str_replace('<div class="imgNote">', '<div class="imageBox_note">', $tmp[$i]).'</div>';
            //     }
            // }
            // $tmp = implode(PHP_EOL, $tmp);
            // $tmp = str_replace('public/svg/loading_plane_e9ecef.svg', '{{ config("admin.images.default_750x460") }}', $tmp);
            // Storage::put(config('admin.storage.contentTourLocation').$request->get('slug').'.blade.php', $tmp);
            /* relation tour và guide (cẩm nang du lịch) */
            RelationTourLocationGuide::select('*')
                                ->where('tour_location_id', $idTourLocation)
                                ->delete();
            if(!empty($request->get('guide_info_id'))){
                foreach($request->get('guide_info_id') as $idGuideInfo){
                    $insertRelationTourLocationGuide    = [
                        'tour_location_id'  => $idTourLocation,
                        'guide_info_id'     => $idGuideInfo
                    ];
                    RelationTourLocationGuide::insertItem($insertRelationTourLocationGuide);
                }
            }
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                        ->where('relation_table', 'tour_location')
                        ->where('reference_id', $idTourLocation)
                        ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_location',
                            'reference_id'      => $idTourLocation
                        ]);
                    }
                }
            }
            /* relation tour_location và ship_location */
            RelationTourLocationShipLocation::select('*')
                                ->where('tour_location_id', $idTourLocation)
                                ->delete();
            if(!empty($request->get('ship_location_id'))){
                foreach($request->get('ship_location_id') as $idShipLocation){
                    $insertRelationTourLocationShipLocation    = [
                        'tour_location_id'  => $idTourLocation,
                        'ship_location_id'  => $idShipLocation
                    ];
                    RelationTourLocationShipLocation::insertItem($insertRelationTourLocationShipLocation);
                }
            }
            /* relation tour_location và carrental_location */
            RelationTourLocationCarrentalLocation::select('*')
                                ->where('tour_location_id', $idTourLocation)
                                ->delete();
            if(!empty($request->get('carrental_location_id'))){
                foreach($request->get('carrental_location_id') as $idCarrentalLocation){
                    $insertRelationTourLocationCarrentalLocation    = [
                        'tour_location_id'      => $idTourLocation,
                        'carrental_location_id' => $idCarrentalLocation
                    ];
                    RelationTourLocationCarrentalLocation::insertItem($insertRelationTourLocationCarrentalLocation);
                }
            }
            /* relation tour_location và service_location */
            RelationTourLocationServiceLocation::select('*')
                                ->where('tour_location_id', $idTourLocation)
                                ->delete();
            if(!empty($request->get('service_location_id'))){
                foreach($request->get('service_location_id') as $idServiceLocation){
                    $insertRelationTourLocationServiceLocation    = [
                        'tour_location_id'      => $idTourLocation,
                        'service_location_id'   => $idServiceLocation
                    ];
                    RelationTourLocationServiceLocation::insertItem($insertRelationTourLocationServiceLocation);
                }
            }
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourLocation').$request->get('slug').'.blade.php', $request->get('content'));
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
        return redirect()->route('admin.tourLocation.view', ['id' => $idTourLocation]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = TourLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_location');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng tour_location */
                TourLocation::find($id)->delete();
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
