<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Controllers\AdminSliderController;
use App\Models\TourCountry;
use App\Models\TourContinent;
use App\Models\ServiceLocation;
use App\Models\AirLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\Guide;
use App\Models\QuestionAnswer;
use App\Http\Requests\TourCountryRequest;
use App\Models\RelationTourCountryGuideInfo;
use App\Models\RelationTourCountryServiceLocation;
use App\Models\RelationTourCountryAirLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\CheckSeo;

class AdminTourCountryController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo châu lục */
        if(!empty($request->get('search_continent'))) $params['search_continent'] = $request->get('search_continent');
        /* lấy dữ liệu */
        $list           = TourCountry::getList($params);
        $tourContinents = TourContinent::all();
        return view('admin.tourCountry.list', compact('list', 'tourContinents', 'params'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = TourCountry::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_country');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'tour_country');
                                }])
                                ->with('seo', 'guides.infoGuide.seo', 'serviceLocations.infoServiceLocation.seo', 'airLocations.infoAirLocation.seo')
                                ->first();
        $tourContinents     = TourContinent::all();
        $parents            = $tourContinents;
        $guides             = Guide::all();
        $serviceLocations   = ServiceLocation::all();
        $airLocations       = AirLocation::all();
        // $content        = null;
        // if(!empty($item->seo->slug)){
        //     $content    = Storage::get(config('admin.storage.contentTourCountry').$item->seo->slug.'.blade.php');
        // }
        $message            = $request->get('message') ?? null; 
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.tourCountry.view', compact('item', 'type', 'parents', 'tourContinents', 'guides', 'serviceLocations', 'airLocations', 'message'));
    }

    public function create(TourCountryRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_country', $dataPath);
            $seoId              = Seo::insertItem($insertPage);
            /* insert tour_country */
            $insertTourCountry  = $this->BuildInsertUpdateModel->buildArrayTableTourCountry($request->all(), $seoId);
            $idTourCountry      = TourCountry::insertItem($insertTourCountry);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_country',
                            'reference_id'      => $idTourCountry
                        ]);
                    }
                }
            }
            /* relation tour_country và guide_info */
            if(!empty($request->get('guide_info_id'))){
                foreach($request->get('guide_info_id') as $idGuideInfo){
                    $insertRelationTourCountryGuideInfo    = [
                        'tour_country_id'  => $idTourCountry,
                        'guide_info_id'     => $idGuideInfo
                    ];
                    RelationTourCountryGuideInfo::insertItem($insertRelationTourCountryGuideInfo);
                }
            }
            /* relation tour_country và service_location */
            if(!empty($request->get('service_location_id'))){
                foreach($request->get('service_location_id') as $idServiceLocation){
                    $insertRelationTourCountryServiceLocation    = [
                        'tour_country_id'       => $idTourCountry,
                        'service_location_id'   => $idServiceLocation
                    ];
                    RelationTourCountryServiceLocation::insertItem($insertRelationTourCountryServiceLocation);
                }
            }
            /* relation tour_country và air_location */
            if(!empty($request->get('air_location_id'))){
                foreach($request->get('air_location_id') as $idAirLocation){
                    $insertRelationTourCountryAirLocation    = [
                        'tour_country_id'       => $idTourCountry,
                        'air_location_id'       => $idAirLocation
                    ];
                    RelationTourCountryAirLocation::insertItem($insertRelationTourCountryAirLocation);
                }
            }
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourCountry').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourCountry,
                    'relation_table'    => 'tour_country',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Quốc gia tour mới'
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
        return redirect()->route('admin.tourCountry.view', ['id' => $idTourCountry]);
    }

    public function update(TourCountryRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_country', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update TourCountry */
            $idTourCountry     = $request->get('tour_country_id');
            $updateTourCountry = $this->BuildInsertUpdateModel->buildArrayTableTourCountry($request->all());
            TourCountry::updateItem($idTourCountry, $updateTourCountry);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                        ->where('relation_table', 'tour_country')
                        ->where('reference_id', $idTourCountry)
                        ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_country',
                            'reference_id'      => $idTourCountry
                        ]);
                    }
                }
            }
            /* relation tour_country và guide_info */
            RelationTourCountryGuideInfo::select('*')
                ->where('tour_country_id', $idTourCountry)
                ->delete();
            if(!empty($request->get('guide_info_id'))){
                foreach($request->get('guide_info_id') as $idGuideInfo){
                    $insertRelationTourCountryGuideInfo    = [
                        'tour_country_id'  => $idTourCountry,
                        'guide_info_id'     => $idGuideInfo
                    ];
                    RelationTourCountryGuideInfo::insertItem($insertRelationTourCountryGuideInfo);
                }
            }
            /* relation tour_country và service_location */
            RelationTourCountryServiceLocation::select('*')
                ->where('tour_country_id', $idTourCountry)
                ->delete();
            if(!empty($request->get('service_location_id'))){
                foreach($request->get('service_location_id') as $idServiceLocation){
                    $insertRelationTourCountryServiceLocation    = [
                        'tour_country_id'       => $idTourCountry,
                        'service_location_id'   => $idServiceLocation
                    ];
                    RelationTourCountryServiceLocation::insertItem($insertRelationTourCountryServiceLocation);
                }
            }
            /* relation tour_country và air_location */
            RelationTourCountryAirLocation::select('*')
                ->where('tour_country_id', $idTourCountry)
                ->delete();
            if(!empty($request->get('air_location_id'))){
                foreach($request->get('air_location_id') as $idAirLocation){
                    $insertRelationTourCountryAirLocation    = [
                        'tour_country_id'       => $idTourCountry,
                        'air_location_id'       => $idAirLocation
                    ];
                    RelationTourCountryAirLocation::insertItem($insertRelationTourCountryAirLocation);
                }
            }
            /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTourCountry').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourCountry,
                    'relation_table'    => 'tour_country',
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
        return redirect()->route('admin.tourCountry.view', ['id' => $idTourCountry]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = TourCountry::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_country');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng tour_country */
                TourCountry::find($id)->delete();
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
