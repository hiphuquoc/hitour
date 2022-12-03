<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ShipPort;
use App\Models\Customer;
use App\Models\ShipBooking;
use App\Models\ShipBookingQuantityAndPrice;

use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use App\Models\CitizenIdentity;
use App\Models\ShipBookingStatus;

use Illuminate\Support\Facades\DB;

class AdminShipBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params             = [];
        /* Search theo tên */
        if(!empty($request->get('search_customer'))) $params['search_customer'] = $request->get('search_customer');
        /* Search theo điểm khởi hành */
        if(!empty($request->get('search_departure'))) $params['search_departure'] = $request->get('search_departure');
        /* Search theo điểm đến */
        if(!empty($request->get('search_location'))) $params['search_location'] = $request->get('search_location');
        /* Search theo trạng thái */
        if(!empty($request->get('search_status'))) $params['search_status'] = $request->get('search_status');
        /* paginate */
        $viewPerPage        = Cookie::get('viewShipBooking') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list               = ShipBooking::getList($params);
        /* điểm khởi hành Tàu */
        $shipPorts          = ShipPort::all();
        /* status */
        $status             = ShipBookingStatus::all();
        return view('admin.shipBooking.list', compact('list', 'params', 'shipPorts', 'status', 'viewPerPage'));
    }

    public function view(Request $request){
        if(!empty($request->get('id'))){
            $item           = ShipBooking::select('*')
                                ->where('id', $request->get('id'))
                                ->with('customer_contact', 'customer_list', 'infoDeparture')
                                ->first();
            $ports          = ShipPort::select('*')
                                ->with('district', 'province')
                                ->get();
            /* type */
            $type               = !empty($item) ? 'edit' : 'create';
            $type               = $request->get('type') ?? $type;
            return view('admin.shipBooking.view', compact('item', 'type', 'ports'));
        }else {
            return redirect()->route('admin.shipBooking.list');
        }
    }

    public function viewExport($id){
        $item               = ShipBooking::select('*')
                                ->where('id', $id)
                                ->with('customer_contact', 'infoDeparture', 'status', 'customer_list')
                                ->first();
        return view('admin.shipBooking.viewExport', compact('item'));
    }

    public function viewExportHtml($id){
        $item               = ShipBooking::select('*')
                                ->where('id', $id)
                                ->with('customer_contact', 'infoDeparture', 'status', 'customer_list')
                                ->first();
        return view('admin.shipBooking.viewExportHtml', compact('item'));
    }
    
    public static function getExpirationAt(Request $request){
        $result             = null;
        if(!empty($request->get('ship_booking_id'))){
            $info           = ShipBooking::select('expiration_at')
                                ->where('id', $request->get('ship_booking_id'))
                                ->first();
            $result         = date('Y-m-d H:i', strtotime($info->expiration_at));
        }
        echo $result;
    }

    public static function sendMailConfirm(Request $request){
        if(!empty($request->get('ship_booking_id'))&&!empty($request->get('expiration_at'))){
            /* cập nhật thời hạn booking */
            ShipBooking::updateItem($request->get('ship_booking_id'), ['expiration_at' => $request->get('expiration_at')]);
            /* tạo queue gửi email */
            \App\Jobs\ConfirmShipBooking::dispatch($request->get('ship_booking_id'));
            echo true;
        }
    }

    public static function loadViewExport(Request $request){
        $result             = null;
        if(!empty($request->get('ship_booking_id'))){
            $item           = ShipBooking::select('*')
                                ->where('id', $request->get('ship_booking_id'))
                                ->with('customer_contact', 'infoDeparture', 'status', 'customer_list')
                                ->first();
            $result         = view('admin.shipBooking.viewExportHtml', compact('item'));
        }
        echo $result;
    }

    public function update(Request $request){
        try {
            DB::beginTransaction();
            if(!empty($request->get('ship_booking_id'))){
                $idShipBooking              = $request->get('ship_booking_id');
                /* update customer_inf0 */
                $updateCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
                Customer::updateItem($request->get('customer_info_id'), $updateCustomer);
                /* update ship_booking => không có dữ liệu thay đổi */
                /* delete ship_booking_quantity_and_price */
                ShipBookingQuantityAndPrice::select('*')
                    ->where('ship_booking_id', $idShipBooking)
                    ->delete();
                /* insert lại ship_booking_quantity_and_price */
                $arrayInsertShipQuantity    = $this->BuildInsertUpdateModel->buildArrayTableShipQuantityAndPrice($idShipBooking, $request->all());
                foreach($arrayInsertShipQuantity as $insertShipQuantity){
                    ShipBookingQuantityAndPrice::insertItem($insertShipQuantity);
                }
                DB::commit();
                /* Message */
                $message        = [
                    'type'      => 'success',
                    'message'   => '<strong>Thành công!</strong> Dã cập nhật booking'
                ];
            }
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        $request->session()->put('message', $message);
        return redirect()->route('admin.shipBooking.view', ['id' => $idShipBooking]);
    }

    public static function delete(Request $request){
        $result                 = false;
        if(!empty($request->get('id'))){
            $infoShipBooking    = ShipBooking::find($request->get('id'));
            /* delete relation */
            $infoShipBooking->customer_contact()->delete();
            $infoShipBooking->customer_list()->delete();
            $infoShipBooking->infoDeparture()->delete();
            $infoShipBooking->delete();
            $result             = true;
        }
        return $result;
    }
}
