<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Models\TourContinent;
use App\Models\ServiceLocation;
use App\Models\AirLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\Guide;
use App\Models\QuestionAnswer;
use App\Models\RelationTourContinentServiceLocation;
use App\Models\RelationTourContinentAirLocation;
use App\Models\RelationTourContinentGuideInfo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\TourContinentRequest;
use App\Jobs\CheckSeo;

class AdminTourContinentController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* lấy dữ liệu */
        $list           = TourContinent::getList($params);
        return view('admin.tourContinent.list', compact('list', 'params'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = TourContinent::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_continent');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'tour_continent');
                                }])
                                ->with('seo', 'guides.infoGuide.seo', 'serviceLocations.infoServiceLocation.seo', 'airLocations.infoAirLocation.seo')
                                ->first();
        $guides             = Guide::all();
        $serviceLocations   = ServiceLocation::all();
        $airLocations       = AirLocation::all();
        // $content        = null;
        // if(!empty($item->seo->slug)){
        //     $content    = Storage::get(config('admin.storage.contentTourContinent').$item->seo->slug.'.blade.php');
        // }
        $message            = $request->get('message') ?? null; 
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.tourContinent.view', compact('item', 'type', 'guides', 'serviceLocations', 'airLocations', 'message'));
    }

    public function create(TourContinentRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_continent', $dataPath);
            $seoId              = Seo::insertItem($insertPage);
            /* insert tour_continent */
            $insertTourContinent = $this->BuildInsertUpdateModel->buildArrayTableTourContinent($request->all(), $seoId);
            $idTourContinent     = TourContinent::insertItem($insertTourContinent);
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourContinent').$request->get('slug').'.blade.php', $request->get('content'));
            /* relation tour_continent và guide_info */
            if(!empty($request->get('guide_info_id'))){
                foreach($request->get('guide_info_id') as $idGuideInfo){
                    $insertRelationTourContinentGuideInfo    = [
                        'tour_continent_id'     => $idTourContinent,
                        'guide_info_id'         => $idGuideInfo
                    ];
                    RelationTourContinentGuideInfo::insertItem($insertRelationTourContinentGuideInfo);
                }
            }
            /* relation tour_continent và service_location */
            if(!empty($request->get('service_location_id'))){
                foreach($request->get('service_location_id') as $idServiceLocation){
                    $insertRelationTourContinentServiceLocation    = [
                        'tour_continent_id'     => $idTourContinent,
                        'service_location_id'   => $idServiceLocation
                    ];
                    RelationTourContinentServiceLocation::insertItem($insertRelationTourContinentServiceLocation);
                }
            }
            /* relation tour_continent và air_location */
            if(!empty($request->get('air_location_id'))){
                foreach($request->get('air_location_id') as $idAirLocation){
                    $insertRelationTourContinentAirLocation    = [
                        'tour_continent_id'     => $idTourContinent,
                        'air_location_id'       => $idAirLocation
                    ];
                    RelationTourContinentAirLocation::insertItem($insertRelationTourContinentAirLocation);
                }
            }
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_continent',
                            'reference_id'      => $idTourContinent
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourContinent,
                    'relation_table'    => 'tour_continent',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Châu lục mới'
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
        return redirect()->route('admin.tourContinent.view', ['id' => $idTourContinent]);
    }

    public function update(TourContinentRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_continent', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update TourContinent */
            $idTourContinent     = $request->get('tour_continent_id');
            $updateTourContinent = $this->BuildInsertUpdateModel->buildArrayTableTourContinent($request->all());
            TourContinent::updateItem($idTourContinent, $updateTourContinent);
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourContinent').$request->get('slug').'.blade.php', $request->get('content'));
            /* relation tour_continent và guide_info */
            RelationTourContinentGuideInfo::select('*')
                ->where('tour_continent_id', $idTourContinent)
                ->delete();
            if(!empty($request->get('guide_info_id'))){
                foreach($request->get('guide_info_id') as $idGuideInfo){
                    $insertRelationTourContinentGuideInfo    = [
                        'tour_continent_id'     => $idTourContinent,
                        'guide_info_id'         => $idGuideInfo
                    ];
                    RelationTourContinentGuideInfo::insertItem($insertRelationTourContinentGuideInfo);
                }
            }
            /* relation tour_continent và service_location */
            RelationTourContinentServiceLocation::select('*')
                ->where('tour_continent_id', $idTourContinent)
                ->delete();
            if(!empty($request->get('service_location_id'))){
                foreach($request->get('service_location_id') as $idServiceLocation){
                    $insertRelationTourContinentServiceLocation    = [
                        'tour_continent_id'     => $idTourContinent,
                        'service_location_id'   => $idServiceLocation
                    ];
                    RelationTourContinentServiceLocation::insertItem($insertRelationTourContinentServiceLocation);
                }
            }
            /* relation tour_continent và air_location */
            RelationTourContinentAirLocation::select('*')
                ->where('tour_continent_id', $idTourContinent)
                ->delete();
            if(!empty($request->get('air_location_id'))){
                foreach($request->get('air_location_id') as $idAirLocation){
                    $insertRelationTourContinentAirLocation    = [
                        'tour_continent_id'     => $idTourContinent,
                        'air_location_id'       => $idAirLocation
                    ];
                    RelationTourContinentAirLocation::insertItem($insertRelationTourContinentAirLocation);
                }
            }
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                        ->where('relation_table', 'tour_continent')
                        ->where('reference_id', $idTourContinent)
                        ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_continent',
                            'reference_id'      => $idTourContinent
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourContinent,
                    'relation_table'    => 'tour_continent',
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
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($request->get('seo_id'));
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.tourContinent.view', ['id' => $idTourContinent]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = TourContinent::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_continent');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng tour_continent */
                TourContinent::find($id)->delete();
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
