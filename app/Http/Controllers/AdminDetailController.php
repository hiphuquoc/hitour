<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailMoreLess;
use Illuminate\Support\Facades\Auth;

class AdminDetailController extends Controller {

    public static function loadFormDetailMoreLess(Request $request){
        $idBooking  = $request->get('booking_info_id');
        $type       = $request->get('type');
        $data       = DetailMoreLess::select('*')
                        ->where('reference_id', $idBooking)
                        ->where('reference_type', $type)
                        ->get();
        $result     = view('admin.booking.formDetail', compact('data', 'idBooking', 'type'))->render();
        echo $result;
    }

    public function create(Request $request){
        if(!empty($request->get('detail'))){
            $idBooking  = $request->get('booking_info_id');
            $type       = $request->get('type');
            /* xóa bỏ chi phí trước đó của booking */
            DetailMoreLess::select('*')
                ->where('reference_id', $idBooking)
                ->where('reference_type', $type)
                ->delete();
            /* insert lại */
            foreach($request->get('detail') as $detail){
                if(!empty($detail['name'])&&$detail['value']){
                    $insert = [
                        'name'              => $detail['name'],
                        'value'             => $detail['value'],
                        'reference_id'      => $idBooking,
                        'reference_type'    => $type,
                        'created_by'        => Auth::id() ?? 0
                    ];
                    DetailMoreLess::insertItem($insert);
                }
            }
            /* Message */
            $toast              = [
                'title'     => 'Thành công!',
                'message'   => 'Đã cập nhật chi tiết xác nhận',
                'type'      => 'success'
            ];
        }else {
            /* Message */
            $toast              = [
                'title'     => 'Thất bại!',
                'message'   => 'Chưa cập nhật chi tiết xác nhận',
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
