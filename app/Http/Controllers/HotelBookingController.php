<?php

namespace App\Http\Controllers;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\Request;

use App\Services\BuildInsertUpdateModel;

class HotelBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public static function form(Request $request){
        $dataForm           = $request->all();
        // dd($dataForm);
        $idHotelPrice       = $dataForm['hotel_price_id'] ?? 0;
        /* lấy prices được chọn */
        $room               = HotelRoom::select('*')
                                ->whereHas('prices', function ($query) use ($idHotelPrice) {
                                    $query->where('id', $idHotelPrice);
                                })
                                ->with(['prices' => function ($query) use ($idHotelPrice) {
                                    $query->where('id', $idHotelPrice);
                                }])
                                ->first();
        /* lấy thông tin hotel với đầy đủ price và room => cho tính năng thay đổi */
        $hotel              = Hotel::select('*')
                                ->whereHas('rooms.prices', function ($query) use ($idHotelPrice) {
                                    $query->where('id', $idHotelPrice);
                                })
                                ->with('rooms.prices')
                                ->first();
        if(!empty($room)&&!empty($hotel)) {
            return view('main.hotelBooking.form', compact('hotel', 'room', 'dataForm'));
        }else {
            return redirect()->route('main.home');
        }
    }

    public static function loadBookingSummary(Request $request){
        $result             = null;
        if(!empty($request->get('dataForm'))){
            $dataForm       = [];
            foreach($request->get('dataForm') as $value){
                $dataForm[$value['name']]   = $value['value'];
            }
            $idHotelPrice   = $dataForm['hotel_price_id'];
            $room           = HotelRoom::select('*')
                                ->whereHas('prices', function ($query) use ($idHotelPrice) {
                                    $query->where('id', $idHotelPrice);
                                })
                                ->with(['prices' => function ($query) use ($idHotelPrice) {
                                    $query->where('id', $idHotelPrice);
                                }])
                                ->first();
            $result         = view('main.hotelBooking.summary', [
                'data'  => $dataForm,
                'room'  => $room
            ]);
        }
        echo $result;
    }

    // public function create(Request $request){
    //     /* insert customer_inf0 */
    //     $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
    //     $idCustomer                 = Customer::insertItem($insertCustomer);
    //     /* insert ship_booking */
    //     $insertBooking              = $this->BuildInsertUpdateModel->buildArrayTableBookingInfo($idCustomer, 'combo_info', $request->all());
    //     $noBooking                  = $insertBooking['no'];
    //     $idBooking                  = Booking::insertItem($insertBooking);
    //     /* insert service_booking_quantity_and_price */
    //     $arrayInsertServiceQuantity = $this->BuildInsertUpdateModel->buildArrayTableComboQuantityAndPrice($idBooking, $request->all());
    //     foreach($arrayInsertServiceQuantity as $insertServiceQuantity){
    //         BookingQuantityAndPrice::insertItem($insertServiceQuantity);
    //     }
    //     /* thông báo email cho nhân viên */
    //     $infoBooking                = Booking::select('*')
    //                                     ->where('id', $idBooking)
    //                                     ->with('customer_contact', 'customer_list', 'status', 'service', 'tour', 'combo', 'quantityAndPrice', 'costMoreLess', 'vat')
    //                                     ->first();
    //     \App\Jobs\ConfirmBooking::dispatch($infoBooking, null, 'notice');
    //     return redirect()->route('main.hotelBooking.confirm', ['no' => $noBooking]);
    // }

    // public static function loadCombo(Request $request){
    //     $result                     = null;
    //     if(!empty($request->get('combo_location_id'))){
    //         $idComboLocation        = $request->get('combo_location_id');
    //         $idComboInfoActive      = $request->get('combo_info_id');
    //         $data                   = Combo::select('*')
    //                                     ->whereHas('locations.infoLocation', function($query) use($idComboLocation){
    //                                         $query->where('combo_location_id', $idComboLocation);
    //                                     })
    //                                     ->get();
    //         $result                 = view('main.hotelBooking.selectbox', compact('data', 'idComboInfoActive'));
    //     }
    //     return $result;
    // }

    // public static function loadOption(Request $request){
    //     $result                 = null;
    //     if(!empty($request->get('combo_info_id'))){
    //         $idCombo            = $request->get('combo_info_id');
    //         $infoCombo          = Combo::select('*')
    //                                 ->where('id', $idCombo)
    //                                 ->with('options.prices', 'options.hotel', 'options.hotelRoom')
    //                                 ->first();
    //         $data               = \App\Http\Controllers\TourBookingController::getTourOptionByDate($request->get('date'), $infoCombo->options);
    //         /* lấy location */
    //         $location           = [];
    //         foreach($infoCombo->locations as $l){
    //             $location[]     = $l->infoLocation->display_name;
    //         }
    //         $location           = implode(', ', $location);
    //         $result             = view('main.hotelBooking.formChooseOption', compact('data', 'location'));
    //         /* dùng cho edit trong admin */
    //         if(!empty($request->get('type'))&&$request->get('type')=='admin') {
    //             $result                             = [];
    //             $result['content']                  = view('admin.booking.optionCombo', ['options' => $data])->render();
    //             $result['combo_option_id_active']   = $data[0]['id'];
    //             return $result;
    //         }
    //     }
    //     echo $result;
    // }

    // public static function loadFormQuantityByOption(Request $request){
    //     $result         = null;
    //     if(!empty($request->get('combo_option_id'))){
    //         $prices     = ComboPrice::select('*')
    //                         ->where('combo_option_id', $request->get('combo_option_id'))
    //                         ->get();
    //         $result     = view('main.hotelBooking.formQuantity', compact('prices'))->render();
    //         /* dùng cho edit trong admin */
    //         if(!empty($request->get('type'))&&$request->get('type')=='admin') {
    //             $infoBooking    = Booking::select('*')
    //                                 ->where('id', $request->get('booking_info_id'))
    //                                 ->with('quantityAndPrice')
    //                                 ->first();
    //             $result         = view('admin.booking.formQuantity', ['prices' => $prices, 'quantity' => $infoBooking->quantityAndPrice])->render();
    //         }
    //     }
    //     echo $result;
    // }

    // public static function confirm(Request $request){
    //     $noBooking          = $request->get('no') ?? null;
    //     $item               = Booking::select('*')
    //                             ->where('no', $noBooking)
    //                             ->with('quantityAndPrice', 'service')
    //                             ->first();
    //     if(!empty($item)){
    //         return view('main.hotelBooking.confirmBooking', compact('item'));
    //     }else {
    //         return redirect()->route('main.home');
    //     }
    // }
}
