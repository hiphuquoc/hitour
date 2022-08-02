<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostMoreLess;
use App\Models\TourBooking;

use App\Services\BuildInsertUpdateModel;

class AdminCostController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function loadFormCostMoreLess(Request $request){
        if(!empty($request->get('tour_booking_id'))){
            $item               = CostMoreLess::find($request->get('cost_more_less_id'));
            $body               = view('admin.form.formCostMoreLess', compact('item'))->render();
            $result['header']   = 'Thêm /Bớt chi phí';
            $result['body']     = $body;
        }else {
            $result['header']   = 'Thêm /Bớt chi phí';
            $result['body']     = '<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Booking trước khi tạo Chi phí phát sinh!</div>';
        }
        return json_encode($result);
    }

    public function create(Request $request){
        $flag                   = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('tour_booking_id'))){
            $insertCost         = $this->BuildInsertUpdateModel->buildArrayTableCostMoreLess($request->get('tour_booking_id'), 'tour_booking', $request->get('dataForm'));
            $idInsert           = CostMoreLess::insertItem($insertCost);
            if(!empty($idInsert)) $flag = true;
        }
        echo $flag;
    }

    public function update(Request $request){
        $flag                   = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('tour_booking_id'))&&!empty($request->get('cost_more_less_id'))){
            $updateCost         = $this->BuildInsertUpdateModel->buildArrayTableCostMoreLess($request->get('tour_booking_id'), 'tour_booking', $request->get('dataForm'), 'update');
            $flag               = CostMoreLess::updateItem($request->get('cost_more_less_id'), $updateCost);
        }
        echo $flag;
    }

    public function delete(Request $request){
        $flag           = false;
        if(!empty($request->get('cost_more_less_id'))){
            $flag       = CostMoreLess::find($request->get('cost_more_less_id'))->delete();
        }
        echo $flag;
    }

    public function loadCostMoreLess(Request $request){
        $result         = null;
        if(!empty($request->get('tour_booking_id'))){
            $infoTour   = TourBooking::select('*')
                                    ->where('id', $request->get('tour_booking_id'))
                                    ->with('costMoreLess')
                                    ->first();
            $dataCost   = $infoTour->costMoreLess;
            $total      = 0;
            foreach($dataCost as $cost) {
                $result .= view('admin.cost.oneRow', compact('cost'))->render();
                $total  += $cost->quantity*$cost->unit_price;
            }
            if($dataCost->isNotEmpty()) $result .= '<div class="flexBox">
                                                        <div class="flexBox_item">Tổng: <span style="font-weight:bold;color:#E74C3C;">'.number_format($total).'<sup>đ</sup></span></div>
                                                    </div>';
        }
        if(empty($result)) $result = config('admin.message_data_empty');
        echo $result;
    }

}
