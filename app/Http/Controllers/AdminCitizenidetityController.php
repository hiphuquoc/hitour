<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CitizenIdentity;
use Illuminate\Support\Facades\Auth;

class AdminCitizenidetityController extends Controller {

    public static function loadFormCitizenidentity(Request $request){
        $idBooking  = $request->get('reference_id');
        $type       = $request->get('reference_type');
        $data       = CitizenIdentity::select('*')
                        ->where('reference_id', $idBooking)
                        ->where('reference_type', $type)
                        ->get();
        $result     = view('admin.booking.formCitizenidentity', compact('data', 'idBooking', 'type'))->render();
        echo $result;
    }

    public static function create(Request $request){
        if(!empty($request->get('citizenidentity'))){
            $idBooking  = $request->get('reference_id');
            $type       = $request->get('reference_type');
            /* xóa bỏ chi phí trước đó của booking */
            CitizenIdentity::select('*')
                ->where('reference_id', $idBooking)
                ->where('reference_type', $type)
                ->delete();
            /* insert lại */
            foreach($request->get('citizenidentity') as $citizenidentity){
                if(!empty($citizenidentity['name'])&&$citizenidentity['identity']&&$citizenidentity['year_of_birth']){
                    $insert = [
                        'name'              => $citizenidentity['name'],
                        'identity'          => $citizenidentity['identity'],
                        'year_of_birth'     => $citizenidentity['year_of_birth'],
                        'reference_id'      => $idBooking,
                        'reference_type'    => $type
                    ];
                    CitizenIdentity::insertItem($insert);
                }
            }
            /* Message */
            $toast              = [
                'title'     => 'Thành công!',
                'message'   => 'Đã cập nhật danh sách hành khách',
                'type'      => 'success'
            ];
        }else {
            /* Message */
            $toast              = [
                'title'     => 'Thất bại!',
                'message'   => 'Chưa cập nhật danh sách hành khách',
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
