<?php

namespace App\Http\Controllers;

use App\Models\TourLocation;
use App\Models\Tour;
use App\Models\ShipLocation;
use App\Models\ShipPartner;
use App\Models\Ship;
use App\Models\Guide;
use App\Models\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Blade;

use App\Helpers\Url;

class RoutingController extends Controller {

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
                                        ->with(['files' => function($query){
                                            $query->where('relation_table', 'tour_location');
                                        }])
                                        ->with('seo', 'tours.infoTour.seo', 'guides.infoGuide.seo', 'services.seo', 'shipLocations.infoShipLocation.ships.seo', 'shipLocations.infoShipLocation.ships.location.district', 'shipLocations.infoShipLocation.ships.location.province', 'shipLocations.infoShipLocation.ships.departure', 'shipLocations.infoShipLocation.ships.prices.times', 'shipLocations.infoShipLocation.ships.prices.partner', 'shipLocations.infoShipLocation.ships.partners.infoPartner.seo', 'shipLocations.infoShipLocation.ships.portDeparture', 'shipLocations.infoShipLocation.ships.portLocation', 'carrentalLocations.infoCarrentalLocation.seo')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.tourLocation.index', compact('item', 'breadcrumb', 'content'));
            }else if($checkExists['type']==='tour_info'){ // ====== Tour =============================
                $item               = Tour::select('*')
                                        ->whereHas('seo', function($q) use ($checkExists){
                                            $q->where('slug', $checkExists['slug']);
                                        })
                                        ->with(['files' => function($query){
                                            $query->where('relation_table', 'tour_info');
                                        }])
                                        ->with('seo', 'staffs.infoStaff', 'options.prices', 'departure', 'content', 'timetables')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.tour.index', compact('item', 'breadcrumb', 'content'));
            }else if($checkExists['type']==='ship_location'){ // ====== Ship Location =============================
                $item               = ShipLocation::select('*')
                                        ->whereHas('seo', function($query) use($checkExists){
                                            $query->where('slug', $checkExists['slug']);
                                        })
                                        ->with(['files' => function($query){
                                            $query->where('relation_table', 'ship_location');
                                        }])
                                        ->with('seo', 'district', 'ships.seo', 'ships.location.district', 'ships.location.province', 'ships.departure', 'ships.prices.times', 'ships.prices.partner', 'ships.partners.infoPartner.seo', 'ships.portDeparture', 'ships.portLocation')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentShipLocation').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.shipLocation.index', compact('item', 'content', 'breadcrumb'));
            }else if($checkExists['type']==='ship_partner'){ // ====== Ship Partner =============================
                $item               = ShipPartner::select('*')
                                        ->whereHas('seo', function($query) use($checkExists){
                                            $query->where('slug', $checkExists['slug']);
                                        })
                                        ->with('seo', 'questions')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentShipPartner').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.shipPartner.index', compact('item', 'content', 'breadcrumb'));
            }else if($checkExists['type']==='ship_info'){ // ====== Ship =============================
                $item               = Ship::select('*')
                                        ->whereHas('seo', function($query) use($checkExists){
                                            $query->where('slug', $checkExists['slug']);
                                        })
                                        ->with(['files' => function($query){
                                            $query->where('relation_table', 'ship_info');
                                        }])
                                        ->with('seo', 'partners.infoPartner.seo', 'portDeparture', 'portLocation')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentShip').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.ship.index', compact('item', 'content', 'breadcrumb'));
            }else if($checkExists['type']==='guide_info'){
                $item               = Guide::select('*')
                                        ->whereHas('seo', function($query) use($checkExists){
                                            $query->where('slug', $checkExists['slug']);
                                        })
                                        ->with(['files' => function($query){
                                            $query->where('relation_table', 'guide_info');
                                        }])
                                        ->with('seo', 'tourLocations.infoTourLocation.seo', 'tourLocations.infoTourLocation.shipLocations.infoShipLocation.seo', 'tourLocations.infoTourLocation.services.seo')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentGuide').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.guide.index', compact('item', 'content', 'breadcrumb'));
            }else if($checkExists['type']==='service_info'){
                $item               = Service::select('*')
                                        ->whereHas('seo', function($query) use($checkExists){
                                            $query->where('slug', $checkExists['slug']);
                                        })
                                        ->with(['files' => function($query){
                                            $query->where('relation_table', 'service_info');
                                        }])
                                        ->with('seo', 'tourLocation.shipLocations.infoShipLocation.seo', 'tourLocation.services.seo')
                                        ->first();
                $content            = Blade::render(Storage::get(config('admin.storage.contentService').$item->seo->slug.'.blade.php'));
                $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                return view('main.service.index', compact('item', 'content', 'breadcrumb'));
            }
        }else {
            /* Error 404 */
            // return view('main.error.404');

            // return view('main.blog.detail');
        }
    }

    

}
