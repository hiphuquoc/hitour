<?php

namespace App\Http\Controllers;

use App\Models\TourLocation;
use App\Models\Tour;
use App\Models\ShipLocation;
use App\Models\ShipPartner;
use App\Models\AirPartner;
use App\Models\Ship;
use App\Models\Guide;
use App\Models\Service;
use App\Models\ServiceLocation;
use App\Models\CarrentalLocation;
use App\Models\AirLocation;
use App\Models\Air;
use App\Models\TourContinent;
use App\Models\TourCountry;
use App\Models\Category;
use App\Models\Blog;
use App\Models\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Redirect;
use App\Helpers\Url;
use App\Models\TourInfoForeign;

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
                                            ->with('tours.infoTour', function($query){
                                                $query->where('status_show', 1);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_location');
                                            }])
                                            ->with('seo', 'airLocations', 'guides', 'shipLocations', 'carrentalLocations', 'serviceLocations', 'destinations', 'specials')
                                            ->first();
                    /* danh sách blog điểm đến */
                    $arrayIdDestination = [];
                    foreach($item->destinations as $destination) $arrayIdDestination[] = $destination->infoCategory->id;
                    $destinationList    = Blog::select('*')
                                            ->whereHas('categories.infoCategory', function($query) use($arrayIdDestination){
                                                $query->whereIn('id', $arrayIdDestination);
                                            })
                                            ->with('seo')
                                            ->get();
                    /* danh sách blog đặc sản */
                    $arrayIdSpecial     = [];
                    foreach($item->specials as $special) $arrayIdSpecial[] = $special->infoCategory->id;
                    $specialList        = Blog::select('*')
                                            ->whereHas('categories.infoCategory', function($query) use($arrayIdSpecial){
                                                $query->whereIn('id', $arrayIdSpecial);
                                            })
                                            ->with('seo')
                                            ->get();                    
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tourLocation.index', compact('item', 'breadcrumb', 'destinationList', 'specialList', 'content'));
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
                                            ->with('seo', 'locations', 'staffs.infoStaff', 'options.prices', 'departure', 'content', 'timetables')
                                            ->first();
                    $idTour                 = $item->id ?? 0;
                    $arrayIdTourLocation    = [];
                    foreach($item->locations as $location) $arrayIdTourLocation[]  = $location->infoLocation->id;
                    $related                = Tour::select('*')
                                                ->where('id', '!=', $idTour)
                                                ->where('status_show', 1)
                                                ->whereHas('locations.infoLocation', function($query) use($arrayIdTourLocation){
                                                    $query->whereIn('id', $arrayIdTourLocation);
                                                })
                                                ->with('seo')
                                                ->get();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tour.index', compact('item', 'breadcrumb', 'content', 'related'));
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
                                            ->with('seo', 'district', 'ships', 'tourLocations')
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
                                            ->with('seo', 'partners', 'portDeparture', 'portLocation', 'location')
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
                                            ->with('seo', 'tourLocations')
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
                                            ->with('seo', 'serviceLocation')
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
                                            ->with('seo', 'services', 'tourLocations')
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
                                                $query->where('relation_table', 'carrental_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'carrental_location');
                                            }])
                                            ->with('seo', 'tourLocations')
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
                                            ->with('seo', 'airs', 'tourLocations')
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
                                            ->with('seo', 'airLocation')
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
                                            ->with('seo', 'tourCountries', 'airLocations', 'serviceLocations', 'guides.infoGuide.seo')
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
                                            ->with('seo', 'tours', 'airLocations', 'serviceLocations', 'guides')
                                            ->first();
                    // $content            = Blade::render(Storage::get(config('admin.storage.contentTourLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tourCountry.index', compact('item', 'breadcrumb'));
                case 'tour_info_foreign':
                    $item               = TourInfoForeign::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_info_foreign');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_info_foreign');
                                            }])
                                            ->with('seo', 'staffs.infoStaff', 'options.prices', 'departure', 'content', 'timetables')
                                            ->first();
                    $idTour                 = $item->id ?? 0;
                    $arrayIdTourCountry     = [];
                    foreach($item->tourCountries as $tourCountry) $arrayIdTourCountry[]  = $tourCountry->infoCountry->id;
                    $related                = TourInfoForeign::select('*')
                                                ->where('id', '!=', $idTour)
                                                ->whereHas('tourCountries.infoCountry', function($query) use($arrayIdTourCountry){
                                                    $query->whereIn('id', $arrayIdTourCountry);
                                                })
                                                ->with('seo')
                                                ->get();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.tourInfoForeign.index', compact('item', 'breadcrumb', 'content', 'related'));
                case 'category_info':
                    $item               = Category::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with('seo', 'tourLocations')
                                            ->first();
                    /* danh sách category cấp 1 */
                    $listCategoryLv1    = Category::select('*')
                                            ->whereHas('seo', function($query){
                                                $query->where('level', 1);
                                            })
                                            ->with('seo')
                                            ->get();
                    /* lấy id category và id category con (đệ quy) */
                    $arrayIdCategory    = array_merge([$item->id], Category::getArrayCategoryChildByIdSeo($item->seo->id));
                    /* dùng cho schema list */
                    $list               = Blog::select('*')
                                            ->whereHas('categories.infoCategory', function($query) use($arrayIdCategory){
                                                $query->whereIn('id', $arrayIdCategory);
                                            })
                                            ->with('seo')
                                            ->get();
                    /* content */
                    // $content            = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                    /* breadcrumb */
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    /* lấy thông tin category con (để phân biệt giao diện category cấp cuối và không phải cấp cuối) */
                    $infoCategoryChilds = Category::select('*')
                                            ->whereHas('seo', function($q) use ($item){
                                                $q->where('parent', $item->seo->id);
                                            })
                                            ->with('seo', 'tourLocations')
                                            ->get();
                    if(!empty($infoCategoryChilds)&&$infoCategoryChilds->isNotEmpty()){ /* trường hợp category chưa phải cấp cuối */
                        /* lấy danh sách từng blog theo category child */
                        foreach($infoCategoryChilds as $infoCategoryChild){
                            $infoCategoryChild->childs  = Blog::select('*')
                                                            ->whereHas('categories.infoCategory', function($query) use($infoCategoryChild){
                                                                $query->where('id', $infoCategoryChild->id);
                                                            })
                                                            ->with('seo')
                                                            ->get();
                        }
                        return view('main.category.indexParent', compact('item', 'breadcrumb', 'infoCategoryChilds', 'list', 'listCategoryLv1'));
                    }else { /* trường hợp category là cấp cuối */
                        /* lấy danh sách blog bằng array id category */
                        $blogs              = Blog::select('*')
                        ->whereHas('categories.infoCategory', function($query) use($arrayIdCategory){
                            $query->whereIn('id', $arrayIdCategory);
                        })
                        ->with('seo')
                        ->get();
                        return view('main.category.index', compact('item', 'breadcrumb', 'blogs', 'list', 'listCategoryLv1'));
                    }
                case 'blog_info':
                    $item               = Blog::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with('seo')
                                            ->first();
                    $idParent           = $item->seo->parent ?? 0;
                    $parent             = Category::select('*')
                                            ->whereHas('seo', function($query) use($idParent){
                                                $query->where('id', $idParent);
                                            })
                                            ->with('seo', 'tourLocations')
                                            ->first();
                    /* lấy category cùng cấp với category cha của blog */
                    $categoryRelates    = Category::select('*')
                                            ->whereHas('seo', function($query) use($parent){
                                                $query->where('parent', $parent->seo->parent);
                                            })
                                            ->with('seo', 'tourLocations')
                                            ->get();
                    /* lấy blog trong category và category con */
                    $arrayIdCategory    = array_merge([$parent->id], Category::getArrayCategoryChildByIdSeo($parent->seo->id));
                    $blogRelates        = Blog::select('*')
                                            ->whereHas('categories.infoCategory', function($query) use($arrayIdCategory){
                                                $query->whereIn('id', $arrayIdCategory);
                                            })
                                            ->where('id', '!=', $item->id)
                                            ->with('seo')
                                            ->get();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentBlog').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                    return view('main.blog.index', compact('item', 'breadcrumb', 'parent', 'blogRelates', 'categoryRelates', 'content'));
                case 'page_info':
                        $item           = Page::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists['slug']);
                                            })
                                            ->with('seo')
                                            ->first();
                        $content        = Blade::render(Storage::get(config('admin.storage.contentPage').$item->seo->slug.'.blade.php'));
                        $breadcrumb     = !empty($checkExists['data']) ? Url::buildFullLinkArray($checkExists['data']) : null;
                        $shipPartners   = ShipPartner::select('*')
                                            ->with('seo')
                                            ->get();
                        $airPartners    = AirPartner::select('*')
                                            ->with('seo')
                                            ->get();
                        return view('main.page.index', compact('item', 'breadcrumb', 'shipPartners', 'airPartners', 'content'));
                default:
                    return Redirect::to('/', 301);
            }
        }else {
            /* Error 404 */
            // return view('main.error.404');
            // return view('main.blog.detail');

            return \App\Http\Controllers\ErrorController::error404();
        }
    }
}
