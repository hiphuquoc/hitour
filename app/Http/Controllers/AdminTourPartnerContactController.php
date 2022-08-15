<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Services\BuildInsertUpdateModel;
use App\Models\TourPartnerContact;
use Illuminate\Support\Facades\DB;

class AdminTourPartnerContactController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function loadContact(Request $request){
        $result                     = null;
        if(!empty($request->get('partner_id'))&&$request->get('type')=='edit'){
            $data                   = TourPartnerContact::getListByPartnerId($request->get('partner_id'));
            foreach($data as $item) $result .= view('admin.tourPartner.contactRow', compact('item'))->render();
        }
        if(empty($result)) $result  = config('admin.message_data_empty');
        echo $result;
    }

    public function create(Request $request){
        if(!empty($request->get('dataForm'))){
            /* insert partner_contact */
            $insertPartnerContact   = $this->BuildInsertUpdateModel->buildArrayTableTourPartnerContact($request->get('dataForm'));
            $idPartner              = TourPartnerContact::insertItem($insertPartnerContact);
            /* Message */
            $flag                   = !empty($idPartner) ? true : false;
            echo $flag;
        }
    }

    public function update(Request $request){
        $flag               = false;
        if(!empty($request->get('dataForm'))){
            $updatePartnerContact   = $this->BuildInsertUpdateModel->buildArrayTableTourPartnerContact($request->get('dataForm'));
            $flag           = TourPartnerContact::updateItem($request->get('dataForm')['partner_contact_id'], $updatePartnerContact);
        }
        echo $flag;
    }

    public static function delete(Request $request){
        $result     = false;
        if(!empty($request->get('id'))) $result = TourPartnerContact::deleteItem($request->get('id'));
        echo $result;
    }

    public function loadFormContact(Request $request){
        $item               = [];
        if(!empty($request->get('id'))) $item   = TourPartnerContact::find($request->get('id'));
        $result['header']   = !empty($item) ? 'Chỉnh sửa liên hệ' : 'Thêm liên hệ';
        $result['body']     = view('admin.tourPartner.formModal', compact('item'))->render();
        return json_encode($result);
    }
}
