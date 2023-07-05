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
        if(!empty($request->get('partner_id'))&&$request->get('type')=='edit'){
            $data                   = HotelContact::getListByPartnerId($request->get('partner_id'));
            foreach($data as $item) $result .= view('admin.hotelPartner.contactRow', compact('item'))->render();
        }
        if(empty($result)) $result  = config('admin.message_data_empty');
        echo $result;
    }

    public function create(Request $request){
        if(!empty($request->get('dataForm'))){
            /* insert partner_contact */
            $insertPartnerContact   = $this->BuildInsertUpdateModel->buildArrayTableHotelContact($request->get('dataForm'));
            $idPartner              = HotelContact::insertItem($insertPartnerContact);
            /* Message */
            $flag                   = !empty($idPartner) ? true : false;
            echo $flag;
        }
    }

    public function update(Request $request){
        $flag               = false;
        if(!empty($request->get('dataForm'))){
            $updatePartnerContact   = $this->BuildInsertUpdateModel->buildArrayTableHotelContact($request->get('dataForm'));
            $flag           = HotelContact::updateItem($request->get('dataForm')['hotel_contact_id'], $updatePartnerContact);
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
        if(!empty($request->get('id'))) $item   = HotelContact::find($request->get('id'));
        $result['header']   = !empty($item) ? 'Chỉnh sửa liên hệ' : 'Thêm liên hệ';
        $result['body']     = view('admin.hotelPartner.formModal', compact('item'))->render();
        return json_encode($result);
    }
}
