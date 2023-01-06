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
        $noBooking                  = $insertShipBooking['no'];
        $idBooking                  = ShipBooking::insertItem($insertShipBooking);
        /* insert ship_booking_quantity_and_price */
        $arrayInsertShipQuantity    = $this->BuildInsertUpdateModel->buildArrayTableShipQuantityAndPrice($idBooking, $request->all());
        foreach($arrayInsertShipQuantity as $insertShipQuantity){
            ShipBookingQuantityAndPrice::insertItem($insertShipQuantity);
        }
        /* gửi email thông báo cho nhân viên */
        $infoShipBooking            = ShipBooking::select('*')
                                        ->where('id', $idBooking)
                                        ->with('customer_contact', 'infoDeparture', 'status', 'customer_list')
                                        ->first();
        \App\Jobs\ConfirmShipBooking::dispatch($infoShipBooking, null, 'notice');
        /* chuyển hướng sang trang chi tiết booking */
        return redirect()->route('main.shipBooking.confirm', ['no' => $noBooking]);
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
            /* lấy cảng đối lập với cảng đưa vào */
            $data           = [];
            foreach($tmp as $ship){
                if($idPortStart==$ship->portDeparture->id) {
                    $data[] = $ship->portLocation;
                } else {
                    $data[] = $ship->portDeparture;
                }
            }
            /* id cảng active */
            $namePortActive = $request->get('name_port_active') ?? null;
            $result         = view('main.shipBooking.selectboxLocation', compact('data', 'namePortActive'));
        }
        return $result;
    }

    public static function loadDeparture(Request $request){
        $result                     = null;
        if(!empty($request->get('date')&&!empty($request->get('ship_port_departure_id')&&!empty($request->get('ship_port_location_id'))))){
            $code                   = $request->get('code');
            $date                   = $request->get('date');
            $portShipDeparture      = ShipPort::find($request->get('ship_port_departure_id'));
            $portShipLocation       = ShipPort::find($request->get('ship_port_location_id'));
            $data                   = self::getShipPricesAndTimeByDate($date, $portShipDeparture->name, $portShipLocation->name);
            if(!empty($request->get('theme'))&&$request->get('theme')=='admin'){
                /* thông tin booking (nếu có) => dùng đề active chuyến được chọn */
                $booking                = [];
                if(!empty($request->get('ship_booking_id'))){
                    $booking            = ShipBooking::select('*')
                                            ->where('id', $request->get('ship_booking_id'))
                                            ->with('infoDeparture')
                                            ->first();
                }
                $result             = view('admin.shipBooking.formChooseShip', compact('data', 'portShipDeparture', 'portShipLocation', 'date', 'code', 'booking'))->render();
            }else {
                $result             = view('main.shipBooking.formChooseShip', compact('data', 'portShipDeparture', 'portShipLocation', 'date', 'code'))->render();
            }
        }
        
        return json_encode($result);
    }

    public static function getShipPricesAndTimeByDate($date, $namePortDeparture, $namePortLocation){
        $collectionShip                 = Ship::select('*')
                                            ->whereHas('prices.times', function($query) use($namePortDeparture, $namePortLocation){
                                                $query->where('ship_from', $namePortDeparture)
                                                        ->where('ship_to', $namePortLocation);
                                            })
                                            ->with('portDeparture', 'portLocation', 'departure', 'location', 'prices.times', 'prices.partner')
                                            ->first();
        $result                         = [];
        if(!empty($collectionShip->prices)){
            $i                          = 0;
            foreach($collectionShip->prices as $price){
                $mkDate                 = strtotime($date.' 00:00:59');
                $arrayTime              = new \Illuminate\Database\Eloquent\Collection;
                foreach($price->times as $time){
                    $mkDateStart        = strtotime($time->date_start.' 00:00:00');
                    $mkDateEnd          = strtotime($time->date_end.' 23:59:59');
                    if($mkDate>$mkDateStart&&$mkDate<$mkDateEnd&&$time->ship_from==$namePortDeparture&&$time->ship_to==$namePortLocation){
                        $arrayTime[]    = $time;
                    }
                }
                $result[$i]                 = $price->toArray();
                $result[$i]['departure']    = $collectionShip->departure->display_name;
                $result[$i]['location']     = $collectionShip->location->display_name;
                $result[$i]['times']        = $arrayTime->toArray();
                ++$i;
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
        $noBooking          = $request->get('no') ?? null;
        $item               = ShipBooking::select('*')
                                ->where('no', $noBooking)
                                ->with('infoDeparture', 'customer_contact')
                                ->first();
        if(!empty($item)){
            return view('main.shipBooking.confirmBooking', compact('item'));
        }else {
            return redirect()->route('main.home');
        }
    }
}
