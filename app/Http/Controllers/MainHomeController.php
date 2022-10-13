<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Seo;

class MainHomeController extends Controller {

    public function home(){
        $item   = Seo::select('*')
                    ->where('slug', 'tour-du-lich-phu-quoc')
                    ->first();
        return view('main.home.home', compact('item'));
    }
}
