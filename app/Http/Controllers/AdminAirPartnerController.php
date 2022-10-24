<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AirPartnerRequest;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\AirPartner;
use App\Models\Seo;
use App\Models\QuestionAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Jobs\CheckSeo;

class AdminAirPartnerController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(){
        $list               = AirPartner::getList();
        return view('admin.airPartner.list', compact('list'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = AirPartner::select('*')
                            ->where('id', $id)
                            ->with(['questions' => function($query){
                                $query->where('relation_table', 'air_partner');
                            }])
                            ->with('seo')
                            ->first();
        $content            = null;
        if(!empty($item->seo->slug)){
            $content        = Storage::get(config('admin.storage.contentAirPartner').$item->seo->slug.'.blade.php');
        }
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.airPartner.view', compact('item', 'type', 'content'));
    }

    public function create(AirPartnerRequest $request){
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
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'air_partner', $dataPath);
            $seoId              = Seo::insertItem($insertPage);
            /* insert air_partner */
            $insertPartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTableAirPartner($request->all(), $seoId, $dataPath['filePathNormal']);
            $idAirPartner      = AirPartner::insertItem($insertPartnerInfo);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentAirPartner').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'air_partner',
                            'reference_id'      => $idAirPartner
                        ]);
                    }
                }
            }
            DB::commit();
            /* Message */
            $message            = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo đối tác Vé máy bay mới'
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
        return redirect()->route('admin.airPartner.view', ['id' => $idAirPartner]);
    }

    public function update(AirPartnerRequest $request){
        try {
            DB::beginTransaction();
            $idAirPartner      = $request->get('id') ?? 0;
            /* upload image */
            $dataPath           = null;
            if($request->hasFile('image')) {
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $dataPath       = Upload::uploadThumnail($request->file('image'), $name);
            }
            /* update seo */
            $request->merge(['title' => $request->get('company_name')]);
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'air_partner', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update partner_info */
            $updatePartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTableAirPartner($request->all(), null, $dataPath['filePathNormal'] ?? null);
            AirPartner::updateItem($idAirPartner, $updatePartnerInfo);
            /* lưu content vào file */
            Storage::put(config('admin.storage.contentAirPartner').$request->get('slug').'.blade.php', $request->get('content'));
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                            ->where('relation_table', 'air_partner')
                            ->where('reference_id', $idAirPartner)
                            ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'air_partner',
                            'reference_id'      => $idAirPartner
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
        return redirect()->route('admin.airPartner.view', ['id' => $idAirPartner]);
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id                 = $request->get('id');
                /* lấy thông tin */
                $infoPartner        = AirPartner::select('*')
                                    ->where('id', $id)
                                    ->with('seo')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục */
                $imageSmallPath     = Storage::path(config('admin.images.folderUpload').basename($infoPartner->seo->image_small));
                if(file_exists($imageSmallPath)) @unlink($imageSmallPath);
                $imagePath          = Storage::path(config('admin.images.folderUpload').basename($infoPartner->seo->image));
                if(file_exists($imagePath)) @unlink($imagePath);
                /* delete bảng air_partner */
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
