<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipController extends Controller {

    public function loadTocContent(Request $request){
        $xhtml       = null;
        if(!empty($request->get('dataSend'))){
            $xhtml   = view('main.shipLocation.tocContent', ['data' => $request->get('dataSend')])->render();
        }
        echo $xhtml;
    }
}
