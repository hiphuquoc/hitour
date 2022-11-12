<?php

namespace App\Http\Controllers;
use App\Models\Tour;
use App\Models\TourLocation;
use App\Models\Customer;
use App\Models\TourBooking;
use App\Models\TourBookingQuantityAndPrice;
use Illuminate\Http\Request;

use App\Services\BuildInsertUpdateModel;

class TourBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function form(Request $request){
        $tourLocations  = TourLocation::select('*')
                            ->with('region')
                            ->get();
        return view('main.tourBooking.form', compact('tourLocations'));
    }

    public function create(Request $request){
        /* insert customer_inf0 */
        $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        // $idCustomer                 = Customer::insertItem($insertCustomer);
        $idCustomer                 = 1;
        /* insert tour_booking */
        // dd($request->all());
        $insertTourBooking          = $this->BuildInsertUpdateModel->buildArrayTableTourBooking($idCustomer, $request->all());
        // $idBooking                  = TourBooking::insertItem($insertTourBooking);
        $idBooking                  = 1;
        /* insert tour_booking_quantity_and_price */
        $arrayInsertTourQuantity    = $this->BuildInsertUpdateModel->buildArrayTableTourQuantityAndPrice($idBooking, $request->all());
        
        // foreach($arrayInsertTourQuantity as $insertTourQuantity){
        //     TourBookingQuantityAndPrice::insertItem($insertTourQuantity);
        // }
        return redirect()->route('main.tourBooking.confirm', ['tour_booking_id' => $idBooking]);
    }

    public static function loadTour(Request $request){
        $result                 = null;
        if(!empty($request->get('tour_location_id'))){
            $idTourLocation     = $request->get('tour_location_id');
            $data               = Tour::select('*')
                                    ->whereHas('locations.infoLocation', function($query) use($idTourLocation){
                                        $query->where('id', $idTourLocation);
                                    })
                                    ->where('status_show', 1)
                                    ->get();
            $result             = view('main.tourBooking.selectbox', compact('data'));
        }
        return $result;
    }

    public static function loadOptionTour(Request $request){
        $result                 = null;
        if(!empty($request->get('tour_info_id'))){
            $idTour             = $request->get('tour_info_id');
            $infoTour           = Tour::select('*')
                                    ->where('id', $idTour)
                                    ->with('options.prices')
                                    ->first();
            $data               = self::getTourOptionByDate($request->get('date'), $infoTour->options->toArray());
            $result             = view('main.tourBooking.formChooseOption', compact('data'));
        }
        echo $result;
    }

    public static function getTourOptionByDate($date, $options){
        $result                 = [];
        if(!empty($date)&&!empty($options)){
            $mkDate             = strtotime($date);
            foreach($options as $option){
                $mkStart        = strtotime($option['prices'][0]['date_start']);
                $mkEnd          = strtotime($option['prices'][0]['date_end']);
                if($mkDate>$mkStart&&$mkDate<$mkEnd) $result[] = $option;
            }
        }
        return $result;
    }
}
