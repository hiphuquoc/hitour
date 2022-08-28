<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BuildInsertUpdateModel;
use App\Models\ShipPort;
use App\Models\Province;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class AdminShipPortController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(){
        $list               = ShipPort::select('*')
                                ->with('region', 'province', 'district')
                                ->orderBy('created_at', 'DESC')
                                ->get();
        return view('admin.shipPort.list', compact('list'));
    }

    public function view(Request $request){
        $id                 = $request->get('id') ?? 0;
        $item               = ShipPort::select('*')
                                ->where('id', $id)
                                ->with('region', 'province', 'district')
                                ->first();
        $provinces          = Province::getItemByIdRegion($item->region_id ?? 0);
        $districts          = District::getItemByIdProvince($item->province_id ?? 0);
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.shipPort.view', compact('item', 'type', 'provinces', 'districts'));
    }

    public function create(Request $request){
        try {
            DB::beginTransaction();
            /* insert ship_port */
            $insertShipPort     = $this->BuildInsertUpdateModel->buildArrayTableShipPort($request->all());
            $idShipPort         = ShipPort::insertItem($insertShipPort);
            DB::commit();
            /* Message */
            $message            = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã tạo Bến cảng mới'
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
        return redirect()->route('admin.shipPort.view', ['id' => $idShipPort]);
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            /* update ship_port */
            $updateShipPort     = $this->BuildInsertUpdateModel->buildArrayTableShipPort($request->all());
            ShipPort::updateItem($request->get('ship_port_id'), $updateShipPort);
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
        return redirect()->route('admin.shipPort.view', ['id' => $request->get('ship_port_id')]);
    }

    public static function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                /* lấy thông tin */
                ShipPort::select('*')
                    ->where('id', $request->get('id'))
                    ->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }

    public static function loadSelectBoxShipPort(Request $request){
        if(!empty($request->get('id'))){
            if($request->get('type')=='departure'){
                $data   = ShipPort::getShipPortByShipDepartureId($request->get('id'));
            }else {
                $data   = ShipPort::getShipPortByShipLocationId($request->get('id'));
            }
            $result     = view('admin.ajax.selectBox', compact('data'));
            echo $result;
        }
    }
}
