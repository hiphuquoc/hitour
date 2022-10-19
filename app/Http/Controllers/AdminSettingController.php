<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
 


class AdminSettingController extends Controller {

    public function settingView(Request $request){
        if(!empty($request->get('name')&&!empty($request->get('default')))){
            Cookie::queue($request->get('name'), $request->get('default'), 86400);
        }
    }
    
}
