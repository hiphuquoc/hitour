<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Requests\PageRequest;
use App\Models\Category;
use App\Models\Page;
use App\Models\Seo;
use App\Models\RelationCategoryInfoBlogInfo;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use App\Jobs\CheckSeo;

class AdminPageController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params             = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* paginate */
        $viewPerPage        = Cookie::get('viewPageInfo') ?? 50;
        $params['paginate'] = $viewPerPage;
        $list               = Page::getList($params);
        return view('admin.page.list', compact('list', 'params', 'viewPerPage'));
    }

    public function view(Request $request){
        $parents        = Page::select('*')
                            ->with('seo')
                            ->get();
        $id             = $request->get('id') ?? 0;
        $item           = Page::select('*')
                            ->where('id', $id)
                            ->with('seo')
                            ->first();
        $content        = null;
        if(!empty($item->seo->slug)){
            $content    = Storage::get(config('admin.storage.contentPage').$item->seo->slug.'.blade.php');
        }
        /* type */
        $type           = !empty($item) ? 'edit' : 'create';
        $type           = $request->get('type') ?? $type;
        return view('admin.page.view', compact('parents', 'item', 'type', 'content'));
    }

    public function create(PageRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert seo */
            $insertSeo          = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'page_info', $dataPath);
            $seoId              = Seo::insertItem($insertSeo);
            /* insert page_info */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTablePageInfo($request->all(), $seoId);
            $idPage             = Page::insertItem($insertPage);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentPage').$request->get('slug').'.blade.php', $content);
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Trang mới'
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
        return redirect()->route('admin.page.view', ['id' => $idPage]);
    }

    public function update(PageRequest $request){
        try {
            DB::beginTransaction();
            $idPage         = $request->get('page_info_id');
            /* upload image */
            $dataPath           = [];
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update seo */
            $updateSeo          = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'page_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updateSeo);
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTablePageInfo($request->all(), $request->get('seo_id'));
            Page::updateItem($idPage, $updatePage);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentPage').$request->get('slug').'.blade.php', $content);
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
        return redirect()->route('admin.page.view', ['id' => $idPage]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id         = $request->get('id');
                $info       = Page::select('*')
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
                /* xóa page_info */
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
