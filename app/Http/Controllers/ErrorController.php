<?php

namespace App\Http\Controllers;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Models\Seo;

class ErrorController extends Controller {

    public static function error404(){
        return response()->json([
            'status' => '404'
        ]);
    }

}
