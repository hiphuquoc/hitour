<?php

namespace App\Http\Controllers;

// use App\Services\UrlService;
use App\Models\Tour;
use App\Models\TourLocation;

use Illuminate\Support\Facades\DB;

use App\Helpers\Url;

class RoutingController extends Controller {
    // public function __construct(UrlService $UrlService){
    //     $this->UrlService  = $UrlService;
    // }

    public function routing($slug, $slug2 = null, $slug3 = null, $slug4 = null, $slug5 = null){
        $tmpSlug        = [$slug, $slug2, $slug3, $slug4, $slug5];
        /* loại bỏ phần tử rỗng */
        $arraySlug      = [];
        foreach($tmpSlug as $slug) if(!empty($slug)) $arraySlug[] = $slug;
        /* check url có tồn tại? => lấy thông tin */
        $checkExists    = Url::checkUrlExists($arraySlug);
        if(!empty($checkExists['type'])){
            if($checkExists['type']==='tour_location'){ // ====== Tour Location =============================
                $item               = TourLocation::select('*')
                                        ->whereHas('seo', function($q) use ($checkExists){
                                            $q->where('slug', $checkExists['slug']);
                                        })
                                        ->with('seo', 'files', 'tours')
                                        ->first();
                /* lấy danh sách tour */
                $arrayIdTour        = [];
                foreach($item->tours as $tmp) $arrayIdTour[]  = $tmp->tour_info_id;
                $list               = DB::table('seo')
                                        ->join('tour_info as ti', 'ti.seo_id', '=', 'seo.id')
                                        ->join('relation_tour_location as rtl', 'rtl.tour_info_id', '=', 'ti.id')
                                        ->join('tour_location as tl', 'tl.id', '=', 'rtl.tour_location_id')
                                        ->join('tour_departure as td', 'td.id', '=', 'ti.tour_departure_id')
                                        ->select('seo.id', 'seo.slug', 'seo.level', 'seo.parent', 'seo.description', 'seo.image', 'seo.image_small', 'ti.id as tour_info_id', 'ti.pick_up', 'ti.code', 'ti.name', 'ti.price_show', 'ti.price_del', 'ti.days', 'ti.nights', 'ti.departure_schedule', 'td.name as tour_departure_name', 'tl.name as tour_location_name')
                                        ->where('rtl.tour_location_id', $item->id)
                                        ->where('ti.status_show', 1)
                                        ->get();
                $list               = Url::buildFullLinkArray($list);
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.tourLocation.index', compact('item', 'list', 'breadcrumb'));
            }else if($checkExists['type']==='tour_info'){ // ====== Tour =============================
                $item               = Tour::select('*')
                                        ->whereHas('seo', function($q) use ($checkExists){
                                            $q->where('slug', $checkExists['slug']);
                                        })
                                        ->with('seo', 'files', 'staffs', 'options')
                                        ->first();
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.tour.index', compact('item', 'breadcrumb'));
            }
        }else {
            /* Error 404 */
            // return view('main.error.404');

            // return view('main.blog.detail');
        }
    }

    

}
