<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Seo;
use App\Models\TourLocation;
use App\Models\RelationCategoryInfoTourLocation;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminCategoryController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params     = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        $data       = Category::select('*')
                        ->when(!empty($params['search_name']), function($query) use($params){
                            $query->where('name', 'LIKE', '%'.$params['search_name'].'%');
                        })
                        ->with('seo')
                        ->get();
        $list   = Category::getAllCategoryByTree($data);
        return view('admin.category.list', compact('list', 'params'));
    }

    public function view(Request $request){
        $parents        = Category::select('*')
                            ->with('seo')
                            ->get();
        $id             = $request->get('id') ?? 0;
        $item           = Category::select('*')
                            ->where('id', $id)
                            ->with('seo', 'tourLocations.infoTourLocation')
                            ->first();
        $tourLocations  = TourLocation::all();
        /* type */
        $type           = !empty($item) ? 'edit' : 'create';
        $type           = $request->get('type') ?? $type;
        return view('admin.category.view', compact('parents', 'tourLocations', 'item', 'type'));
    }

    public function create(CategoryRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert seo */
            $insertSeo          = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'category_info', $dataPath);
            $seoId              = Seo::insertItem($insertSeo);
            /* insert category_info */
            $insertCategory     = $this->BuildInsertUpdateModel->buildArrayTableCategoryInfo($request->all(), $seoId);
            $idCategory         = Category::insertItem($insertCategory);
            /* relation category_info và tour_location */
            if(!empty($request->get('tour_location_id'))){
                foreach($request->get('tour_location_id') as $idTourLocation){
                    $insertRelationCategoryInfoTourLocation    = [
                        'category_info_id'  => $idCategory,
                        'tour_location_id'  => $idTourLocation
                    ];
                    RelationCategoryInfoTourLocation::insertItem($insertRelationCategoryInfoTourLocation);
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Chuyên mục mới'
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
        return redirect()->route('admin.category.view', ['id' => $idCategory]);
    }

    public function update(CategoryRequest $request){
        try {
            DB::beginTransaction();
            $idCategory         = $request->get('category_info_id');
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update page */
            $updateSeo          = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'category_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updateSeo);
            /* update category */
            $updateCategory     = $this->BuildInsertUpdateModel->buildArrayTableCategoryInfo($request->all(), $request->get('seo_id'));
            Category::updateItem($idCategory, $updateCategory);
            /* update cột level và slug_full của child */
            $this->updateChild($request->get('seo_id'));
            /* relation category_info và tour_location */
            RelationCategoryInfoTourLocation::select('*')
                ->where('category_info_id', $idCategory)
                ->delete();
            if(!empty($request->get('tour_location_id'))){
                foreach($request->get('tour_location_id') as $idTourLocation){
                    $insertRelationCategoryInfoTourLocation    = [
                        'category_info_id'  => $idCategory,
                        'tour_location_id'  => $idTourLocation
                    ];
                    RelationCategoryInfoTourLocation::insertItem($insertRelationCategoryInfoTourLocation);
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
        return redirect()->route('admin.category.view', ['id' => $idCategory]);
    }

    private function updateChild($idPage){
        $child              = Seo::select('*')->where('parent', $idPage)->get();
        if(!empty($child)){
            $infoParent     = Seo::select('*')->where('id', $idPage)->firstOrFail();
            $level          = $infoParent->level;
            $levelChild     = $level + 1;
            foreach($child as $item){
                /* update level bảng seo */
                $slugFull   = Seo::buildFullUrl($item->slug, $levelChild, $infoParent->id);
                Seo::updateItem($item->id, [
                    'level'     => $levelChild,
                    'slug_full' => $slugFull
                ]);
                /* update tiếp phẩn tử con */
                $this->updateChild($item->id);
            }
        }
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = Category::select('*')
                                ->where('id', $id)
                                ->with('seo')
                                ->first();
                /* delete bảng seo */
                Seo::find($info->seo->id)->delete();
                /* xóa ảnh đại diện trong thư mục */
                $imageSmallPath     = Storage::path(config('admin.images.folderUpload').basename($info->seo->image_small));
                if(file_exists($imageSmallPath)) @unlink($imageSmallPath);
                $imagePath          = Storage::path(config('admin.images.folderUpload').basename($info->seo->image));
                if(file_exists($imagePath)) @unlink($imagePath);
                /* xóa category_info */
                $info->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
