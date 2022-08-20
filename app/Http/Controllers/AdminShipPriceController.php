<?php

namespace App\Http\Controllers;

use App\Helpers\Time;

use Illuminate\Http\Request;
use App\Models\ShipPartner;
use App\Models\Ship;
use App\Models\ShipPrice;
use App\Models\ShipTime;

use App\Services\BuildInsertUpdateModel;

class AdminShipPriceController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function loadList(Request $request){
        $result             = 'Không có dữ liệu phù hợp!';
        if(!empty($request->get('ship_info_id'))){
            $result         = null;
            $infoShipPrice  = Ship::select('*')
                                ->where('id', $request->get('ship_info_id'))
                                ->with('prices.times', 'prices.partner')
                                ->first();
            if($infoShipPrice->prices->isNotEmpty()){
                $result     .= view('admin.ship.oneRowPriceAndTime', ['item' => $infoShipPrice->prices])->render();
            }
        }
        echo $result;
    }

    public function createPrice(Request $request){
        $flag               = false;
        if(!empty($request->get('dataForm'))){
            $dataForm       = $request->get('dataForm');
            /* insert ship_price */
            $insertPrice    = $this->BuildInsertUpdateModel->buildArrayTableShipPrice($dataForm);
            $idShipPrice    = ShipPrice::insertItem($insertPrice);
            /* insert ship_time */
            for($i=0;$i<count($dataForm['time_departure']);++$i){
                $insertTime = [];
                $insertTime['shiP_price_id']    = $idShipPrice;
                $insertTime['time_departure']   = date('H:i', strtotime($dataForm['time_departure'][$i]));
                $insertTime['time_arrive']      = date('H:i', strtotime($dataForm['time_arrive'][$i]));
                /* time_move */
                $insertTime['time_move']        = Time::calcTimeMove($dataForm['time_departure'][$i], $dataForm['time_arrive'][$i]);
                ShipTime::insertItem($insertTime);
            }
            $flag           = !empty($idShipPrice) ? true : false;
        }
        echo $flag;
    }

    public function updatePrice(Request $request){
        $flag               = false;
        if(!empty($request->get('dataForm'))){
            $dataForm       = $request->get('dataForm');
            $idShipPrice    = $dataForm['ship_price_id'];
            /* update ship_price */
            $updatePrice    = $this->BuildInsertUpdateModel->buildArrayTableShipPrice($dataForm);
            ShipPrice::updateItem($idShipPrice, $updatePrice);
            /* delete ship_time old */
            ShipTime::select('*')
                ->where('ship_price_id', $idShipPrice)
                ->delete();
            /* insert ship_time */
            for($i=0;$i<count($dataForm['time_departure']);++$i){
                $insertTime = [];
                $insertTime['ship_price_id']    = $idShipPrice;
                $insertTime['time_departure']   = date('H:i', strtotime($dataForm['time_departure'][$i]));
                $insertTime['time_arrive']      = date('H:i', strtotime($dataForm['time_arrive'][$i]));
                /* time_move */
                $insertTime['time_move']        = Time::calcTimeMove($dataForm['time_departure'][$i], $dataForm['time_arrive'][$i]);
                ShipTime::insertItem($insertTime);
            }
            $flag           = true;
        }
        echo $flag;
    }

    public function deletePrice(Request $request){
        $flag           = false;
        if(!empty($request->get('ship_price_id'))){
            $shipPrice  = ShipPrice::find($request->get('ship_price_id'));
            $shipPrice->times()->delete();
            $flag       = $shipPrice->delete();
        }
        return $flag;
    }

    public function loadFormModal(Request $request){
        if(!empty($request->get('ship_info_id'))){
            if(!empty($request->get('ship_price_id'))&&$request->get('type')=='update'){
                /* trường hợp update */
                $idShip     = $request->get('ship_price_id');
                $type       = $request->get('type');
                $header     = 'Chỉnh sửa giờ tàu và giá';
                $item       = ShipPrice::select('*')
                                ->where('id', $idShip)
                                ->with('times')
                                ->first();
                $partners   = ShipPartner::all();
                if(!empty($item)){
                    $body   = view('admin.ship.formShipTime', compact('item', 'type', 'partners'))->render();
                }else {
                    $body   = '<div style="margin-top:1rem;font-weight:600;">Có lỗi xảy ra, không tải được dữ liệu!</div>';
                }
                
            }else {
                /* trường hợp create và copy */
                $idShip     = $request->get('ship_price_id') ?? 0;
                $type       = $request->get('type');
                $item       = ShipPrice::select('*')
                                ->where('id', $idShip)
                                ->with('times')
                                ->first();
                $header     = 'Thêm giờ tàu và giá';
                $partners   = ShipPartner::all();
                $body       = view('admin.ship.formShipTime', compact('item', 'type', 'partners'))->render();
            }
        }else {
            $header         = 'Thêm giờ tàu và giá';
            $body           = '<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Chuyến tàu trước khi tạo giờ tàu và giá!</div>';
        }
        $result['header']   = $header;
        $result['body']     = $body;
        return json_encode($result);
    }
}
