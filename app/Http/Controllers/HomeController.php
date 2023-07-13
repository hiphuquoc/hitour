<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShipLocation;
use App\Models\AirLocation;
use App\Models\ServiceLocation;
use App\Models\TourLocation;
use App\Models\ShipPartner;
use App\Models\AirPartner;
use App\Models\Seo;
use App\Models\TourTimetable;
use App\Models\TourTimetableForeign;
use App\Models\TourContent;
use App\Models\TourContentForeign;
use App\Models\HotelRoomFacility;
use Illuminate\Support\Facades\Storage;

use App\Jobs\CheckSeo;
use App\Models\Redirect;

use Goutte\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class HomeController extends Controller {

    private $arrayData  = array();
    private $count      = 0;

    public function home(){
        /* cache HTML */
        $nameCache              = 'home.'.config('main.cache.extension');
        $pathCache              = Storage::path(config('main.cache.folderSave')).$nameCache;
        $cacheTime    	        = 1800;
        if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
            $xhtml = file_get_contents($pathCache);
        }else {
            $item               = Seo::select('*')
                                    ->where('slug', '')
                                    ->first();
            $shipLocations      = ShipLocation::select('*')
                                    ->with('seo')
                                    ->get();
            $airLocations       = AirLocation::select('*')
                                    ->with('seo')
                                    ->get();
            $serviceLocations   = ServiceLocation::select('*')
                                    ->where('district_id', '!=', '0') /* vé giải trí trong nước */
                                    ->with('seo', 'services')
                                    ->get();
            $islandLocations    = TourLocation::select('*')
                                    ->where('island', '1')
                                    ->with('seo')
                                    ->get();
            $specialLocations   = TourLocation::select('*')
                                    ->where('special', '1')
                                    ->with('seo')
                                    ->get();
            $shipPartners       = ShipPartner::select('*')
                                    ->with('seo')
                                    ->get();
            $airPartners        = AirPartner::select('*')
                                    ->with('seo')
                                    ->get();
            $xhtml  = view('main.home.home', compact('item', 'shipLocations', 'airLocations', 'serviceLocations', 'islandLocations', 'specialLocations', 'shipPartners', 'airPartners'))->render();
            if(env('APP_CACHE_HTML')==true) Storage::put(config('main.cache.folderSave').$nameCache, $xhtml);
        }
        echo $xhtml;
    }

    /* ===== Tính năng thay tất cả các ảnh hỗ trợ Loading ===== */
    public static function changeImageInContentWithLoading(){
        $data           = glob(Storage::path('/public/contents').'/*');
        $fileSuccess    = [];
        $fileError      = [];
        foreach($data as $child){
            $dataChild  = glob($child.'/*');
            foreach($dataChild as $fileName){
                $flag   = self::actionChangeImageInContentWithLoading($fileName);
                if($flag==true) {
                    $fileSuccess[]  = $fileName;
                }else {
                    $fileError[]    = $fileName;
                }
            }
        }
        dd($fileSuccess);
    }
    public static function actionChangeImageInContentWithLoading($fileName){
        if(!empty($fileName)){
            $content        = file_get_contents($fileName);
            $content        = AdminImageController::replaceImageInContentWithLoading($content);
            return file_put_contents($fileName, $content);
        }
        return false;
    }
    public static function changeImageInContentWithLoadingTourInfo(){
        /* cập nhật content bảng tour_timetable */
        $data           = TourTimetable::select('*')
                            ->get();
        foreach($data as $item){
            $params     = [
                'content'           => AdminImageController::replaceImageInContentWithLoading($item->content),
                'content_sort'      => AdminImageController::replaceImageInContentWithLoading($item->content_sort)
            ];
            TourTimetable::updateItem($item->id, $params);
        }
        /* cập nhật content bảng tour_timetable_foreign */
        $data           = TourTimetableForeign::select('*')
                            ->get();
        foreach($data as $item){
            $params     = [
                'content'           => AdminImageController::replaceImageInContentWithLoading($item->content),
                'content_sort'      => AdminImageController::replaceImageInContentWithLoading($item->content_sort)
            ];
            TourTimetableForeign::updateItem($item->id, $params);
        }
        /* cập nhật content bảng tour_content */
        $data           = TourContent::select('*')
                            ->get();
        foreach($data as $item){
            $params     = [
                'special_content'   => AdminImageController::replaceImageInContentWithLoading($item->special_content),
                'special_list'      => AdminImageController::replaceImageInContentWithLoading($item->special_list),
                'include'           => AdminImageController::replaceImageInContentWithLoading($item->include),
                'not_include'       => AdminImageController::replaceImageInContentWithLoading($item->not_include),
                'policy_child'      => AdminImageController::replaceImageInContentWithLoading($item->policy_child),
                'menu'              => AdminImageController::replaceImageInContentWithLoading($item->menu),
                'hotel'             => AdminImageController::replaceImageInContentWithLoading($item->hotel),
                'policy_cancel'     => AdminImageController::replaceImageInContentWithLoading($item->policy_cancel)
            ];
            TourContent::updateItem($item->id, $params);
        }
        /* cập nhật content bảng tour_content_foreign */
        $data           = TourContentForeign::select('*')
                            ->get();
        foreach($data as $item){
            $params     = [
                'special_content'   => AdminImageController::replaceImageInContentWithLoading($item->special_content),
                'special_list'      => AdminImageController::replaceImageInContentWithLoading($item->special_list),
                'include'           => AdminImageController::replaceImageInContentWithLoading($item->include),
                'not_include'       => AdminImageController::replaceImageInContentWithLoading($item->not_include),
                'policy_child'      => AdminImageController::replaceImageInContentWithLoading($item->policy_child),
                'menu'              => AdminImageController::replaceImageInContentWithLoading($item->menu),
                'hotel'             => AdminImageController::replaceImageInContentWithLoading($item->hotel),
                'policy_cancel'     => AdminImageController::replaceImageInContentWithLoading($item->policy_cancel)
            ];
            TourContentForeign::updateItem($item->id, $params);
        }
    }
    /* reset tất cả checkOnpage đưa vào Job */
    public static function checkOnpageAll(){
        $seos   = Seo::select('id')
                    ->get();
        foreach($seos as $seo){
            CheckSeo::dispatch($seo->id);
        }
        return \Illuminate\Support\Facades\Redirect::to(route('main.home'), 301);
    }

    function readWebPage($url = null) {
        // $ch = curl_init();
        // // $url   = 'https://mytour.vn/khach-san/33364-khu-nghi-duong-melia-nui-ba-vi-(melia-bavi-mountain-retreat).html?checkIn=15-07-2023&checkOut=16-07-2023&adults=2&rooms=1&children=0';
        // $url    = 'https://www.booking.com/hotel/vn/meriton.vi.html';
        // // $url    = 'https://alonhadat.com.vn/lo-dat-5400m2-view-ho-goc-2-mat-tien-krong-bong-11785906.html';
        // $client = new Client();
        // $crawlerContent = $client->request('GET', $url);
        
        // /* các tiện ích của khách sạn */
        // // $crawlerContent->filter('.hp--popular_facilities div > div > div > div > div > span')->each(function($node) {
        // //     $this->arrayData[] = $node->html(); 
        // // });

        // // /* ảnh khách sạn */
        // // $crawlerContent->filter('.bh-photo-modal a')->each(function($node) {
        // //     $this->arrayData[] = $node->html(); 
        // // });

        // /* ảnh khách sạn */
        // $crawlerContent->filter('.property-section--content > div')->each(function($node) {
        //     $this->arrayData[] = $node->html(); 
        // });
        
        $i                  = 0;
        // Tạo một đối tượng Crawler từ HTML đã có
        $htmlContent        = view('admin.hotel.test')->render();
        $crawlerContent     = new Crawler($htmlContent);
        $htmlRoom           = view('admin.hotel.room')->render();
        $crawlerRoom        = new Crawler($htmlRoom);

        /* lấy giới thiệu khách sạn */
        $this->arrayData['info'][$i]['title']   = $crawlerContent->filter('#hotel_description > div > div > h2')->html();
        $this->arrayData['info'][$i]['content'] = $crawlerContent->filter('#hotel_description > div > div > div .MuiBox-root')->html();
        $i          += 1;

        /* lấy tiện ích chung phòng - dạng icon */
        $crawlerRoom->filter('#hprt-ws-lightbox-facilities-mapped > div > div > span')->each(function($node){
            /* ===== lấy facilities */
            /* lấy text */
            $this->arrayData['facilities'][$this->count]['text']                      = $node->text();
            /* lấy icon */
            $spanNode   = $node->filter('svg')->getNode(0);
            $spanDom    = $node->getNode(0)->ownerDocument->saveHTML($spanNode);
            if(!empty($spanDom)) $this->arrayData['facilities'][$this->count]['icon'] = trim($spanDom);
            $this->count += 1;
        });
        
        /* lấy hình ảnh phòng */
        $crawlerRoom->filter('.hp-gallery img')->each(function($node){
            if(!empty($node->attr('src'))) {
                $this->arrayData['images'][]   = $node->attr('src');
            }else if(!empty($node->attr('data-lazy'))){
                $this->arrayData['images'][]   = $node->attr('data-lazy');
            }
        });
        /* lấy mô tả tiện ích phòng */
        $this->count        = 0;
        $crawlerRoom->filter('.more-facilities-space h2')->each(function($node){
            if(!empty($node->filter('h2')->text())) $this->arrayData['details'][$this->count]['title'] = $node->filter('h2')->text();
            $this->count    += 1;
        });
        $this->count        = 0;
        $crawlerRoom->filter('.more-facilities-space ul')->each(function($node){
            if(!empty($node->html())) $this->arrayData['details'][$this->count]['content'] = '<ul>'.trim($node->html()).'</ul>';
            $this->count    += 1;
        });
        /* lấy số người tối đa của phòng */
        $numberPeople   = null;
        $tmp            = $crawlerRoom->filter('.tpi-hprt-lightbox-book-conditions__occupancy')->attr('title');
        if(!empty($tmp)){
            $pattern    = '/\d+/';
            preg_match($pattern, $tmp, $matches);
            if (!empty($matches)) $numberPeople = $matches[0];
        }
        $this->arrayData['number_people'] = $numberPeople;
        /* lấy giá phòng */
        $price          = null;
        $tmp            = $crawlerRoom->filter('.hprt-lightbox-book-price')->text();
        if(!empty($tmp)){
            $pattern    = '/\d+[,.]*\d*/';
            preg_match($pattern, $tmp, $matches);
            if (!empty($matches)) $price = $matches[0];
        }
        $price          = str_replace([',', '.'], ['', ''], $price);
        $this->arrayData['price']   = $price;
        /* lấy tên phòng */
        $this->arrayData['name']    = $crawlerRoom->filter('h1')->text();
        /* lấy tiện nghi chung của phòng */
        $this->count        = 0;
        $crawlerRoom->filter('.hprt-facilities-facility span')->each(function($node){
            if(!empty($node->text())) $this->arrayData['tmp'][$this->count]['name'] = $node->text();
            $this->count    += 1;
        });
        $this->count        = 0;
        $crawlerRoom->filter('.hprt-facilities-facility')->each(function($node){
            $spanNode       = $node->filter('svg')->getNode(0);
            $spanDom        = $node->getNode(0)->ownerDocument->saveHTML($spanNode);
            $this->arrayData['tmp'][$this->count]['icon'] = trim($spanDom);
            $this->count    += 1;
        });
        /* => tiến hành lọc qua xem nào chưa có trong bảng CSDL thì tạo ra */
        $allRoomFacilities  = HotelRoomFacility::all();
        $this->count        = 0;
        foreach($this->arrayData['tmp'] as $r){
            $flag           = false;
            $tmp            = new \Illuminate\Database\Eloquent\Collection;
            foreach($allRoomFacilities as $roomFacility){
                if($r['name']==$roomFacility->name){
                    $flag   =  true;
                    $tmp    = $roomFacility;
                    break;
                }
            }
            /* flag = true => facility này đã có trong cơ sở dữ liệu => lấy thông tin đưa vào mảng */
            if($flag==true){
                $this->arrayData['room_facilities'][$this->count]['id']     = $tmp->id;
                $this->arrayData['room_facilities'][$this->count]['name']   = $tmp->name;
                $this->arrayData['room_facilities'][$this->count]['icon']   = $tmp->icon;
            }else { /* flag = false => facility này đã chưa trong cơ sở dữ liệu => insert vào sau đó lấy thông tin đưa vào mảng */
                $idRoomFacility = HotelRoomFacility::insertItem([
                    'name'  => $r['name'],
                    'icon'  => $r['icon']
                ]);
                $this->arrayData['room_facilities'][$this->count]['id']     = $idRoomFacility;
                $this->arrayData['room_facilities'][$this->count]['name']   = $r['name'];
                $this->arrayData['room_facilities'][$this->count]['icon']   = $r['icon'];
            }
            $this->count    += 1;
        }

        
        
        

        // /* chi tiết phòng - số người - bữa sáng */
        // $this->arrayData['room']['condition']   = $crawlerRoom->filter('.hprt-lightbox-book-conditions > div')->html();

        dd($this->arrayData);

    }
    
}
