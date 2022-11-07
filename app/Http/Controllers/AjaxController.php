<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller {

    public function buildTocContentSidebar(Request $request){
        $xhtml       = null;
        if(!empty($request->get('dataSend'))){
            $xhtml   = view('main.template.tocContentSidebar', ['data' => $request->get('dataSend')])->render();
        }
        echo $xhtml;
    }

    public function buildTocContentMain(Request $request){
        $xhtml       = null;
        if(!empty($request->get('dataSend'))){
            $xhtml   = view('main.template.tocContentMain', ['data' => $request->get('dataSend')])->render();
        }
        echo $xhtml;
    }
}
