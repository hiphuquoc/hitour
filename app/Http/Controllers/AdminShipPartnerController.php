<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\ShipPartner;
use App\Models\Seo;
use Illuminate\Support\Facades\DB;

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
                            ->with('seo')
                            ->first();
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.shipPartner.view', compact('item', 'type'));
    }

    public function create(Request $request){
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
            $idPartner          = ShipPartner::insertItem($insertPartnerInfo);
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
        $request->session()->put('message', $message);
        return redirect()->route('admin.shipPartner.view', ['id' => $idPartner]);
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
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
            ShipPartner::updateItem($request->get('id'), $updatePartnerInfo);
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
        return redirect()->route('admin.shipPartner.view', ['id' => $request->get('id')]);
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $id             = $request->get('id');
                /* lấy thông tin */
                $infoPartner    = ShipPartner::find($id);
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
