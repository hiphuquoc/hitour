<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\TourPartner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminTourPartnerController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(){
        $list               = TourPartner::getList();
        return view('admin.tourPartner.list', compact('list'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = TourPartner::find($id);
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.tourPartner.view', compact('item', 'type'));
    }

    public function create(Request $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $avatarPath         = null;
            if($request->hasFile('image')) {
                $avatarPath     = Upload::uploadAvatar($request->file('image'));
            }
            /* insert partner_info */
            $insertPartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTableTourPartner($request->all(), $avatarPath);
            $idPartner          = TourPartner::insertItem($insertPartnerInfo);
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
        $request->session()->put('message', $message);
        return redirect()->route('admin.tourPartner.view', ['id' => $idPartner]);
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $avatarPath         = null;
            if($request->hasFile('image')) {
                $avatarPath     = Upload::uploadAvatar($request->file('image'));
            }
            /* insert partner_info */
            $updatePartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTableTourPartner($request->all(), $avatarPath);
            TourPartner::updateItem($request->get('id'), $updatePartnerInfo);
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
        $request->session()->put('message', $message);
        return redirect()->route('admin.tourPartner.view', ['id' => $request->get('id')]);
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id                 = $request->get('id');
                /* lấy thông tin */
                $infoPartner        = TourPartner::find($id);
                /* xóa ảnh đại diện trong thư mục */
                $imageSmallPath     = Storage::path(config('admin.images.folderUpload').basename($infoPartner->company_logo));
                if(file_exists($imageSmallPath)) @unlink($imageSmallPath);
                /* delete bảng tour_partner */
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
