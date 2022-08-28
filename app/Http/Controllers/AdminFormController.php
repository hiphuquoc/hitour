<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

// use App\Models\TourLocation;
use App\Models\Province;
use App\Models\District;

class AdminFormController extends Controller {
    public function loadProvinceByRegion(Request $request){
        $data       = [];
        if(!empty($request->get('region_id'))){
            $data   = Province::getItemByIdRegion($request->get('region_id'));
        }
        $xhtml  = view('admin.ajax.selectBox', compact('data'));
        echo $xhtml;
    }

    public function loadDistrictByProvince(Request $request){
        $data       = [];
        if(!empty($request->get('province_id'))){
            $data   = District::getItemByIdProvince($request->get('province_id'));
        }
        $xhtml  = view('admin.ajax.selectBox', compact('data'));
        echo $xhtml;
    }
}
