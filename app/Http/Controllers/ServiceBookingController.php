<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceLocation;
use App\Models\ServiceOption;
use App\Models\ServicePrice;
use App\Models\Booking;
use App\Models\BookingQuantityAndPrice;
use Illuminate\Http\Request;

use App\Services\BuildInsertUpdateModel;

class ServiceBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function form(Request $request){
        $serviceLocations   = ServiceLocation::select('*')
                                ->whereHas('services', function(){

                                })
                                ->get();
        return view('main.serviceBooking.form', compact('serviceLocations'));
    }

    public function create(Request $request){
        /* insert customer_inf0 */
        $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        $idCustomer                 = Customer::insertItem($insertCustomer);
        /* insert ship_booking */
        $insertBooking              = $this->BuildInsertUpdateModel->buildArrayTableBookingInfo($idCustomer, 'service_info', $request->all());
        $noBooking                  = $insertBooking['no'];
        $idBooking                  = Booking::insertItem($insertBooking);
        /* insert service_booking_quantity_and_price */
        $arrayInsertServiceQuantity = $this->BuildInsertUpdateModel->buildArrayTableServiceQuantityAndPrice($idBooking, $request->all());
        foreach($arrayInsertServiceQuantity as $insertServiceQuantity){
            BookingQuantityAndPrice::insertItem($insertServiceQuantity);
        }
        return redirect()->route('main.serviceBooking.confirm', ['no' => $noBooking]);
    }

    public static function loadService(Request $request){
        $result                     = null;
        if(!empty($request->get('service_location_id'))){
            $idServiceLocation      = $request->get('service_location_id');
            $idServiceInfoActive    = $request->get('service_info_id');
            $data                   = Service::select('*')
                                        ->where('service_location_id', $idServiceLocation)
                                        ->get();
            $result                 = view('main.serviceBooking.selectbox', compact('data', 'idServiceInfoActive'));
        }
        return $result;
    }

    public static function loadOption(Request $request){
        $result                 = null;
        if(!empty($request->get('service_info_id'))){
            $idService          = $request->get('service_info_id');
            $infoService        = Service::select('*')
                                    ->where('id', $idService)
                                    ->with('options.prices')
                                    ->first();
            $data               = \App\Http\Controllers\TourBookingController::getTourOptionByDate($request->get('date'), $infoService->options->toArray());
            $result             = view('main.serviceBooking.formChooseOption', compact('data'));
            /* dùng cho edit trong admin */
            if(!empty($request->get('type'))&&$request->get('type')=='admin') {
                $result                             = [];
                $result['content']                  = view('admin.booking.optionService', ['options' => $data])->render();
                $result['service_option_id_active'] = $data[0]['id'];
                return $result;
            }
        }
        echo $result;
    }

    public static function loadFormQuantityByOption(Request $request){
        $result         = null;
        if(!empty($request->get('service_option_id'))){
            $prices     = ServicePrice::select('*')
                            ->where('service_option_id', $request->get('service_option_id'))
                            ->get();
            $result     = view('main.serviceBooking.formQuantity', compact('prices'))->render();
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
            $infoOption     = ServiceOption::select('*')
                                ->where('id', $dataForm['service_option_id'])
                                ->with('prices')
                                ->first();
            $dataForm['options'] = $infoOption->toArray();
            
            $result         = view('main.serviceBooking.summary', ['data' => $dataForm]);
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
            return view('main.serviceBooking.confirmBooking', compact('item'));
        }else {
            return redirect()->route('main.home');
        }
    }
}
