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
use App\Models\ComboLocation;
use App\Models\Combo;
use App\Models\HotelLocation;
use App\Models\Hotel;

class RoutingController extends Controller {

    public function routing($slug, $slug2 = null, $slug3 = null, $slug4 = null, $slug5 = null, $slug6 = null, $slug7 = null, $slug8 = null, $slug9 = null, $slug10 = null){
        // $tmpSlug        = [$slug, $slug2, $slug3, $slug4, $slug5, $slug6, $slug7, $slug8, $slug9, $slug10];
        /* dùng request uri */
        $tmpSlug        = explode('/', $_SERVER['REQUEST_URI']);
        /* loại bỏ phần tử rỗng */
        $arraySlug      = [];
        foreach($tmpSlug as $slug) if(!empty($slug)&&$slug!='public') $arraySlug[] = $slug;
        /* loại bỏ hashtag và request trước khi check */
        $arraySlug[count($arraySlug)-1] = preg_replace('#([\?|\#]+).*$#imsU', '', end($arraySlug));
        $urlRequest     = implode('/', $arraySlug);
        /* check url có tồn tại? => lấy thông tin */
        $checkExists    = Url::checkUrlExists(end($arraySlug));
        /* nếu sai => redirect về link đúng */
        if(!empty($checkExists->slug_full)&&$checkExists->slug_full!=$urlRequest){
            /* ko rút gọn trên 1 dòng được => lỗi */
            return Redirect::to($checkExists->slug_full, 301);
        }
        /* nếu đúng => xuất dữ liệu */
        if(!empty($checkExists->type)){
            $flagMatch              = false;
            /* cache HTML */
            $nameCache              = self::buildNameCache($checkExists['slug_full']).'.'.config('main.cache.extension');
            $pathCache              = Storage::path(config('main.cache.folderSave')).$nameCache;
            $cacheTime    	        = 1800;
            if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
                $xhtml = file_get_contents($pathCache);
                echo $xhtml;
            }else {
                /* ===== TOUR LOCATION */
                if($checkExists->type=='tour_location'){
                    $flagMatch          = true;
                    $item               = TourLocation::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.tourLocation.index', compact('item', 'breadcrumb', 'destinationList', 'specialList', 'content'))->render();
                }
                /* ===== TOUR INFO */
                if($checkExists->type=='tour_info'){
                    $flagMatch              = true;
                    $item                   = Tour::select('*')
                                                ->whereHas('seo', function($q) use ($checkExists){
                                                    $q->where('slug', $checkExists->slug);
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
                    $content                = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                    $breadcrumb             = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml                  = view('main.tour.index', compact('item', 'breadcrumb', 'content', 'related'))->render();
                }
                /* ===== SHIP LOCATION */
                if($checkExists->type=='ship_location'){
                    $flagMatch              = true;
                    $item                   = ShipLocation::select('*')
                                                ->whereHas('seo', function($query) use($checkExists){
                                                    $query->where('slug', $checkExists->slug);
                                                })
                                                ->with(['files' => function($query){
                                                    $query->where('relation_table', 'ship_location');
                                                }])
                                                ->with(['questions' => function($q){
                                                    $q->where('relation_table', 'ship_location');
                                                }])
                                                ->with('seo', 'district', 'ships', 'tourLocations', 'categories')
                                                ->first();
                    /* lấy blog liên quan ghép vào từng category liên kết với ship_location */
                    $i                          = 0;
                    foreach($item->categories as $category){
                        /* lấy id category từ cấp hiện tại & tất cả cấp con */
                        $arrayIdCategoryChild   = Category::getArrayCategoryChildByIdSeo($category->infoCategory->seo->id);
                        $arrayIdCategory        = array_merge($arrayIdCategoryChild, [$category->infoCategory->id]);
                        /* lấy blogs thuộc tất cả category trên ghép vào collection */
                        $blogs                  = Blog::select('*')
                                                    ->whereHas('categories.infoCategory', function($query) use($arrayIdCategory){
                                                        $query->whereIn('id', $arrayIdCategory);
                                                    })
                                                    ->with('seo')
                                                    ->get();
                        $item->categories[$i]->blogs = $blogs;
                        ++$i;
                    }
                    /* lịch tàu & bảng giá mặc định */
                    $schedule               = Blade::render(Storage::get(config('admin.storage.contentSchedule').$item->seo->slug.'.blade.php'));
                    /* nội dung */
                    $content                = Blade::render(Storage::get(config('admin.storage.contentShipLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb             = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml                  = view('main.shipLocation.index', compact('item', 'schedule', 'content', 'breadcrumb'))->render();
                }
                /* ===== SHIP PARTNER */
                if($checkExists->type=='ship_partner'){
                    $flagMatch          = true;
                    $item               = ShipPartner::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
                                            })
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'ship_partner');
                                            }])
                                            ->with('seo') /* 'ships.infoShip.location.tourLocations.infoTourLocation.seo' */
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentShipPartner').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.shipPartner.index', compact('item', 'content', 'breadcrumb'))->render();
                }
                /* ===== SHIP INFO */
                if($checkExists->type=='ship_info'){
                    $flagMatch          = true;
                    $item               = Ship::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'ship_info');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'ship_info');
                                            }])
                                            ->with('seo', 'partners', 'portDeparture', 'portLocation', 'location')
                                            ->first();
                    /* lịch tàu & bảng giá mặc định */
                    $schedule           = Blade::render(Storage::get(config('admin.storage.contentSchedule').$item->seo->slug.'.blade.php'));
                    /* nội dung */
                    $content            = Blade::render(Storage::get(config('admin.storage.contentShip').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.ship.index', compact('item', 'schedule', 'content', 'breadcrumb'))->render();
                }
                /* ===== GUIDE INFO */
                if($checkExists->type=='guide_info'){
                    $flagMatch          = true;
                    $item               = Guide::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'guide_info');
                                            }])
                                            ->with('seo', 'tourLocations')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentGuide').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.guide.index', compact('item', 'content', 'breadcrumb'))->render();
                }
                /* ===== SERVICE INFO */
                if($checkExists->type=='service_info'){
                    $flagMatch          = true;
                    $item               = Service::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.service.index', compact('item', 'content', 'breadcrumb'))->render();
                }
                /* ===== SERVICE LOCATION */
                if($checkExists->type=='service_location'){
                    $flagMatch          = true;
                    $item               = ServiceLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.serviceLocation.index', compact('item', 'content', 'breadcrumb'))->render();
                }
                /* ===== CARRENTAL LOCATION */
                if($checkExists->type=='carrental_location'){
                    $flagMatch          = true;
                    $item               = CarrentalLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.carrentalLocation.index', compact('item', 'breadcrumb', 'content'))->render();
                }
                /* ===== AIR LOCATION */
                if($checkExists->type=='air_location'){
                    $flagMatch          = true;
                    $item               = AirLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.airLocation.index', compact('item', 'breadcrumb', 'content'))->render();
                }
                /* ===== AIR INFO */
                if($checkExists->type=='air_info'){
                    $flagMatch          = true;
                    $item               = Air::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.air.index', compact('item', 'breadcrumb', 'content'))->render();
                }
                /* ===== TOUR CONTINENT */
                if($checkExists->type=='tour_continent'){
                    $flagMatch          = true;
                    $item               = TourContinent::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_continent');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_continent');
                                            }])
                                            ->with('seo', 'tourCountries', 'airLocations', 'serviceLocations', 'guides.infoGuide.seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTourContinent').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.tourContinent.index', compact('item', 'breadcrumb', 'content'))->render();
                }
                /* ===== TOUR COUNTRY */
                if($checkExists->type=='tour_country'){
                    $flagMatch          = true;
                    $item               = TourCountry::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'tour_country');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'tour_country');
                                            }])
                                            ->with('seo', 'tours', 'airLocations', 'serviceLocations', 'guides')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentTourCountry').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.tourCountry.index', compact('item', 'breadcrumb', 'content'))->render();
                }
                /* ===== TOUR INFO FOREIGN */
                if($checkExists->type=='tour_info_foreign'){
                    $flagMatch          = true;
                    $item               = TourInfoForeign::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.tourInfoForeign.index', compact('item', 'breadcrumb', 'content', 'related'))->render();
                }
                /* ===== TOUR CATEGORY INFO */
                if($checkExists->type=='category_info'){
                    $flagMatch          = true;
                    $item               = Category::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
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
                        $xhtml          = view('main.category.indexParent', compact('item', 'breadcrumb', 'infoCategoryChilds', 'list', 'listCategoryLv1'))->render();
                    }else { /* trường hợp category là cấp cuối */
                        /* lấy danh sách blog bằng array id category */
                        $blogs              = Blog::select('*')
                        ->whereHas('categories.infoCategory', function($query) use($arrayIdCategory){
                            $query->whereIn('id', $arrayIdCategory);
                        })
                        ->with('seo')
                        ->get();
                        $xhtml          = view('main.category.index', compact('item', 'breadcrumb', 'blogs', 'list', 'listCategoryLv1'))->render();
                    }
                }
                /* ===== TOUR BLOG INFO */
                if($checkExists->type=='blog_info'){
                    $flagMatch          = true;
                    $item               = Blog::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
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
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.blog.index', compact('item', 'breadcrumb', 'parent', 'blogRelates', 'categoryRelates', 'content'))->render();
                }
                /* ===== TOUR PAGE INFO */
                if($checkExists->type=='page_info'){
                    $flagMatch          = true;
                    $item               = Page::select('*')
                                            ->whereHas('seo', function($q) use ($checkExists){
                                                $q->where('slug', $checkExists->slug);
                                            })
                                            ->with('seo')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentPage').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $shipPartners       = ShipPartner::select('*')
                                            ->with('seo')
                                            ->get();
                    $airPartners        = AirPartner::select('*')
                                            ->with('seo')
                                            ->get();
                    $xhtml              = view('main.page.index', compact('item', 'breadcrumb', 'shipPartners', 'airPartners', 'content'))->render();
                }
                /* ===== COMBO LOCATION */
                if($checkExists->type=='combo_location'){
                    $flagMatch          = true;
                    $item               = ComboLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'combo_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'combo_location');
                                            }])
                                            ->with('seo', 'combos')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentComboLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.comboLocation.index', compact('item', 'content', 'breadcrumb'))->render();
                }
                /* ===== COMBO INFO */
                if($checkExists->type=='combo_info'){
                    $flagMatch              = true;
                    $item                   = Combo::select('*')
                                                ->whereHas('seo', function($q) use ($checkExists){
                                                    $q->where('slug', $checkExists->slug);
                                                })
                                                ->with(['files' => function($query){
                                                    $query->where('relation_table', 'combo_info');
                                                }])
                                                ->with(['questions' => function($q){
                                                    $q->where('relation_table', 'combo_info');
                                                }])
                                                ->with('seo', 'locations', 'staffs.infoStaff', 'options.prices')
                                                ->first();
                    $idCombo                = $item->id ?? 0;
                    $arrayidComboLocation   = [];
                    foreach($item->locations as $location) $arrayidComboLocation[]  = $location->infoLocation->id;
                    $related                = Combo::select('*')
                                                // ->where('id', '!=', $idCombo)
                                                ->where('status_show', 1)
                                                ->whereHas('locations.infoLocation', function($query) use($arrayidComboLocation){
                                                    $query->whereIn('id', $arrayidComboLocation);
                                                })
                                                ->with('seo')
                                                ->get();
                    // $content                = Blade::render(Storage::get(config('admin.storage.contentTour').$item->seo->slug.'.blade.php'));
                    $breadcrumb             = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml                  = view('main.combo.index', compact('item', 'breadcrumb', 'related'))->render();
                }
                /* ===== HOTEL LOCATION */
                if($checkExists->type=='hotel_location'){
                    $flagMatch          = true;
                    $item               = HotelLocation::select('*')
                                            ->whereHas('seo', function($query) use($checkExists){
                                                $query->where('slug', $checkExists->slug);
                                            })
                                            ->with(['files' => function($query){
                                                $query->where('relation_table', 'hotel_location');
                                            }])
                                            ->with(['questions' => function($q){
                                                $q->where('relation_table', 'hotel_location');
                                            }])
                                            ->with('seo', 'hotels')
                                            ->first();
                    $content            = Blade::render(Storage::get(config('admin.storage.contentHotelLocation').$item->seo->slug.'.blade.php'));
                    $breadcrumb         = Url::buildBreadcrumb($checkExists->slug_full);
                    $xhtml              = view('main.hotelLocation.index', compact('item', 'content', 'breadcrumb'))->render();
                }
                /* Ghi dữ liệu - Xuất kết quả */
                if($flagMatch==true){
                    echo $xhtml;
                    if(env('APP_CACHE_HTML')==true) Storage::put(config('main.cache.folderSave').$nameCache, $xhtml);
                }else {
                    return \App\Http\Controllers\ErrorController::error404();
                }
            }
        }else {
            return \App\Http\Controllers\ErrorController::error404();
        }
    }

    public static function buildNameCache($slugFull){
        $result     = null;
        if(!empty($slugFull)){
            $tmp    = explode('/', $slugFull);
            $result = [];
            foreach($tmp as $t){
                if(!empty($t)) $result[] = $t;
            }
            $result = implode('-', $result);
        }
        return $result;
    }
}
