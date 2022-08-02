<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;

class AdminPartnerController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(){
        $list               = Partner::getList();
        return view('admin.partner.list', compact('list'));
    }

    public function viewEdit(Request $request, $id){
        if(!empty($id)){
            $item           = Partner::find($id);
            $type           = 'edit';
            if(!empty($request->get('type'))) $type = $request->get('type');
            if(!empty($item)) return view('admin.partner.view', compact('item', 'type'));
        }
        return redirect()->route('admin.partner.list');
    }

    public function viewInsert(Request $request){
        $type               = 'create';
        return view('admin.partner.view', compact('type'));
    }

    public function create(Request $request){
        /* upload image */
        $avatarPath         = null;
        if($request->hasFile('image')) {
            $avatarPath     = Upload::uploadAvatar($request->file('image'));
        }
        /* insert partner_info */
        $insertPartnerInfo  = $this->BuildInsertUpdateModel->buildArrayTablePartnerInfo($request->all(), $avatarPath);
        $idPartner          = Partner::insertItem($insertPartnerInfo);
        /* Message */
        return redirect()->route('admin.partner.list');
    }

    public function update(Request $request){
        /* upload image */
        $avatarPath         = null;
        if($request->hasFile('image')) {
            $avatarPath     = Upload::uploadAvatar($request->file('image'));
        }
        /* insert partner_info */
        $updatePartnerInfo    = $this->BuildInsertUpdateModel->buildArrayTablePartnerInfo($request->all(), $avatarPath);
        // dd($insertTourInfo);
        Partner::updateItem($request->get('id'), $updatePartnerInfo);
        /* Message */
        return redirect()->route('admin.partner.list');
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id             = $request->get('id');
                /* lấy thông tin */
                $infoPartner    = Partner::find($id);
                /* delete ảnh */
                if(!empty($infoPartner->company_logo)&&file_exists(public_path($infoPartner->company_logo))) @unlink(public_path($infoPartner->company_logo));
                /* delete bảng tour_location */
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
