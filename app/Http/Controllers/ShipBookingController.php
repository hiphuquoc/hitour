<?php

namespace App\Http\Controllers;

use App\Models\Ship;
use App\Models\ShipPort;
use App\Models\ShipDeparture;
use App\Models\ShipLocation;
use Illuminate\Http\Request;

class ShipBookingController extends Controller {

    public static function form(Request $request){
        $ports = ShipPort::select('*')
                    ->with('district', 'province')
                    ->get();
        return view('main.shipBooking.form', compact('ports'));
    }

    public static function handle(Request $request){

        dd($request->all());
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
        $xhtmlDp1               = null;
        $xhtmlDp2               = null;
        if(!empty($request->get('date')&&!empty($request->get('ship_port_departure_id')&&!empty($request->get('ship_port_location_id'))))){
            $date               = $request->get('date');
            $portShipDeparture  = ShipPort::find($request->get('ship_port_departure_id'));
            $portShipLocation   = ShipPort::find($request->get('ship_port_location_id'));
            
            /* date rage to array */
            $datePast           = strtotime($date) - (86400*2);
            $dateFuture         = strtotime($date) + (86400*2);
            $arrayDate          = \App\Helpers\Time::createDateRangeArray(date('Y-m-d', $datePast), date('Y-m-d', $dateFuture));
            foreach($arrayDate as $day){
                $data[$day]     = self::getShipPricesAndTimeByDate($day, $portShipDeparture->name, $portShipLocation->name);
            }
            $xhtmlDp1           = view('main.shipBooking.formChooseShip', compact('data', 'date'))->render();
        }
        $result['dp1']          = $xhtmlDp1;
        $result['dp2']          = $xhtmlDp2;
        return json_encode($result);
    }

    public static function getShipPricesAndTimeByDate($date, $namePortDeparture, $namePortLocation){
        $collectionShip     = Ship::select('*')
                                ->whereHas('prices.times', function($query) use($namePortDeparture, $namePortLocation){
                                    $query->where('ship_from', $namePortDeparture)
                                            ->where('ship_to', $namePortLocation);
                                })
                                ->with('prices.times', 'prices.partner')
                                ->first();
        $result             = [];
        foreach($collectionShip->prices as $price){
            $mkDate         = strtotime($date);
            $mkDateStart    = strtotime($price->date_start);
            $mkDateEnd      = strtotime($price->date_end);
            if($mkDate>$mkDateStart&&$mkDate<$mkDateEnd){
                $result['partner']      = $price->partner->name;
                $result['date_start']   = $price->date_start;
                $result['date_end']     = $price->date_end;
                $result['price_adult']  = $price->price_adult;
                $result['price_child']  = $price->price_child;
                $result['price_old']    = $price->price_old;
                $result['price_vip']    = $price->price_vip;
                foreach($price->times as $time){
                    if($time->ship_from==$namePortDeparture&&$time->ship_to==$namePortLocation) $result['times'][] = $time->toArray();
                }
                /* break để chỉ lấy duy nhất một ship_price (trường hợp sau này bổ sung lấy nhiều kết quả và lọc theo thuật toán để ra kết quả chuẩn trong 1 ngày có nhiều kết quả) */
                break;
            }
        }
        return $result;
    }
}
