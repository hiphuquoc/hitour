<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Ship;
use App\Models\ShipPort;
use App\Models\ShipBooking;
use App\Models\ShipBookingQuantityAndPrice;
use Illuminate\Http\Request;

use App\Services\BuildInsertUpdateModel;

class ShipBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function form(Request $request){
        $ports = ShipPort::select('*')
                    ->with('district', 'province')
                    ->get();
        return view('main.shipBooking.form', compact('ports'));
    }

    public function create(Request $request){
        /* insert customer_inf0 */
        $insertCustomer             = $this->BuildInsertUpdateModel->buildArrayTableCustomerInfo($request->all());
        $idCustomer                 = Customer::insertItem($insertCustomer);
        /* insert ship_booking */
        $insertShipBooking          = $this->BuildInsertUpdateModel->buildArrayTableShipBooking($idCustomer, $request->all());
        $idBooking                  = ShipBooking::insertItem($insertShipBooking);
        /* insert ship_booking_quantity_and_price */
        $arrayInsertShipQuantity    = $this->BuildInsertUpdateModel->buildArrayTableShipQuantityAndPrice($idBooking, $request->all());
        foreach($arrayInsertShipQuantity as $insertShipQuantity){
            ShipBookingQuantityAndPrice::insertItem($insertShipQuantity);
        }
        return redirect()->route('main.shipBooking.confirm', ['ship_booking_id' => $idBooking]);
    }

    public static function loadShipLocation(Request $request){
        $result             = null;
        if(!empty($request->get('ship_port_id'))){
            $idPortStart    = $request->get('ship_port_id');
            $tmp            = Ship::select('*')
                                ->where('ship_port_departure_id', $idPortStart)
                                ->orWhere('ship_port_location_id', $idPortStart)
                                ->with('portDeparture.district', 'portDeparture.province', 'portLocation.district', 'portLocation.province')
                                ->get();
            /* lấy cảng đói lập với cảng đưa vào */
            $data           = [];
            foreach($tmp as $ship){
                if($idPortStart==$ship->portDeparture->id) {
                    $data[] = $ship->portLocation;
                } else {
                    $data[] = $ship->portDeparture;
                }
            }
            $result         = view('main.shipBooking.selectboxLocation', compact('data'));
        }
        return $result;
    }

    public static function loadDeparture(Request $request){
        $result                 = null;
        if(!empty($request->get('date')&&!empty($request->get('ship_port_departure_id')&&!empty($request->get('ship_port_location_id'))))){
            $code               = $request->get('code');
            $date               = $request->get('date');
            if($code==1){
                $portShipDeparture  = ShipPort::find($request->get('ship_port_departure_id'));
                $portShipLocation   = ShipPort::find($request->get('ship_port_location_id'));
            }else {
                $portShipDeparture  = ShipPort::find($request->get('ship_port_location_id'));
                $portShipLocation   = ShipPort::find($request->get('ship_port_departure_id'));
            }
            /* date rage to array */
            $datePast           = strtotime($date) - (86400*2);
            $dateFuture         = strtotime($date) + (86400*2);
            $arrayDate          = \App\Helpers\Time::createDateRangeArray(date('Y-m-d', $datePast), date('Y-m-d', $dateFuture));
            $data               = [];
            foreach($arrayDate as $day){
                $data[$day]     = self::getShipPricesAndTimeByDate($day, $portShipDeparture->name, $portShipLocation->name);
            }
            $result             = view('main.shipBooking.formChooseShip', compact('data', 'date', 'code'))->render();
        }
        return json_encode($result);
    }

    public static function getShipPricesAndTimeByDate($date, $namePortDeparture, $namePortLocation){
        $collectionShip     = Ship::select('*')
                                ->whereHas('prices.times', function($query) use($namePortDeparture, $namePortLocation){
                                    $query->where('ship_from', $namePortDeparture)
                                            ->where('ship_to', $namePortLocation);
                                })
                                ->with('portDeparture', 'portLocation', 'departure', 'location', 'prices.times', 'prices.partner')
                                ->first();
        $result             = [];
        foreach($collectionShip->prices as $price){
            $mkDate         = strtotime($date);
            $mkDateStart    = strtotime($price->date_start);
            $mkDateEnd      = strtotime($price->date_end);
            if($mkDate>$mkDateStart&&$mkDate<$mkDateEnd){
                if($namePortDeparture==$collectionShip->portDeparture->name&&$namePortLocation==$collectionShip->portLocation->name){
                    $result['departure']    = $collectionShip->departure->display_name;
                    $result['location']     = $collectionShip->location->display_name;
                }else {
                    $result['departure']    = $collectionShip->location->display_name;
                    $result['location']     = $collectionShip->departure->display_name;
                }
                $result['ship_price_id']    = $price->id;
                $result['ship_info_id']     = $collectionShip->id;
                $result['partner']          = $price->partner->name;
                $result['date_start']       = $price->date_start;
                $result['date_end']         = $price->date_end;
                $result['price_adult']      = $price->price_adult;
                $result['price_child']      = $price->price_child;
                $result['price_old']        = $price->price_old;
                $result['price_vip']        = $price->price_vip;
                foreach($price->times as $time){
                    if($time->ship_from==$namePortDeparture&&$time->ship_to==$namePortLocation) $result['times'][] = $time->toArray();
                }
                /* break để chỉ lấy duy nhất một ship_price (trường hợp sau này bổ sung lấy nhiều kết quả và lọc theo thuật toán để ra kết quả chuẩn trong 1 ngày có nhiều kết quả) */
                break;
            }
        }
        return $result;
    }

    public static function loadBookingSummary(Request $request){
        $result             = null;
        if(!empty($request->get('dataForm'))){
            $dataForm       = [];
            foreach($request->get('dataForm') as $value){
                $dataForm[$value['name']]   = $value['value'];
            }
            $result         = view('main.shipBooking.summary', ['data' => $dataForm]);
        }
        echo $result;
    }

    public static function confirm(Request $request){
        $idShipBooking  = $request->get('ship_booking_id') ?? 0;
        $item           = ShipBooking::select('*')
                            ->where('id', $idShipBooking)
                            ->with('infoDeparture', 'customer')
                            ->first();
        if(!empty($item)){
            return view('main.shipBooking.confirmBooking', compact('item'));
        }else {
            return redirect()->route('main.home');
        }
    }
}
