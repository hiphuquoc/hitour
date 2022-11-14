<?php

namespace App\Http\Controllers;
use App\Models\Seo;
use Illuminate\Http\Request;
use App\Models\CheckSeo as CheckSeoModel;
use Illuminate\Support\Facades\Redirect;

class ErrorController extends Controller {

    public static function error404(){
        // if($request->ajax()){
        //     return response()->json(['status' => '404']);
        // }
        return Redirect::to(route('main.home'), 301);
    }

}
