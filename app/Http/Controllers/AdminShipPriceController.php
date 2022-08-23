<?php

namespace App\Http\Controllers;

use App\Helpers\Time;

use Illuminate\Http\Request;
use App\Models\ShipPartner;
use App\Models\Ship;
use App\Models\ShipPrice;
use App\Models\ShipTime;

use App\Services\BuildInsertUpdateModel;

use Illuminate\Support\Facades\Input;

class AdminShipPriceController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function loadList(Request $request){
        $result             = 'Không có dữ liệu phù hợp!';
        if(!empty($request->get('ship_info_id'))){
            $infoShipPrice  = Ship::select('*')
                                ->where('id', $request->get('ship_info_id'))
                                ->with('prices.times', 'prices.partner')
                                ->first();
            if($infoShipPrice->prices->isNotEmpty()){
                $result     = view('admin.ship.oneRowPriceAndTime', ['item' => $infoShipPrice->prices])->render();
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
            $arrayInsertTime    = $this->BuildInsertUpdateModel->buildArrayTableShipTime($idShipPrice, $dataForm);
            for($i=0;$i<count($arrayInsertTime);++$i) ShipTime::insertItem($arrayInsertTime[$i]);
            $flag           = !empty($idShipPrice) ? true : false;
        }
        echo $flag;
    }

    public function updatePrice(Request $request){
        $flag                   = false;
        if(!empty($request->get('dataForm'))){
            $dataForm           = $request->get('dataForm');
            $idShipPrice        = $dataForm['ship_price_id'];
            /* update ship_price */
            $updatePrice        = $this->BuildInsertUpdateModel->buildArrayTableShipPrice($dataForm);
            ShipPrice::updateItem($idShipPrice, $updatePrice);
            /* delete ship_time old */
            ShipTime::select('*')
                ->where('ship_price_id', $idShipPrice)
                ->delete();
            /* insert ship_time */
            $arrayInsertTime    = $this->BuildInsertUpdateModel->buildArrayTableShipTime($idShipPrice, $dataForm);
            for($i=0;$i<count($arrayInsertTime);++$i) ShipTime::insertItem($arrayInsertTime[$i]);
            $flag               = true;
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
                $idShipInfo     = $request->get('ship_info_id');
                $idShipPrice    = $request->get('ship_price_id');
                $type           = $request->get('type');
                $header         = 'Chỉnh sửa giờ tàu và giá';
                $shipInfo       = Ship::select('*')
                                    ->where('id', $idShipInfo)
                                    ->with('location.district', 'location.province', 'departure.district', 'departure.province')
                                    ->first();
                $item           = ShipPrice::select('*')
                                    ->where('id', $idShipPrice)
                                    ->with('times')
                                    ->first();
                $partners       = ShipPartner::all();
                if(!empty($item)){
                    $body       = view('admin.ship.formShipTime', compact('shipInfo', 'item', 'type', 'partners'))->render();
                }else {
                    $body       = '<div style="margin-top:1rem;font-weight:600;">Có lỗi xảy ra, không tải được dữ liệu!</div>';
                }
                
            }else {
                /* trường hợp create và copy */
                $idShipInfo     = $request->get('ship_info_id') ?? 0;   
                $idShipPrice    = $request->get('ship_price_id') ?? 0;
                $type           = $request->get('type');
                $shipInfo       = Ship::select('*')
                                    ->where('id', $idShipInfo)
                                    ->with('location.district', 'location.province', 'departure.district', 'departure.province')
                                    ->first();
                $item           = ShipPrice::select('*')
                                    ->where('id', $idShipPrice)
                                    ->with('times')
                                    ->first();
                $header         = 'Thêm giờ tàu và giá';
                $partners       = ShipPartner::all();
                $body           = view('admin.ship.formShipTime', compact('shipInfo', 'item', 'type', 'partners'))->render();
            }
        }else {
            $header             = 'Thêm giờ tàu và giá';
            $body               = '<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Chuyến tàu trước khi tạo giờ tàu và giá!</div>';
        }
        $result['header']       = $header;
        $result['body']         = $body;
        return json_encode($result);
    }
}
