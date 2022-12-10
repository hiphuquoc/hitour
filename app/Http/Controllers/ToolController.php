<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ToolController extends Controller {

    public static function mixKeyword(Request $request){
        return view('main.tools.mixKeyword');
    }

}
