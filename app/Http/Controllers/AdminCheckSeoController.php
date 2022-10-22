<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Seo;

class AdminCheckSeoController extends Controller {

    public function listCheckSeo(Request $request){
        $viewPerPage    = Cookie::get('viewCheckSeo') ?? 50;
        $list           = Seo::select('*')
                            ->whereHas('checkSeos', function($query){
                                
                            })
                            ->with('checkSeos')
                            ->paginate($viewPerPage);
        return view('admin.toolSeo.listCheckSeo', compact('list', 'viewPerPage'));
    }

    public function loadDetailCheckSeo(Request $request){
        $result     = null;
        if(!empty($request->get('id'))){
            $item   = Seo::select('*')
                        ->where('id', $request->get('id'))
                        ->with('checkSeos')
                        ->first();
            $result = view('admin.toolSeo.detailCheckSeo', compact('item'))->render();
        }
        echo $result;
    }
    
}
