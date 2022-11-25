<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOption;
use App\Models\ServicePrice;

use App\Services\BuildInsertUpdateModel;

class AdminServicePriceController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function loadPrice(Request $request){
        $result                 = 'Không có dữ liệu phù hợp!';
        if(!empty($request->get('service_info_id'))){
            $infoServiceOption   = ServiceOption::select('*')
                                    ->where('service_info_id', $request->get('service_info_id'))
                                    ->with('prices')
                                    ->get();
            $result             = view('admin.service.oneRowPrice', ['item' => $infoServiceOption])->render();
        }
        echo $result;
    }

    public function createPrice(Request $request){
        $flag                       = false;
        if(!empty($request->get('dataForm'))){
            $dataForm               = $request->get('dataForm');
            /* insert service_option */
            $idServiceOption        = ServiceOption::insertItem([
                'service_info_id'   => $dataForm['service_info_id']
            ]);
            /* insert service_price */
            $arrayInsert            = $this->BuildInsertUpdateModel->buildArrayTableServicePrice($idServiceOption, $dataForm);
            foreach($arrayInsert as $insert) {
                $idServicePrice     = ServicePrice::insertItem($insert);
                $flag               = !empty($idServicePrice) ? true : false;
            }
        }
        echo $flag;
    }

    public function updatePrice(Request $request){
        $flag                   = false;
        if(!empty($request->get('dataForm'))){
            $dataForm           = $request->get('dataForm');
            $idServiceOption    = $dataForm['service_option_id'];
            /* update service_option */
            /* bảng này không có gì để update */
            /* delete ship_time old */
            ServicePrice::select('*')
                ->where('service_option_id', $idServiceOption)
                ->delete();
            /* insert ship_time */
            /* insert service_price */
            $arrayInsert            = $this->BuildInsertUpdateModel->buildArrayTableServicePrice($idServiceOption, $dataForm);
            foreach($arrayInsert as $insert) {
                $idServicePrice     = ServicePrice::insertItem($insert);
                $flag               = !empty($idServicePrice) ? true : false;
            }
        }
        echo $flag;
    }

    public function deletePrice(Request $request){
        $flag               = false;
        if(!empty($request->get('service_option_id'))){
            $serviceOption  = ServiceOption::find($request->get('service_option_id'));
            $serviceOption->prices()->delete();
            $flag           = $serviceOption->delete();
        }
        return $flag;
    }

    public function loadFormPrice(Request $request){
        if(!empty($request->get('service_info_id'))){
            if(!empty($request->get('service_option_id'))&&$request->get('type')=='update'){
                /* trường hợp update */
                $idServiceOption    = $request->get('service_option_id');
                $type               = $request->get('type');
                $header             = 'Chỉnh sửa giá';
                $item               = ServiceOption::select('*')
                                        ->where('id', $idServiceOption)
                                        ->with('prices')
                                        ->first();
                if(!empty($item)){
                    $body           = view('admin.service.formServicePrice', compact('item', 'type'))->render();
                }else {
                    $body           = '<div style="margin-top:1rem;font-weight:600;">Có lỗi xảy ra, không tải được dữ liệu!</div>';
                }
            }else {
                /* trường hợp create và copy */
                $type           = $request->get('type');
                $item           = null;
                if($type=='copy'&&!empty($request->get('service_option_id'))) {
                    $item       = ServiceOption::select('*')
                                    ->where('id', $request->get('service_option_id'))
                                    ->with('prices')
                                    ->first();
                }
                $header         = 'Thêm giá';
                $body           = view('admin.service.formServicePrice', compact('type', 'item'))->render();
            }
        }else {
            $header             = 'Thêm giá';
            $body               = '<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Dịch vụ trước khi tạo giá!</div>';
        }
        $result['header']       = $header;
        $result['body']         = $body;
        return json_encode($result);
    }

    // public static function mergeArrayShipPrice($times){
    //     /* gom theo date_start và date_end */
    //     $shipTime               = [];
    //     $i                      = 0;
    //     foreach($times as $time){
    //         if(key_exists($time->name, $shipTime)){
    //             /* xử lý date đưa vào mảng */
    //             $flagDateExists                                             = false;
    //             foreach($shipTime[$time->name]['date'] as $t){
    //                 if($t['date_start']==$time->date_start&&$t['date_end']==$time->date_end){
    //                     $flagDateExists                                     = true;
    //                     break;
    //                 }
    //             }
    //             if($flagDateExists==false){
    //                 $key                                                    = count($shipTime[$time->name]['date']);
    //                 $shipTime[$time->name]['date'][$key]['date_start']      = $time->date_start;
    //                 $shipTime[$time->name]['date'][$key]['date_end']        = $time->date_end;
    //             }
    //             /* xử lý time đưa vào mảng */
    //             $flagTimeExists                                                 = false;
    //             foreach($shipTime[$time->name]['time'] as $t){
    //                 if($t['time_departure']==$time->time_departure&&$t['time_arrive']==$time->time_arrive){
    //                     $flagTimeExists                                         = true;
    //                     break;
    //                 }
    //             }
    //             if($flagTimeExists==false){
    //                 $key                                                    = count($shipTime[$time->name]['time']);
    //                 $shipTime[$time->name]['time'][$key]['time_departure']  = $time->time_departure;
    //                 $shipTime[$time->name]['time'][$key]['time_arrive']     = $time->time_arrive;
    //                 $shipTime[$time->name]['time'][$key]['time_move']       = $time->time_move;
    //             }
    //         }else {
    //             $key                                                = count($shipTime);
    //             $shipTime[$time->name]['id']                        = $time->id;
    //             $shipTime[$time->name]['ship_price_id']             = $time->ship_price_id;
    //             $shipTime[$time->name]['name']                      = $time->name;
    //             $shipTime[$time->name]['ship_from']                 = $time->ship_from;
    //             $shipTime[$time->name]['ship_form_sort']            = $time->ship_form_sort;
    //             $shipTime[$time->name]['ship_to']                   = $time->ship_to;
    //             $shipTime[$time->name]['ship_to_sort']              = $time->ship_to_sort;
    //             $shipTime[$time->name]['time'][0]['time_departure'] = $time->time_departure;
    //             $shipTime[$time->name]['time'][0]['time_arrive']    = $time->time_arrive;
    //             $shipTime[$time->name]['time'][0]['time_move']      = $time->time_move;
    //             $shipTime[$time->name]['date'][0]['date_start']     = $time->date_start;
    //             $shipTime[$time->name]['date'][0]['date_end']       = $time->date_end;
    //         }
    //         ++$i;
    //     }
    //     /* reset key của array */
    //     $result    = [];
    //     foreach($shipTime as $time) $result[] = $time;
    //     return $result;
    // }
}
