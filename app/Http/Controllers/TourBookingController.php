<?php

namespace App\Http\Controllers;
use App\Models\Tour;
use App\Models\TourLocation;
use App\Models\TourPrice;
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
        /* insert customer_info */
        $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        $idCustomer                 = Customer::insertItem($insertCustomer);
        /* insert tour_booking */
        $insertTourBooking          = $this->BuildInsertUpdateModel->buildArrayTableTourBooking($idCustomer, $request->all());
        $noBooking                  = $insertTourBooking['no'];
        $idBooking                  = TourBooking::insertItem($insertTourBooking);
        /* insert tour_booking_quantity_and_price */
        $insertTourBookingQuantityAndPrice  = $this->BuildInsertUpdateModel->buildArrayTableTourQuantityAndPrice($idBooking, $request->get('tour_option_id'), $request->all());
        foreach($insertTourBookingQuantityAndPrice as $itemInsert){
            TourBookingQuantityAndPrice::insertItem($itemInsert);
        }
        return redirect()->route('main.tourBooking.confirm', ['no' => $noBooking]);
    }

    public static function confirm(Request $request){
        $noBooking  = $request->get('no') ?? null;
        $item       = TourBooking::select('*')
                        ->where('no', $noBooking)
                        ->with('tour', 'customer_contact', 'customer_list', 'quantiesAndPrices')
                        ->first();
        if(!empty($item)){
            return view('main.tourBooking.confirmBooking', compact('item'));
        }else {
            return redirect()->route('main.home');
        }
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

    public static function loadFormQuantityByOption(Request $request){
        $result         = null;
        if(!empty($request->get('tour_option_id'))){
            $prices     = TourPrice::select('*')
                            ->where('tour_option_id', $request->get('tour_option_id'))
                            ->get();
            $result     = view('main.tourBooking.formQuantity', compact('prices'))->render();
        }
        echo $result;
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

    public static function loadBookingSummary(Request $request){
        $result             = null;
        if(!empty($request->get('dataForm'))){
            $dataForm       = [];
            $quantity       = [];
            foreach($request->get('dataForm') as $value){
                $dataForm[$value['name']]   = $value['value'];
            }
            /* lấy thông tin tour gộp vào dataForm */
            $infoTour       = Tour::select('*')
                                ->where('id', $dataForm['tour_info_id'])
                                ->with('options.prices')
                                ->first();
            $dataForm['tour'] = $infoTour->toArray();
            /* tách name quantity và tour_price_id */
            $arrayQuantity  = [];
            foreach($dataForm as $key => $quantity){
                preg_match('#quantity\[(.*)\]#imsU', $key, $match);
                if(!empty($match[1])&&!empty($quantity)) $arrayQuantity[$match[1]] = $quantity;
            }
            /* gộp vào dataForm */
            $dataForm['quantity']   = $arrayQuantity;
            dd($dataForm);
            $result                 = view('main.tourBooking.summary', ['data' => $dataForm]);
        }
        echo $result;
    }
}
