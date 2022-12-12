<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostMoreLess;
use Illuminate\Support\Facades\Auth;

class AdminCostController extends Controller {

    public static function loadFormCostMoreLess(Request $request){
        $idBooking  = $request->get('reference_id');
        $type       = $request->get('reference_type');
        $data       = CostMoreLess::select('*')
                        ->where('reference_id', $idBooking)
                        ->where('reference_type', $type)
                        ->get();
        $result     = view('admin.booking.formCost', compact('data', 'idBooking', 'type'))->render();
        echo $result;
    }

    public static function create(Request $request){
        if(!empty($request->get('cost'))){
            $idBooking  = $request->get('reference_id');
            $type       = $request->get('reference_type');
            /* xóa bỏ chi phí trước đó của booking */
            CostMoreLess::select('*')
                ->where('reference_id', $idBooking)
                ->where('reference_type', $type)
                ->delete();
            /* insert lại */
            foreach($request->get('cost') as $cost){
                if(!empty($cost['name'])&&$cost['value']){
                    $insert = [
                        'name'              => $cost['name'],
                        'value'             => $cost['value'],
                        'reference_id'      => $idBooking,
                        'reference_type'    => $type,
                        'created_by'        => Auth::id() ?? 0
                    ];
                    CostMoreLess::insertItem($insert);
                }
            }
            /* Message */
            $toast              = [
                'title'     => 'Thành công!',
                'message'   => 'Đã cập nhật chi phí',
                'type'      => 'success'
            ];
        }else {
            /* Message */
            $toast              = [
                'title'     => 'Thất bại!',
                'message'   => 'Chưa cập nhật chi phí',
                'type'      => 'error'
            ];
        }
        $request->session()->put('toast', $toast);
        if($type=='booking_info'){
            return redirect()->route('admin.booking.viewExport', ['id' => $idBooking]);
        }else if($type=='ship_booking'){
            return redirect()->route('admin.shipBooking.viewExport', ['id' => $idBooking]);
        }
    }
}
