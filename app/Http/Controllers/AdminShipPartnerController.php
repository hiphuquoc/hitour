<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ShipPartnerRequest;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\ShipPartner;
use App\Models\Seo;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\CheckSeo;

class AdminShipPartnerController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(){
        $list               = ShipPartner::getList();
        return view('admin.shipPartner.list', compact('list'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = ShipPartner::select('*')
                            ->where('id', $id)
                            ->with(['questions' => function($query){
                                $query->where('relation_table', 'ship_partner');
                            }])
                            ->with('seo')
                            ->first();
        $content            = null;
        if(!empty($item->seo->slug)){
            $content        = Storage::get(config('admin.storage.contentShipPartner').$item->seo->slug.'.blade.php');
        }
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.shipPartner.view', compact('item', 'type', 'content'));
    }

    public function create(ShipPartnerRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = null;
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* insert seo */
            $request->merge(['title' => $request->get('company_name')]);
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'ship_partner', $dataPath);
            $seoId              = Seo::insertItem($insertPage);
            /* insert ship_partner */
            $insertPartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTableShipPartner($request->all(), $seoId, $dataPath['filePathNormal']);
            $idShipPartner      = ShipPartner::insertItem($insertPartnerInfo);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentShipPartner').$request->get('slug').'.blade.php', $content);
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'ship_partner',
                            'reference_id'      => $idShipPartner
                        ]);
                    }
                }
            }
            DB::commit();
            /* Message */
            $message            = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo đối tác Tàu mới'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message            = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($seoId);
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.shipPartner.view', ['id' => $idShipPartner]);
    }

    public function update(ShipPartnerRequest $request){
        try {
            DB::beginTransaction();
            $idShipPartner      = $request->get('id') ?? 0;
            /* upload image */
            $dataPath           = null;
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update seo */
            $request->merge(['title' => $request->get('company_name')]);
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'ship_partner', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update partner_info */
            $updatePartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTableShipPartner($request->all(), null, $dataPath['filePathNormal'] ?? null);
            ShipPartner::updateItem($idShipPartner, $updatePartnerInfo);
            /* lưu content vào file */
            $content            = $request->get('content') ?? null;
            $content            = AdminImageController::replaceImageInContentWithLoading($content);
            Storage::put(config('admin.storage.contentShipPartner').$request->get('slug').'.blade.php', $content);
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'ship_partner')
                            ->where('reference_id', $idShipPartner)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'ship_partner',
                            'reference_id'      => $idShipPartner
                        ]);
                    }
                }
            }
            DB::commit();
            /* Message */
            $message            = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Các thay đổi đã được lưu'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message            = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($request->get('seo_id'));
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.shipPartner.view', ['id' => $idShipPartner]);
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id                 = $request->get('id');
                /* lấy thông tin */
                $infoPartner        = ShipPartner::select('*')
                                    ->where('id', $id)
                                    ->with('seo')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục */
                $imageSmallPath     = Storage::path(config('admin.images.folderUpload').basename($infoPartner->seo->image_small));
                if(file_exists($imageSmallPath)) @unlink($imageSmallPath);
                $imagePath          = Storage::path(config('admin.images.folderUpload').basename($infoPartner->seo->image));
                if(file_exists($imagePath)) @unlink($imagePath);
                /* delete bảng ship_partner */
                $infoPartner->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }
}
