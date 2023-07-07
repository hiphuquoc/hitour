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
use App\Models\QuestionAnswer;
use App\Models\RelationTourLocationShipLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ShipLocationRequest;
use App\Jobs\CheckSeo;
use App\Models\Category;
use App\Models\RelationShipLocationCategoryInfo;

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
                            }])
                            ->with(['questions' => function($query){
                                $query->where('relation_table', 'ship_location');
                            }])
                            ->with('seo')
                            ->first();
        $provinces      = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts      = District::getItemByIdProvince($item->province_id ?? 0);
        $categories     = Category::all();
        $schedule       = null;
        if(!empty($item->seo->slug)){
            $schedule   = Storage::get(config('admin.storage.contentSchedule').$item->seo->slug.'.blade.php');
        }
        $content        = null;
        if(!empty($item->seo->slug)){
            $content    = Storage::get(config('admin.storage.contentShipLocation').$item->seo->slug.'.blade.php');
        }
        $message        = $request->get('message') ?? null; 
        $type           = !empty($item) ? 'edit' : 'create';
        $type           = $request->get('type') ?? $type;
        return view('admin.shipLocation.view', compact('item', 'categories', 'schedule', 'content', 'type', 'provinces', 'districts', 'message'));
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
            $seoId              = Seo::insertItem($insertPage);
            /* insert ship_location */
            $insertShipLocation = $this->BuildInsertUpdateModel->buildArrayTableShipLocation($request->all(), $seoId);
            $idShipLocation     = ShipLocation::insertItem($insertShipLocation);
            /* lưu schedule vào file */
            $schedule           = $request->get('schedule') ?? null;
            if(!empty($schedule)) {
                Storage::put(config('admin.storage.contentSchedule').$request->get('slug').'.blade.php', $schedule);
            }else {
                @unlink(Storage::path(config('admin.storage.contentSchedule').$request->get('slug').'.blade.php', $schedule));
            }
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentShipLocation').$request->get('slug').'.blade.php', $content);
            /* insert realtion ship_location và category_info */
            if(!empty($request->get('category_info_id'))){
                foreach($request->get('category_info_id') as $idCategory){
                    if(!empty($idCategory)){
                        RelationShipLocationCategoryInfo::insertItem([
                            'ship_location_id'  => $idShipLocation,
                            'category_info_id'  => $idCategory
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
                            'relation_table'    => 'ship_location',
                            'reference_id'      => $idShipLocation
                        ]);
                    }
                }
            }
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
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($seoId);
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.shipLocation.view', ['id' => $idShipLocation]);
    }

    public function update(ShipLocationRequest $request){
        try {
            DB::beginTransaction();
            $idShipLocation     = $request->get('ship_location_id') ?? 0;
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
            ShipLocation::updateItem($idShipLocation, $updateShipLocation);
            /* lưu schedule vào file */
            $schedule           = $request->get('schedule') ?? null;
            if(!empty($schedule)) {
                Storage::put(config('admin.storage.contentSchedule').$request->get('slug').'.blade.php', $schedule);
            }else {
                @unlink(Storage::path(config('admin.storage.contentSchedule').$request->get('slug').'.blade.php', $schedule));
            }
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentShipLocation').$request->get('slug').'.blade.php', $content);
            /* update realtion ship_location và category_info */
            RelationShipLocationCategoryInfo::select('*')
                ->where('ship_location_id', $idShipLocation)
                ->delete();
            if(!empty($request->get('category_info_id'))){
                foreach($request->get('category_info_id') as $idCategory){
                    if(!empty($idCategory)){
                        RelationShipLocationCategoryInfo::insertItem([
                            'ship_location_id'  => $idShipLocation,
                            'category_info_id'  => $idCategory
                        ]);
                    }
                }
            }
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'ship_location')
                            ->where('reference_id', $idShipLocation)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'ship_location',
                            'reference_id'      => $idShipLocation
                        ]);
                    }
                }
            }
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
        return redirect()->route('admin.shipLocation.view', ['id' => $idShipLocation]);
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
                        ->where('relation_table', 'ship_location')
                        ->where('reference_id', $info->id)
                        ->delete();
                /* xóa relation_tour_location_ship_location */
                RelationTourLocationShipLocation::select('*')
                    ->where('ship_location_id', $info->id)
                    ->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
