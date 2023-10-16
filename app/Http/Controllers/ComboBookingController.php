<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Combo;
use App\Models\ComboLocation;
use App\Models\ComboOption;
use App\Models\ComboPrice;
use App\Models\Booking;
use App\Models\BookingQuantityAndPrice;
use Illuminate\Http\Request;

use App\Services\BuildInsertUpdateModel;

class ComboBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public static function form(Request $request){
        $comboLocations   = ComboLocation::select('*')
                                ->whereHas('combos', function(){

                                })
                                ->with('region')
                                ->get();
        return view('main.comboBooking.form', compact('comboLocations'));
    }

    public function create(Request $request){
        /* insert customer_inf0 */
        $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        $idCustomer                 = Customer::insertItem($insertCustomer);
        /* insert ship_booking */
        $insertBooking              = $this->BuildInsertUpdateModel->buildArrayTableBookingInfo($idCustomer, 'combo_info', $request->all());
        $noBooking                  = $insertBooking['no'];
        $idBooking                  = Booking::insertItem($insertBooking);
        /* insert service_booking_quantity_and_price */
        $arrayInsertServiceQuantity = $this->BuildInsertUpdateModel->buildArrayTableComboQuantityAndPrice($idBooking, $request->all());
        foreach($arrayInsertServiceQuantity as $insertServiceQuantity){
            BookingQuantityAndPrice::insertItem($insertServiceQuantity);
        }
        /* thông báo email cho nhân viên */
        $infoBooking                = Booking::select('*')
                                        ->where('id', $idBooking)
                                        ->with('customer_contact', 'customer_list', 'status', 'service', 'tour', 'combo', 'quantityAndPrice', 'costMoreLess', 'vat')
                                        ->first();
        \App\Jobs\ConfirmBooking::dispatch($infoBooking, null, 'notice');
        return redirect()->route('main.comboBooking.confirm', ['no' => $noBooking]);
    }

    public static function loadCombo(Request $request){
        $result                     = null;
        if(!empty($request->get('combo_location_id'))){
            $idComboLocation        = $request->get('combo_location_id');
            $idComboInfoActive      = $request->get('combo_info_id');
            $data                   = Combo::select('*')
                                        ->whereHas('locations.infoLocation', function($query) use($idComboLocation){
                                            $query->where('combo_location_id', $idComboLocation);
                                        })
                                        ->get();
            $result                 = view('main.comboBooking.selectbox', compact('data', 'idComboInfoActive'));
        }
        return $result;
    }

    public static function loadOption(Request $request){
        $result                 = null;
        if(!empty($request->get('combo_info_id'))){
            $idCombo            = $request->get('combo_info_id');
            $infoCombo          = Combo::select('*')
                                    ->where('id', $idCombo)
                                    ->with('options.prices', 'options.hotel', 'options.hotelRoom')
                                    ->first();
            $data               = \App\Http\Controllers\TourBookingController::getTourOptionByDate($request->get('date'), $infoCombo->options);
            /* lấy location */
            $location           = [];
            foreach($infoCombo->locations as $l){
                $location[]     = $l->infoLocation->display_name;
            }
            $location           = implode(', ', $location);
            $result             = view('main.comboBooking.formChooseOption', compact('data', 'location'));
            /* dùng cho edit trong admin */
            if(!empty($request->get('type'))&&$request->get('type')=='admin') {
                $result                             = [];
                $result['content']                  = view('admin.booking.optionCombo', ['options' => $data])->render();
                $result['combo_option_id_active']   = $data[0]['id'];
                return $result;
            }
        }
        echo $result;
    }

    public static function loadFormQuantityByOption(Request $request){
        $result         = null;
        if(!empty($request->get('combo_option_id'))){
            $prices     = ComboPrice::select('*')
                            ->where('combo_option_id', $request->get('combo_option_id'))
                            ->get();
            $result     = view('main.comboBooking.formQuantity', compact('prices'))->render();
            /* dùng cho edit trong admin */
            if(!empty($request->get('type'))&&$request->get('type')=='admin') {
                $infoBooking    = Booking::select('*')
                                    ->where('id', $request->get('booking_info_id'))
                                    ->with('quantityAndPrice')
                                    ->first();
                $result         = view('admin.booking.formQuantity', ['prices' => $prices, 'quantity' => $infoBooking->quantityAndPrice])->render();
            }
        }
        echo $result;
    }

    public static function loadBookingSummary(Request $request){
        $result             = null;
        if(!empty($request->get('dataForm'))){
            $dataForm       = [];
            foreach($request->get('dataForm') as $value){
                $dataForm[$value['name']]   = $value['value'];
            }
            /* tách name quantity và tour_price_id */
            $arrayQuantity      = [];
            foreach($dataForm as $key => $quantity){
                preg_match('#quantity\[(.*)\]#imsU', $key, $match);
                if(!empty($match[1])&&!empty($quantity)) $arrayQuantity[$match[1]] = $quantity;
            }
            /* gộp vào dataForm */
            $dataForm['quantity']   = $arrayQuantity;
            /* lấy thông tin option */
            $infoOption     = ComboOption::select('*')
                                ->where('id', $dataForm['combo_option_id'])
                                ->with('prices')
                                ->first();
            $dataForm['options'] = $infoOption->toArray();
            $result         = view('main.comboBooking.summary', ['data' => $dataForm]);
        }
        echo $result;
    }

    public static function confirm(Request $request){
        $noBooking          = $request->get('no') ?? null;
        $item               = Booking::select('*')
                                ->where('no', $noBooking)
                                ->with('quantityAndPrice', 'service')
                                ->first();
        if(!empty($item)){
            return view('main.comboBooking.confirmBooking', compact('item'));
        }else {
            return redirect()->route('main.home');
        }
    }
}
