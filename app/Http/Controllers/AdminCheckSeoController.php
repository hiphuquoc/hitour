<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Models\Seo;

class AdminCheckSeoController extends Controller {

    public function listCheckSeo(Request $request){
        $params         = [];
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        $viewPerPage    = Cookie::get('viewCheckSeo') ?? 50;
        $list           = Seo::select('*')
                            ->whereHas('checkSeos', function($query){
                                
                            })
                            ->when(!empty($params['search_name']), function($query) use($params){
                                $query->where('title', 'like', '%'.$params['search_name'].'%')
                                        ->orWhere('slug', 'like', '%'.$params['search_name'].'%');
                            })
                            ->with('checkSeos')
                            ->paginate($viewPerPage);
        // dd($list);
        return view('admin.toolSeo.listCheckSeo', compact('list', 'viewPerPage', 'params'));
    }

    public function loadDetailCheckSeo(Request $request){
        $result         = null;
        if(!empty($request->get('id'))){
            $item       = Seo::select('*')
                            ->where('id', $request->get('id'))
                            ->with('checkSeos')
                            ->first();
            $tabActive  = $request->get('tab_active') ?? 'heading';
            $result     = view('admin.toolSeo.detailCheckSeo', compact('item', 'tabActive'))->render();
        }
        echo $result;
    }
    
}
