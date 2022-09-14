<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;

use App\Models\ShipPort;
use App\Models\Customer;
use App\Models\ShipBooking;
use App\Models\ShipBookingQuantityAndPrice;

use App\Services\BuildInsertUpdateModel;

use App\Http\Requests\TourBookingRequest;
use App\Models\CitizenIdentity;
use App\Models\ShipBookingStatus;

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
        /* lấy dữ liệu */
        $list               = ShipBooking::getList($params);
        /* điểm khởi hành Tàu */
        $shipPorts          = ShipPort::all();
        /* status */
        $status             = ShipBookingStatus::all();
        // dd($params);
        return view('admin.shipBooking.list', compact('list', 'params', 'shipPorts', 'status'));
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

    // public function create(TourBookingRequest $request){
    //     /* insert customer_inf0 */
    //     $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
    //     $idCustomer                 = Customer::insertItem($insertCustomer);
    //     /* insert tour_booking */
    //     $noBooking                  = \App\Helpers\Charactor::randomString(10);
    //     $insertTourBooking          = $this->BuildInsertUpdateModel->buildArrayTableTourBooking($idCustomer, $request->all(), $noBooking);
    //     $idTourBooking              = TourBooking::insertItem($insertTourBooking);
    //     /* insert citizen_identity_info */
    //     if(!empty($request->get('customer_list'))){
    //         $updateCitizenIdentity  = $this->BuildInsertUpdateModel->buildArrayTableCitizenIdentityInfoTable($idTourBooking, 'tour_booking_id', $request->all());
    //         if(!empty($updateCitizenIdentity)){
    //             foreach($updateCitizenIdentity as $itemInsert) CitizenIdentity::insertItem($itemInsert);
    //         }
    //     }
    //     /* insert tour_booking_quantity_and_price */
    //     if(!empty($request->get('tour_option_id'))&&!empty($request->get('quantity'))){
    //         foreach($request->get('quantity') as $key => $value){
    //             /*
    //                 tour_booking_id
    //                 option_name
    //                 option_age
    //                 price
    //                 quantity
    //             */
    //             if(!empty($value)){
    //                 $dataInsert                     = [];
    //                 $dataInsert['tour_booking_id']  = $idTourBooking;
    //                 $infoOption                     = TourOption::find($request->get('tour_option_id'));
    //                 $dataInsert['option_name']      = $infoOption->option;
    //                 $infoPrice                      = TourPrice::find($key);
    //                 $dataInsert['option_age']       = $infoPrice->apply_age;
    //                 $dataInsert['price']            = $infoPrice->price;
    //                 $dataInsert['quantity']         = $value;
    //                 TourBookingQuantityAndPrice::insertItem($dataInsert);
    //             }
    //         }
    //     }
    //     /* insert vat_info (nếu có) */
    //     if(!empty($request->get('vat_name'))&&!empty($request->get('vat_code'))&&!empty($request->get('vat_address'))){
    //         $insertVat  = $this->BuildInsertUpdateModel->buildArrayTableVatInfo($idTourBooking, 'tour_booking_id', $request->all());
    //         VAT::insertItem($insertVat);
    //     }
    //     /* Message */
    //     $message        = [
    //         'type'      => 'success',
    //         'message'   => '<strong>Thành công!</strong> Đã tạo Booking mới'
    //     ];
    //     return redirect()->route('admin.shipBooking.viewEdit', [
    //         'id'        => $idTourBooking,
    //         'message'   => $message
    //     ]);
    // }

    public function update(Request $request){
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
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Đã cập nhật Booking'
            ];
            return redirect()->route('admin.shipBooking.view', [
                'id'        => $idShipBooking,
                'message'   => $message
            ]);
        }
    }
}
