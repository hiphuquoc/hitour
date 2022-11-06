<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Controllers\AdminSliderController;
use App\Models\CarrentalLocation;
use App\Models\Seo;
use App\Services\BuildInsertUpdateModel;
use App\Models\District;
use App\Models\Province;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CarrentalLocationRequest;
use App\Jobs\CheckSeo;

class AdminCarrentalLocationController extends Controller {

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
        $list           = CarrentalLocation::getList($params);
        return view('admin.carrentalLocation.list', compact('list', 'params'));
    }

    public function view(Request $request){
        $id             = $request->get('id') ?? 0;
        $item           = CarrentalLocation::select('*')
                            ->where('id', $id)
                            ->with(['files' => function($query){
                                $query->where('relation_table', 'carrental_location');
                            }])
                            ->with(['questions' => function($query){
                                $query->where('relation_table', 'carrental_location');
                            }])
                            ->with('seo', 'region', 'province', 'district')
                            ->first();
                            
        $provinces      = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts      = District::getItemByIdProvince($item->province_id ?? 0);
        $content        = null;
        if(!empty($item->seo->slug)){
            $content    = Storage::get(config('admin.storage.contentCarrentalLocation').$item->seo->slug.'.blade.php');
        }
        $message        = $request->get('message') ?? null; 
        $type           = !empty($item) ? 'edit' : 'create';
        $type           = $request->get('type') ?? $type;
        return view('admin.carrentalLocation.view', compact('item', 'type', 'provinces', 'districts', 'content', 'message'));
    }

    public function create(CarrentalLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'carrental_location', $dataPath);
            $seoId             = Seo::insertItem($insertPage);
            /* insert carrental_location */
            $insertCarrentalLocation = $this->BuildInsertUpdateModel->buildArrayTableCarrentalLocation($request->all(), $seoId);
            $idCarrentalLocation     = CarrentalLocation::insertItem($insertCarrentalLocation);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentCarrentalLocation').$request->get('slug').'.blade.php', $content);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'carrental_location',
                            'reference_id'      => $idCarrentalLocation
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idCarrentalLocation,
                    'relation_table'    => 'carrental_location',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Cho thuê xe mới'
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
        return redirect()->route('admin.carrentalLocation.view', ['id' => $idCarrentalLocation]);
    }

    public function update(CarrentalLocationRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'carrental_location', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update CarrentalLocation */
            $idCarrentalLocation     = $request->get('carrental_location_id');
            $updateCarrentalLocation = $this->BuildInsertUpdateModel->buildArrayTableCarrentalLocation($request->all());
            CarrentalLocation::updateItem($idCarrentalLocation, $updateCarrentalLocation);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentCarrentalLocation').$request->get('slug').'.blade.php', $content);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'carrental_location')
                            ->where('reference_id', $request->get('carrental_location_id'))
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'carrental_location',
                            'reference_id'      => $request->get('carrental_location_id')
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idCarrentalLocation,
                    'relation_table'    => 'carrental_location',
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
        return redirect()->route('admin.carrentalLocation.view', ['id' => $idCarrentalLocation]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = CarrentalLocation::select('*')
                                ->where('id', $id)
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'carrental_location');
                                }])
                                ->with('seo')
                                ->first();
                /* delete bảng carrental_location */
                CarrentalLocation::find($id)->delete();
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
