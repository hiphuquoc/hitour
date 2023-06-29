<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Controllers\AdminImageController;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Models\ComboContent;
use App\Models\ComboLocation;
use App\Models\TourDeparture;
use App\Models\Combo;
use App\Models\RelationComboLocation;
use App\Models\RelationComboStaff;
use App\Models\RelationComboPartner;
use App\Models\Staff;
use App\Models\ComboPartner;
use App\Models\ComboPrice;
use App\Models\ComboOption;
use App\Models\Seo;
use App\Models\QuestionAnswer;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ComboRequest;
use App\Jobs\CheckSeo;

class AdminComboInfoController extends Controller {

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
        $viewPerPage        = Cookie::get('viewComboInfo') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list                           = Combo::getList($params);
        /* khu vực Combo */
        $comboLocations                 = ComboLocation::all();
        /* đối tác */
        $partners                       = ComboPartner::all();
        /* nhân viên */
        $staffs                         = Staff::all();
        return view('admin.combo.list', compact('list', 'params', 'viewPerPage', 'comboLocations', 'partners', 'staffs'));
    }

    public function view(Request $request){
        $comboLocations     = ComboLocation::all();
        $comboDepartures    = TourDeparture::all();
        $staffs             = Staff::all();
        $partners           = ComboPartner::all();
        $parents            = ComboLocation::select('*')
                                ->with('seo')
                                ->get();
        $message            = $request->get('message') ?? null;
        $id                 = $request->get('id') ?? 0;
        $item               = Combo::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'combo_info');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'combo_info');
                                }])
                                ->with('seo', 'contents')
                                ->first();
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.combo.view', compact('item', 'type', 'comboLocations', 'comboDepartures', 'staffs', 'partners', 'parents', 'message'));

        // return redirect()->route('admin.combo.list');
    }

    public function create(ComboRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'combo_info', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert combo_info */
            $insertComboInfo    = $this->BuildInsertUpdateModel->buildArrayTableComboInfo($request->all(), $pageId);
            $idCombo            = Combo::insertItem($insertComboInfo);
            /* insert combo_content */
            if(!empty($request->get('contents'))){
                foreach($request->get('contents') as $content){
                    if(!empty($content['name'])&&!empty($content['content'])){
                        ComboContent::insertItem([
                            'combo_info_id' => $idCombo,
                            'name'          => $content['name'],
                            'content'       => $content['content']
                        ]);
                    }
                }
            }
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'combo_info',
                            'reference_id'      => $idCombo
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idCombo)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idCombo,
                    'relation_table'    => 'combo_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idCombo)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idCombo,
                    'relation_table'    => 'combo_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_combo_location */
            if(!empty($idCombo)&&!empty($request->get('location'))){
                foreach($request->get('location') as $location){
                    $params     = [
                        'combo_info_id'      => $idCombo,
                        'combo_location_id'  => $location
                    ];
                    RelationComboLocation::insertItem($params);
                }
            }
            /* insert relation_combo_staff */
            if(!empty($idCombo)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'combo_info_id'      => $idCombo,
                        'staff_info_id'     => $staff
                    ];
                    RelationComboStaff::insertItem($params);
                }
            }
            /* insert relation_combo_partner */
            if(!empty($idCombo)&&!empty($request->get('partner'))){
                foreach($request->get('partner') as $partner){
                    $params     = [
                        'combo_info_id'      => $idCombo,
                        'partner_info_id'   => $partner
                    ];
                    RelationComboPartner::insertItem($params);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Combo mới'
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
        return redirect()->route('admin.combo.view', ['id' => $idCombo]);
    }

    public function update(ComboRequest $request){
        try {
            DB::beginTransaction();
            $idCombo             = $request->get('combo_info_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'combo_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update combo_info */
            $updateComboInfo     = $this->BuildInsertUpdateModel->buildArrayTableComboInfo($request->all(), $request->get('seo_id'));
            Combo::updateItem($idCombo, $updateComboInfo);
            /* update combo_content */
            ComboContent::select('*')
                            ->where('combo_info_id', $idCombo)
                            ->delete();
            if(!empty($request->get('contents'))){
                foreach($request->get('contents') as $content){
                    if(!empty($content['name'])&&!empty($content['content'])){
                        ComboContent::insertItem([
                            'combo_info_id' => $idCombo,
                            'name'          => $content['name'],
                            'content'       => $content['content']
                        ]);
                    }
                }
            }
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'combo_info')
                            ->where('reference_id', $idCombo)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'combo_info',
                            'reference_id'      => $idCombo
                        ]);
                    }
                }
            }
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idCombo)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idCombo,
                    'relation_table'    => 'combo_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idCombo)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idCombo,
                    'relation_table'    => 'combo_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* update relation_combo_location */
            RelationComboLocation::deleteAndInsertItem($idCombo, $request->get('location'));
            /* update relation_combo_staff */
            RelationComboStaff::deleteAndInsertItem($idCombo, $request->get('staff'));
            /* update relation_combo_partner */
            RelationComboPartner::deleteAndInsertItem($idCombo, $request->get('partner'));
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
        return redirect()->route('admin.combo.view', ['id' => $idCombo]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idCombo     = $request->get('id');
                /* lấy combo_option (with combo_price) */
                $infoCombo   = Combo::select('*')
                                    ->where('id', $idCombo)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'combo_info');
                                    }])
                                    ->with('seo', 'locations', 'staffs', 'partners', 'options.prices')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoCombo->seo->image)&&file_exists(public_path($infoCombo->seo->image))) unlink(public_path($infoCombo->seo->image));
                if(!empty($infoCombo->seo->image_small)&&file_exists(public_path($infoCombo->seo->image_small))) unlink(public_path($infoCombo->seo->image_small));
                /* xóa combo_content */
                ComboContent::select('*')
                            ->where('combo_info_id', $idCombo)
                            ->delete();
                /* xóa combo_option và combo_price */
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
                /* xóa relation combo_location */
                $arrayIdComboLocation    = [];
                foreach($infoCombo->locations as $location) $arrayIdComboLocation[] = $location->id;
                RelationComboLocation::select('*')->whereIn('id', $arrayIdComboLocation)->delete();
                /* xóa combo_staff */
                $arrayIdStaff           = [];
                foreach($infoCombo->staffs as $staff) $arrayIdStaff[] = $staff->id;
                RelationComboStaff::select('*')->whereIn('id', $arrayIdStaff)->delete();
                /* xóa combo_partner */
                $arrayIdPartner         = [];
                foreach($infoCombo->partners as $partner) $arrayIdPartner[] = $partner->id;
                RelationComboPartner::select('*')->whereIn('id', $arrayIdPartner)->delete();
                /* delete files - dùng removeSliderById cũng remove luôn cả gallery */
                if(!empty($infoCombo->files)){
                    foreach($infoCombo->files as $file) AdminSliderController::removeSliderById($file->id);
                }
                /* xóa seo */
                Seo::find($infoCombo->seo->id)->delete();
                /* xóa combo_info */
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
