<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;

use App\Models\TourLocation;
use App\Models\Tour;
use App\Models\TourPrice;
use App\Models\TourOption;
use App\Models\Customer;
use App\Models\Booking;
use App\Models\TourBookingQuantityAndPrice;
use App\Models\VAT;

use App\Services\BuildInsertUpdateModel;
use App\Http\Requests\TourBookingRequest;
use App\Models\CitizenIdentity;
use App\Models\SystemFile;
use App\Models\BookingStatus;

class AdminBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params             = [];
        /* Search theo tên */
        if(!empty($request->get('search_customer'))) $params['search_customer'] = $request->get('search_customer');
        /* Search theo vùng miền */
        if(!empty($request->get('search_type'))) $params['search_type'] = $request->get('search_type');
        /* Search theo trang thái */
        if(!empty($request->get('search_status'))) $params['search_status'] = $request->get('search_status');
        /* lấy dữ liệu */
        $list               = Booking::getList($params);
        /* status */
        $status             = BookingStatus::all();
        return view('admin.booking.list', compact('list', 'params', 'status'));
    }

    // public function viewEdit(Request $request, $id){
    //     if(!empty($id)){
    //         $item           = Booking::select('*')
    //                             ->where('id', $id)
    //                             ->with('customer_contact', 'quantityAndPrice', 'customer_list', 'costMoreLess', 'vat')
    //                             ->first();
    //         $tourList       = Tour::all();
    //         $message        = $request->get('message') ?? null;
    //         $type           = 'edit';
    //         if(!empty($item)) return view('admin.booking.view', compact('item', 'tourList', 'type', 'message'));
    //     }
    //     return redirect()->route('admin.booking.list');
    // }

    // public function viewInsert(Request $request){
    //     $tourList           = Tour::all();
    //     $type               = 'create';
    //     return view('admin.booking.view', compact('type', 'tourList'));
    // }

    public function viewExport($id){
        $item               = Booking::select('*')
                                ->where('id', $id)
                                ->with('customer_contact', 'customer_list', 'quantityAndPrice', 'tour', 'service', 'costMoreLess', 'vat', 'status')
                                ->first();
        return view('admin.booking.viewExport', compact('item'));
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
    //     return redirect()->route('admin.booking.viewEdit', [
    //         'id'        => $idTourBooking,
    //         'message'   => $message
    //     ]);
    // }

    // public function update(TourBookingRequest $request){
    //     /* update customer_info */
    //     $idCustomer         = $request->get('customer_info_id');
    //     $updateCustomer     = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
    //     Customer::updateItem($request->get('customer_info_id'), $updateCustomer);
    //     /* update tour_booking */
    //     $idTourBooking      = $request->get('tour_booking_id');
    //     $updateTourBooking  = $this->BuildInsertUpdateModel->buildArrayTableTourBooking($idCustomer, $request->all());
    //     TourBooking::updateItem($idTourBooking, $updateTourBooking);
    //     /* update citizen_identity_info */
    //     if(!empty($request->get('customer_list'))){
    //         /* xóa dữ liệu cũ */
    //         CitizenIdentity::select('*')
    //                         ->where('tour_booking_id', $idTourBooking)
    //                         ->delete();
    //         /* insert lại */
    //         $updateCitizenIdentity  = $this->BuildInsertUpdateModel->buildArrayTableCitizenIdentityInfoTable($idTourBooking, 'tour_booking_id', $request->all());
    //         if(!empty($updateCitizenIdentity)){
    //             foreach($updateCitizenIdentity as $itemInsert) CitizenIdentity::insertItem($itemInsert);
    //         }
    //     }
    //     /* update tour_booking_quantity_and_price */
    //     if(!empty($request->get('tour_option_id'))&&!empty($request->get('quantity'))){
    //         /* xóa bỏ quantity cũ */
    //         TourBookingQuantityAndPrice::select('*')
    //             ->where('tour_booking_id', $idTourBooking)
    //             ->delete();
    //         /* insert quantity mới */
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
    //     /* delete && insert vat_info (nếu có) */
    //     if(!empty($request->get('vat_info_id'))) VAT::find($request->get('vat_info_id'))->delete();
    //     if(!empty($request->get('vat_name'))&&!empty($request->get('vat_code'))&&!empty($request->get('vat_address'))){
    //         $insertVat  = $this->BuildInsertUpdateModel->buildArrayTableVatInfo($idTourBooking, 'tour_booking_id', $request->all());
    //         VAT::insertItem($insertVat);
    //     }
    //     /* Message */
    //     $message        = [
    //         'type'      => 'success',
    //         'message'   => '<strong>Thành công!</strong> Đã cập nhật Booking'
    //     ];
    //     return redirect()->route('admin.booking.viewEdit', [
    //         'id'        => $idTourBooking,
    //         'message'   => $message
    //     ]);
    // }

    // public static function loadOptionTourList(Request $request){
    //     $optionChecked              = 0;
    //     $content                    = '<div>Vui lòng chọn chương trình Tour trước!</div>';
    //     if(!empty($request->get('tour_info_id'))){
    //         $options                = TourOption::select('*')
    //                                     ->where('tour_info_id', $request->get('tour_info_id'))
    //                                     ->with('prices')
    //                                     ->get();
    //         if($options->isNotEmpty()) {
    //             if(!empty($request->get('type'))&&$request->get('type')=='admin'){
    //                 /* checked */
    //                 $optionChecked  = $options[0]->id;
    //                 $content        = view('admin.booking.optionTourList', compact('options', 'optionChecked'))->render();
    //             }else {
    //                 /* lọc lại theo ngày khởi hành */
    //                 $options        = self::filterOptionByDate($request->get('date'), $options);
    //                 /* lấy content */
    //                 $content        = view('main.booking.optionTourList', compact('options'))->render();
    //             }
    //         }
    //     }
    //     $result['tour_option_id']   = $optionChecked;
    //     $result['content']          = $content;
    //     return json_encode($result);
    // }

    // public static function loadFormPriceQuantity(Request $request){
    //     $result         = null;
    //     if(!empty($request->get('tour_option_id'))){
    //         $prices     = TourPrice::select('*')
    //                         ->where('tour_option_id', $request->get('tour_option_id'))
    //                         ->get();
    //         $quantity   = [];
    //         if(!empty($request->get('tour_booking_id'))) $quantity  = TourBookingQuantityAndPrice::getListByTourBookingId($request->get('tour_booking_id'));
    //         $result     = view('admin.booking.formPriceQuantity', compact('prices', 'quantity'))->render();
    //     }
    //     echo $result;
    // }

    // public static function filterOptionByDate($date, $tourOptions){
    //     $result         = new \Illuminate\Database\Eloquent\Collection;
    //     if(!empty($date)&&!empty($tourOptions)){
    //         $dataCheck  = config('admin.tour_option_apply_day');
    //         foreach($tourOptions as $option){
    //             foreach($dataCheck as $checker){
    //                 if($option->apply_day==$checker['name']){
    //                     $checked    = in_array(date('w', strtotime($date)), $checker['apply_day']);
    //                     if($checked==true) $result[] = $option;
    //                     break;
    //                 }
    //             }
    //         }
    //     }
    //     return $result;
    // }
}
