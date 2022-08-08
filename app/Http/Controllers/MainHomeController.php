<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainHomeController extends Controller {

    public function home(){
        return view('main.home.home');
    }
}
