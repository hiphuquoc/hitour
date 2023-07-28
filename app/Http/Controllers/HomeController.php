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
use Intervention\Image\ImageManagerStatic;

use App\Jobs\CheckSeo;
use App\Models\Redirect;

use Goutte\Client;
use Illuminate\Support\Facades\File;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Support\Facades\Response;

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
            $xhtml              = view('main.home.home', compact('item', 'shipLocations', 'airLocations', 'serviceLocations', 'islandLocations', 'specialLocations', 'shipPartners', 'airPartners'))->render();
            if(env('APP_CACHE_HTML')==true) Storage::put(config('main.cache.folderSave').$nameCache, $xhtml);
        }
        echo $xhtml;
    }

    /* ===== tiện nghi thay tất cả các ảnh hỗ trợ Loading ===== */
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
        $chuoiNgayThang = "thg 7 2018";
        // Tách các phần tử trong chuỗi bằng khoảng trắng
        $mangChuoi      = explode(" ", $chuoiNgayThang);
        // Xác định tháng dựa trên từ viết tắt
        $thang          = 0;
        $tungay         = array("thg", " ", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
        $denngay        = array("", "", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
        $mangChuoi[1]   = str_replace($tungay, $denngay, strtolower($mangChuoi[1]));
        $thang          = (int)$mangChuoi[1];
        // Tạo định dạng ngày tháng
        $ngayThangPHP = sprintf("%d-%02d-%02d", (int)$mangChuoi[2], $thang, 01);
    }

    private function getComment($url, $number, $count){
        if(!empty($url)){
            // Tạo đối tượng Client của Goutte
            $client         = new Client();
            $everyTime      = 5;
            // Gửi yêu cầu GET đến URL cần lấy dữ liệu
            $url            = explode('Reviews', $url);
            $url            = implode('Reviews-or'.$number, $url);
            $crawlerContent = $client->request('GET', $url);
            /* lấy comment */
            $this->count    = $count;
            if($crawlerContent->filter('[data-test-target=reviews-tab] .YibKl')->count()>0){
                $crawlerContent->filter('[data-test-target=reviews-tab] .YibKl')->each(function($node){
                    /* số sao */
                    $number         = preg_replace("/[^0-9]/", '', $node->filter('[data-test-target=review-rating] > span')->attr('class'));
                    $this->arrayData['comments'][$this->count]['stars']     = $number/10;
                    /* người đánh giá + lúc đánh giá */
                    $this->arrayData['comments'][$this->count]['person']    = $node->filter('.cRVSd')->text();
                    /* tiêu đề đánh giá */
                    $this->arrayData['comments'][$this->count]['title']     = $node->filter('.Qwuub')->text();
                    /* nội dung đánh giá */
                    $this->arrayData['comments'][$this->count]['content']   = $node->filter('.fIrGe')->text();
    
                    $this->count    += 1;
                });
                $this->getComment($url, ($number + $everyTime), $this->count);
            }else {
                return true;
            }
        }
        return true;
    }
    
}
