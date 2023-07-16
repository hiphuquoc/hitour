<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\HotelContact;
use Illuminate\Support\Facades\DB;

class AdminHotelContactController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function loadContact(Request $request){
        $result                     = null;
        if(!empty($request->get('hotel_info_id'))&&$request->get('type')=='edit'){
            $data                   = HotelContact::select('*')
                                        ->where('hotel_info_id', $request->get('hotel_info_id'))
                                        ->get();
            foreach($data as $item) $result .= view('admin.hotel.contactRow', compact('item'))->render();
        }
        if(empty($result)) $result  = config('admin.message_data_empty');
        echo $result;
    }

    public function create(Request $request){
        $flag   = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('hotel_info_id'))){
            /* insert hotel_contact */
            $dataForm           = $request->get('dataForm');
            $idHotelContact     = HotelContact::insertItem([
                'hotel_info_id'     => $request->get('hotel_info_id'), 
                'name'              => $dataForm['name'],
                'address'           => $dataForm['address'],
                'phone'             => $dataForm['phone'], 
                'zalo'              => $dataForm['zalo'],
                'email'             => $dataForm['email'],
                'default'           => 0
            ]);
            /* Message */
            if(!empty($idHotelContact)) $flag = true;
        }
        echo $flag;
    }

    public function update(Request $request){
        $flag               = false;
        if(!empty($request->get('dataForm'))&&!empty($request->get('hotel_info_id'))){
            $dataForm           = $request->get('dataForm');
            HotelContact::updateItem($dataForm['hotel_contact_id'], [
                'name'              => $dataForm['name'],
                'address'           => $dataForm['address'],
                'phone'             => $dataForm['phone'], 
                'zalo'              => $dataForm['zalo'],
                'email'             => $dataForm['email'],
                'default'           => 0
            ]);
            /* Message */
            $flag = true;
        }
        echo $flag;
    }

    public static function delete(Request $request){
        $result     = false;
        if(!empty($request->get('id'))) $result = HotelContact::deleteItem($request->get('id'));
        echo $result;
    }

    public function loadFormContact(Request $request){
        $item               = [];
        if(!empty($request->get('hotel_contact_id'))) $item   = HotelContact::find($request->get('hotel_contact_id'));
        $result['header']   = !empty($item) ? 'Chỉnh sửa liên hệ' : 'Thêm liên hệ';
        $result['body']     = view('admin.hotel.formContact', compact('item'))->render();
        return json_encode($result);
    }
}
