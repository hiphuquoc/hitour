<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Models\HotelLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\District;
use App\Models\Province;
use App\Models\QuestionAnswer;
use App\Models\RelationTourLocationHotelLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\HotelLocationRequest;
use App\Jobs\CheckSeo;

class AdminHotelLocationController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params             = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo vùng miền */
        if(!empty($request->get('search_region'))) $params['search_region'] = $request->get('search_region');
        /* paginate */
        $viewPerPage        = Cookie::get('viewHotelLocation') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list               = HotelLocation::getList($params);
        return view('admin.hotelLocation.list', compact('list', 'params', 'viewPerPage'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = HotelLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'hotel_location');
                                }])
                                ->with(['questions' => function($query){
                                    $query->where('relation_table', 'hotel_location');
                                }])
                                ->with('seo')
                                ->first();
                                // dd($item->questions);
        $provinces          = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts          = District::getItemByIdProvince($item->province_id ?? 0);
        $content        = null;
        if(!empty($item->seo->slug)){
            $content    = Storage::get(config('admin.storage.contentHotelLocation').$item->seo->slug.'.blade.php');
        }
        $message            = $request->get('message') ?? null; 
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.hotelLocation.view', compact('item', 'type', 'provinces', 'districts', 'content', 'message'));
    }

    public function create(HotelLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'hotel_location', $dataPath);
            $seoId              = Seo::insertItem($insertPage);
            /* insert hotel_location */
            $insertHotelLocation    = $this->BuildInsertUpdateModel->buildArrayTableHotelLocation($request->all(), $seoId);
            $idHotelLocation        = HotelLocation::insertItem($insertHotelLocation);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'hotel_location',
                            'reference_id'      => $idHotelLocation
                        ]);
                    }
                }
            }
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentHotelLocation').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotelLocation,
                    'relation_table'    => 'hotel_location',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Điểm đến Hotel mới'
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
        return redirect()->route('admin.hotelLocation.view', ['id' => $idHotelLocation]);
    }

    public function update(HotelLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'hotel_location', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update TourLocation */
            $idHotelLocation     = $request->get('hotel_location_id');
            $updateHotelLocation = $this->BuildInsertUpdateModel->buildArrayTableHotelLocation($request->all());
            HotelLocation::updateItem($idHotelLocation, $updateHotelLocation);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                        ->where('relation_table', 'hotel_location')
                        ->where('reference_id', $idHotelLocation)
                        ->delete();
             if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'hotel_location',
                            'reference_id'      => $idHotelLocation
                        ]);
                    }
                }
            }
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentHotelLocation').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotelLocation,
                    'relation_table'    => 'hotel_location',
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
        return redirect()->route('admin.hotelLocation.view', ['id' => $idHotelLocation]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = HotelLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'hotel_location');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng hotel_location */
                HotelLocation::find($id)->delete();
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
                /* xóa question */
                QuestionAnswer::select('*')
                        ->where('relation_table', 'hotel_location')
                        ->where('reference_id', $info->id)
                        ->delete();
                /* xóa relation_tour_location_hotel_location */
                RelationTourLocationHotelLocation::select('*')
                    ->where('hotel_location_id', $info->id)
                    ->delete();
                /* commit */
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
