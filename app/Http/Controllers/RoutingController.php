<?php

namespace App\Http\Controllers;

use App\Models\TourLocation;
use App\Models\Tour;
use App\Models\ShipLocation;
use App\Models\ShipPartner;
use App\Models\Ship;
use App\Models\Guide;
use App\Models\Service;
use App\Models\ServiceLocation;
use App\Models\CarrentalLocation;
use App\Models\AirLocation;
use App\Models\Air;
use App\Models\TourContinent;
use App\Models\TourCountry;

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
            switch($checkExists['type']){
                case 'tour_location': /* Tour Location */
                    $item               = TourLocation::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_location');
                                            }])
                                            ->with('seo', 'tours.infoTour.seo', 'airLocations.infoAirLocation.airs.seo', 'guides.infoGuide.seo', 'shipLocations.infoShipLocation.ships.seo', 'shipLocations.infoShipLocation.ships.location.district', 'shipLocations.infoShipLocation.ships.location.province', 'shipLocations.infoShipLocation.ships.departure', 'shipLocations.infoShipLocation.ships.prices.times', 'shipLocations.infoShipLocation.ships.prices.partner', 'shipLocations.infoShipLocation.ships.partners.infoPartner.seo', 'shipLocations.infoShipLocation.ships.portDeparture', 'shipLocations.infoShipLocation.ships.portLocation', 'carrentalLocations.infoCarrentalLocation.seo', 'serviceLocations.infoServiceLocation.seo', 'serviceLocations.infoServiceLocation.services.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tourLocation.index', compact('item', 'breadcrumb', 'content'));
                case 'tour_info': /* Tour Info */
                    $item               = Tour::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_info');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_info');
                                            }])
                                            ->with('seo', 'staffs.infoStaff', 'options.prices', 'departure', 'content', 'timetables')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tour.index', compact('item', 'breadcrumb', 'content'));
                case 'ship_location': /* Ship Location */
                    $item               = ShipLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'ship_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'ship_location');
                                            }])
                                            ->with('seo', 'district', 'ships.seo', 'ships.location.district', 'ships.location.province', 'ships.departure', 'ships.prices.times', 'ships.prices.partner', 'ships.partners.infoPartner.seo', 'ships.portDeparture', 'ships.portLocation', 'tourLocations.infoTourLocation.airLocations.infoAirLocation.seo', 'tourLocations.infoTourLocation.serviceLocations.infoServiceLocation.seo', 'tourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo', 'tourLocations.infoTourLocation.guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentShipLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.shipLocation.index', compact('item', 'content', 'breadcrumb'));
                case 'ship_partner': /* Ship Partner */
                    $item               = ShipPartner::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'ship_partner');
                                            }])
                                            ->with('seo') /* 'ships.infoShip.location.tourLocations.infoTourLocation.seo' */
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentShipPartner').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.shipPartner.index', compact('item', 'content', 'breadcrumb'));
                case 'ship_info': /* Ship Info */
                    $item               = Ship::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'ship_info');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'ship_info');
                                            }])
                                            ->with('seo', 'partners.infoPartner.seo', 'portDeparture', 'portLocation', 'location.TourLocations.infoTourLocation.seo', 'location.TourLocations.infoTourLocation.airLocations.infoAirLocation.seo', 'location.TourLocations.infoTourLocation.serviceLocations.infoServiceLocation.seo', 'location.TourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo', 'location.TourLocations.infoTourLocation.guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentShip').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.ship.index', compact('item', 'content', 'breadcrumb'));
                case 'guide_info': /* Guide Info */
                    $item               = Guide::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'guide_info');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'guide_info');
                                            }])
                                            ->with('seo', 'tourLocations.infoTourLocation.seo', 'tourLocations.infoTourLocation.shipLocations.infoShipLocation.seo', 'tourLocations.infoTourLocation.airLocations.infoAirLocation.seo', 'tourLocations.infoTourLocation.serviceLocations.infoServiceLocation.seo', 'tourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentGuide').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.guide.index', compact('item', 'content', 'breadcrumb'));
                case 'service_info': /* Service Info */
                    $item               = Service::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'service_info');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'service_info');
                                            }])
                                            ->with('seo', 'serviceLocation.seo', 'serviceLocation.tourLocations.infoTourLocation.seo', 'serviceLocation.tourLocations.infoTourLocation.airLocations.infoAirLocation.seo', 'serviceLocation.tourLocations.infoTourLocation.shipLocations.infoShipLocation.seo', 'serviceLocation.tourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo', 'serviceLocation.tourLocations.infoTourLocation.guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentService').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.service.index', compact('item', 'content', 'breadcrumb'));
                case 'service_location': /* Service Location */
                    $item               = ServiceLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'service_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'service_location');
                                            }])
                                            ->with('seo', 'services.seo', 'tourLocations.infoTourLocation.tours.infoTour.seo', 'tourLocations.infoTourLocation.airLocations.infoAirLocation.airs.seo', 'tourLocations.infoTourLocation.shipLocations.infoShipLocation.ships.seo', 'tourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo', 'tourLocations.infoTourLocation.guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentServiceLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.serviceLocation.index', compact('item', 'content', 'breadcrumb'));
                case 'carrental_location': /* Carrental Location */
                    $item               = CarrentalLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'service_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'service_location');
                                            }])
                                            ->with('seo', 'tourLocations.infoTourLocation.seo', 'tourLocations.infoTourLocation.shipLocations.infoShipLocation.seo', 'tourLocations.infoTourLocation.airLocations.infoAirLocation.seo', 'tourLocations.infoTourLocation.serviceLocations.infoServiceLocation.seo', 'tourLocations.infoTourLocation.guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentCarrentalLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.carrentalLocation.index', compact('item', 'breadcrumb', 'content'));
                case 'air_location': /* Air Location */
                    $item               = AirLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'air_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'air_location');
                                            }])
                                            ->with('seo', 'airs.portDeparture', 'airs.portLocation', 'airs.seo', 'tourLocations.infoTourLocation.seo', 'tourLocations.infoTourLocation.shipLocations.infoShipLocation.seo', 'tourLocations.infoTourLocation.serviceLocations.infoServiceLocation.seo', 'tourLocations.infoTourLocation.guides.infoGuide.seo', 'tourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentAirLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.airLocation.index', compact('item', 'breadcrumb', 'content'));
                case 'air_info': /* Air Info */
                    $item               = Air::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'air_info');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'air_info');
                                            }])
                                            ->with('seo', 'airLocation.tourLocations.infoTourLocation.seo', 'airLocation.tourLocations.infoTourLocation.shipLocations.infoShipLocation.ships.seo', 'airLocation.tourLocations.infoTourLocation.serviceLocations.infoServiceLocation.services.seo', 'airLocation.tourLocations.infoTourLocation.carrentalLocations.infoCarrentalLocation.seo', 'airLocation.tourLocations.infoTourLocation.guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentAir').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.air.index', compact('item', 'breadcrumb', 'content'));
                case 'tour_continent':
                    $item               = TourContinent::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_continent');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_continent');
                                            }])
                                            ->with('seo', 'tourCountries.tours.infoTourForeign.seo', 'airLocations.infoAirLocation.airs.seo', 'serviceLocations.infoServiceLocation.services.seo', 'guides.infoGuide.seo')
                                            ->first();
                    // $content            = Blade::render(Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tourContinent.index', compact('item', 'breadcrumb'));
                case 'tour_country':
                    $item               = TourCountry::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_country');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_country');
                                            }])
                                            ->with('seo', 'tours.infoTourForeign.seo', 'airLocations.infoAirLocation.airs.seo', 'serviceLocations.infoServiceLocation.services.seo', 'guides.infoGuide.seo')
                                            ->first();
                    // $content            = Blade::render(Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tourCountry.index', compact('item', 'breadcrumb'));
            }
        }else {
            /* Error 404 */
            // return view('main.error.404');

            // return view('main.blog.detail');
        }
    }

    

}
