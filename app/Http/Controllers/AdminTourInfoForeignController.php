<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Models\TourContentForeign;
use App\Models\TourCountry;
use App\Models\TourTimetableForeign;
use App\Models\TourInfoForeign;
use App\Models\TourDeparture;
use App\Models\RelationTourInfoForeignTourCountry;
use App\Models\RelationTourInfoForeignStaff;
use App\Models\RelationTourInfoForeignPartner;
use App\Models\Staff;
use App\Models\TourPartner;
use App\Models\Seo;
use App\Models\QuestionAnswer;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\TourInfoForeignRequest;
use App\Models\SystemFile;
use App\Models\TourContinent;

class AdminTourInfoForeignController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params                         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo châu lục */
        if(!empty($request->get('search_continent'))) $params['search_continent'] = $request->get('search_continent');
        /* Search theo đối tác */
        if(!empty($request->get('search_partner'))) $params['search_partner'] = $request->get('search_partner');
        /* Search theo nhân viên */
        if(!empty($request->get('search_staff'))) $params['search_staff'] = $request->get('search_staff');
        /* paginate */
        $viewPerPage        = Cookie::get('viewTourInfoForeign') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list                           = TourInfoForeign::getList($params);
        /* đối tác */
        $partners                       = TourPartner::all();
        /* nhân viên */
        $staffs                         = Staff::all();
        /* đối tác */
        $tourContinents                 = TourContinent::all();
        return view('admin.tourInfoForeign.list', compact('list', 'params', 'viewPerPage', 'partners', 'staffs', 'tourContinents'));
    }

    public function view(Request $request){
        $tourDepartures     = TourDeparture::all();
        $tourCountries      = TourCountry::all();
        $staffs             = Staff::all();
        $partners           = TourPartner::all();
        $parents            = TourCountry::select('*')
                                ->with('seo')
                                ->get();
        $message            = $request->get('message') ?? null;
        $id                 = $request->get('id') ?? 0;
        $item               = TourInfoForeign::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'tour_info_foreign');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'tour_info_foreign');
                                }])
                                ->with('seo', 'tourCountries')
                                ->first();
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.tourInfoForeign.view', compact('item', 'type', 'tourDepartures', 'tourCountries', 'staffs', 'partners', 'parents', 'message'));
    }

    public function create(TourInfoForeignRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_info_foreign', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert tour_info_foreign */
            $insertTourInfoForeign     = $this->BuildInsertUpdateModel->buildArrayTableTourInfoForeign($request->all(), $pageId);
            $idTourInfoForeign         = TourInfoForeign::insertItem($insertTourInfoForeign);
            /* insert tour_content_foreign */
            $insertTourInfoForeign     = $this->BuildInsertUpdateModel->buildArrayTableTourContentForeign($request->all(), $idTourInfoForeign);
            TourContentForeign::insertItem($insertTourInfoForeign);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_info_foreign',
                            'reference_id'      => $idTourInfoForeign
                        ]);
                    }
                }
            }
            // /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTour').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert tour_timetable */
            if(!empty($request->get('timetable'))){
                foreach($request->get('timetable') as $timetable){
                    $insertTourTimetableForeign    = [
                        'tour_info_foreign_id'  => $idTourInfoForeign,
                        'title'                 => $timetable['tour_timetable_title'],
                        'content'               => $timetable['tour_timetable_content'],
                        'content_sort'          => $timetable['tour_timetable_content_sort']
                    ];
                    TourTimetableForeign::insertItem($insertTourTimetableForeign);
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idTourInfoForeign)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourInfoForeign,
                    'relation_table'    => 'tour_info_foreign',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idTourInfoForeign)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourInfoForeign,
                    'relation_table'    => 'tour_info_foreign',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_tour_country */
            if(!empty($idTourInfoForeign)&&!empty($request->get('tour_country_id'))){
                foreach($request->get('tour_country_id') as $location){
                    $params     = [
                        'tour_info_foreign_id'  => $idTourInfoForeign,
                        'tour_country_id'       => $location
                    ];
                    RelationTourInfoForeignTourCountry::insertItem($params);
                }
            }
            /* insert relation_tour_staff */
            if(!empty($idTourInfoForeign)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'tour_info_foreign_id'  => $idTourInfoForeign,
                        'staff_info_id'         => $staff
                    ];
                    RelationTourInfoForeignStaff::insertItem($params);
                }
            }
            /* insert relation_tour_partner */
            if(!empty($idTourInfoForeign)&&!empty($request->get('partner'))){
                foreach($request->get('partner') as $partner){
                    $params     = [
                        'tour_info_foreign_id'      => $idTourInfoForeign,
                        'partner_info_id'   => $partner
                    ];
                    RelationTourInfoForeignPartner::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Tour mới'
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
        return redirect()->route('admin.tourInfoForeign.view', ['id' => $idTourInfoForeign]);
    }

    public function update(TourInfoForeignRequest $request){
        try {
            DB::beginTransaction();
            $idTourInfoForeign  = $request->get('tour_info_foreign_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'tour_info_foreign', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update tour_info_foreign */
            $updateTourInfoForeign      = $this->BuildInsertUpdateModel->buildArrayTableTourInfoForeign($request->all(), $request->get('seo_id'));
            TourInfoForeign::updateItem($idTourInfoForeign, $updateTourInfoForeign);
            /* update tour_content_foreign */
            TourContentForeign::select('*')
                            ->where('tour_info_foreign_id', $idTourInfoForeign)
                            ->delete();
            $insertTourInfoForeign     = $this->BuildInsertUpdateModel->buildArrayTableTourContentForeign($request->all(), $idTourInfoForeign);
            TourContentForeign::insertItem($insertTourInfoForeign);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'tour_info_foreign')
                            ->where('reference_id', $idTourInfoForeign)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'tour_info_foreign',
                            'reference_id'      => $idTourInfoForeign
                        ]);
                    }
                }
            }
            // /* lưu content vào file */
            // Storage::put(config('admin.storage.contentTour').$request->get('slug').'.blade.php', $request->get('content'));
            /* update tour_timetable */
            TourTimetableForeign::select('*')
                            ->where('tour_info_foreign_id', $idTourInfoForeign)
                            ->delete();
            if(!empty($request->get('timetable'))){
                foreach($request->get('timetable') as $timetable){
                    $insertTourTimetableForeign    = [
                        'tour_info_foreign_id'  => $idTourInfoForeign,
                        'title'                 => $timetable['tour_timetable_title'],
                        'content'               => $timetable['tour_timetable_content'],
                        'content_sort'          => $timetable['tour_timetable_content_sort']
                    ];
                    TourTimetableForeign::insertItem($insertTourTimetableForeign);
                }
            }
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idTourInfoForeign)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourInfoForeign,
                    'relation_table'    => 'tour_info_foreign',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idTourInfoForeign)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idTourInfoForeign,
                    'relation_table'    => 'tour_info_foreign',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* update relation_tour_country */
            RelationTourInfoForeignTourCountry::select('*')
                ->where('tour_info_foreign_id', $idTourInfoForeign)
                ->delete();
            if(!empty($idTourInfoForeign)&&!empty($request->get('tour_country_id'))){
                foreach($request->get('tour_country_id') as $location){
                    $params     = [
                        'tour_info_foreign_id'  => $idTourInfoForeign,
                        'tour_country_id'       => $location
                    ];
                    RelationTourInfoForeignTourCountry::insertItem($params);
                }
            }
            /* update relation_tour_staff */
            RelationTourInfoForeignStaff::select('*')
                ->where('tour_info_foreign_id', $idTourInfoForeign)
                ->delete();
            if(!empty($idTourInfoForeign)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'tour_info_foreign_id'  => $idTourInfoForeign,
                        'staff_info_id'         => $staff
                    ];
                    RelationTourInfoForeignStaff::insertItem($params);
                }
            }
            /* update relation_tour_partner */
            RelationTourInfoForeignPartner::select('*')
                ->where('tour_info_foreign_id', $idTourInfoForeign)
                ->delete();
            if(!empty($idTourInfoForeign)&&!empty($request->get('partner'))){
                foreach($request->get('partner') as $partner){
                    $params     = [
                        'tour_info_foreign_id'      => $idTourInfoForeign,
                        'partner_info_id'   => $partner
                    ];
                    RelationTourInfoForeignPartner::insertItem($params);
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
        $request->session()->put('message', $message);
        return redirect()->route('admin.tourInfoForeign.view', ['id' => $idTourInfoForeign]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idTourInfoForeign     = $request->get('id');
                /* lấy tour_option (with tour_price) */
                $infoTour   = TourInfoForeign::select('*')
                                    ->where('id', $idTourInfoForeign)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'tour_info_foreign');
                                    }])
                                    ->with('seo', 'tourCountries', 'staffs', 'partners')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoTour->seo->image)&&file_exists(public_path($infoTour->seo->image))) unlink(public_path($infoTour->seo->image));
                if(!empty($infoTour->seo->image_small)&&file_exists(public_path($infoTour->seo->image_small))) unlink(public_path($infoTour->seo->image_small));
                /* xóa tour_content */
                TourContentForeign::select('*')
                            ->where('tour_info_foreign_id', $idTourInfoForeign)
                            ->delete();
                /* xóa tour_timetable */
                TourTimetableForeign::select('*')
                            ->where('tour_info_foreign_id', $idTourInfoForeign)
                            ->delete();
                // /* xóa tour_option và tour_price */
                // $arrayIdOption  = [];
                // $arrayIdPrice   = [];
                // foreach($infoTour->options as $option){
                //     $arrayIdOption[]    = $option->id;
                //     foreach($option->prices as $price){
                //         $arrayIdPrice[] = $price->id;
                //     }
                // }
                // TourPrice::select('*')->whereIn('id', $arrayIdPrice)->delete();
                // TourOption::select('*')->whereIn('id', $arrayIdOption)->delete();
                /* xóa relation tour_country */
                $arrayIdTourCountries      = [];
                foreach($infoTour->tourCountries as $tourCountry) $arrayIdTourCountries[]    = $tourCountry->id;
                RelationTourInfoForeignTourCountry::select('*')->whereIn('id', $arrayIdTourCountries)->delete();
                /* xóa tour_staff */
                $arrayIdStaff           = [];
                foreach($infoTour->staffs as $staff) $arrayIdStaff[] = $staff->id;
                RelationTourInfoForeignStaff::select('*')->whereIn('id', $arrayIdStaff)->delete();
                /* xóa tour_partner */
                $arrayIdPartner         = [];
                foreach($infoTour->partners as $partner) $arrayIdPartner[] = $partner->id;
                RelationTourInfoForeignPartner::select('*')->whereIn('id', $arrayIdPartner)->delete();
                /* delete files - dùng removeSliderById cũng remove luôn cả gallery */
                if(!empty($infoTour->files)){
                    foreach($infoTour->files as $file) AdminSliderController::removeSliderById($file->id);
                }
                /* xóa seo */
                Seo::find($infoTour->seo->id)->delete();
                /* xóa tour_info_foreign */
                $infoTour->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
