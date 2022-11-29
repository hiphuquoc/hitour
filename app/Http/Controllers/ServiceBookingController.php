<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Service;
use App\Models\ServiceLocation;
use App\Models\ServiceOption;
use App\Models\ServicePrice;
// use App\Models\ShipBookingQuantityAndPrice;
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
        // /* insert customer_inf0 */
        // $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        // $idCustomer                 = Customer::insertItem($insertCustomer);
        // /* insert ship_booking */
        // $insertShipBooking          = $this->BuildInsertUpdateModel->buildArrayTableShipBooking($idCustomer, $request->all());
        // $noBooking                  = $insertShipBooking['no'];
        // $idBooking                  = ShipBooking::insertItem($insertShipBooking);
        // /* insert ship_booking_quantity_and_price */
        // $arrayInsertShipQuantity    = $this->BuildInsertUpdateModel->buildArrayTableShipQuantityAndPrice($idBooking, $request->all());
        // foreach($arrayInsertShipQuantity as $insertShipQuantity){
        //     ShipBookingQuantityAndPrice::insertItem($insertShipQuantity);
        // }
        // return redirect()->route('main.serviceBooking.confirm', ['no' => $noBooking]);
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
            $data               = Service::select('*')
                                    ->where('id', $idService)
                                    ->with('options.prices')
                                    ->first();
            // dd($infoService->toArray());
            // $data               = self::getTourOptionByDate($request->get('date'), $infoTour->options->toArray());
            $result             = view('main.serviceBooking.formChooseOption', compact('data'));
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
        }
        echo $result;
    }

    // public static function getShipPricesAndTimeByDate($date, $namePortDeparture, $namePortLocation){
    //     $collectionShip                 = Ship::select('*')
    //                                         ->whereHas('prices.times', function($query) use($namePortDeparture, $namePortLocation){
    //                                             $query->where('ship_from', $namePortDeparture)
    //                                                     ->where('ship_to', $namePortLocation);
    //                                         })
    //                                         ->with('portDeparture', 'portLocation', 'departure', 'location', 'prices.times', 'prices.partner')
    //                                         ->first();
    //     $result                         = [];
    //     if(!empty($collectionShip->prices)){
    //         $i                          = 0;
    //         foreach($collectionShip->prices as $price){
    //             $mkDate                 = strtotime($date);
    //             $arrayTime              = new \Illuminate\Database\Eloquent\Collection;
    //             foreach($price->times as $time){
    //                 $mkDateStart        = strtotime($time->date_start);
    //                 $mkDateEnd          = strtotime($time->date_end);
    //                 if($mkDate>$mkDateStart&&$mkDate<$mkDateEnd&&$time->ship_from==$namePortDeparture&&$time->ship_to==$namePortLocation){
    //                     $arrayTime[]    = $time;
    //                 }
    //             }
    //             $result[$i]                 = $price->toArray();
    //             $result[$i]['departure']    = $collectionShip->departure->display_name;
    //             $result[$i]['location']     = $collectionShip->location->display_name;
    //             $result[$i]['times']        = $arrayTime->toArray();
    //             ++$i;
    //         }
    //     }
    //     return $result;
    // }

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
        // $noBooking          = $request->get('no') ?? null;
        // $item               = ShipBooking::select('*')
        //                         ->where('no', $noBooking)
        //                         ->with('infoDeparture', 'customer_contact')
        //                         ->first();
        // if(!empty($item)){
        //     return view('main.serviceBooking.confirmBooking', compact('item'));
        // }else {
        //     return redirect()->route('main.home');
        // }
    }
}
