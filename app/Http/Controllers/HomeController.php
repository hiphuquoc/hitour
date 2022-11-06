<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ShipLocation;
use App\Models\AirLocation;
use App\Models\ServiceLocation;
use App\Models\TourLocation;
use App\Models\ShipPartner;
use App\Models\AirPartner;
use App\Models\Seo;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller {

    public function home(){
        $item               = Seo::select('*')
                                ->where('slug', 'tour-du-lich-phu-quoc')
                                ->first();
        $shipLocations      = ShipLocation::select('*')
                                ->with('seo')
                                ->get();
        $airLocations       = AirLocation::select('*')
                                ->with('seo')
                                ->get();
        $serviceLocations   = ServiceLocation::select('*')
                                ->where('district_id', '!=', '0') /* vé giải trí trong nước */
                                ->with('seo')
                                ->get();
        $islandLocations    = TourLocation::select('*')
                                ->where('island', '1')
                                ->with('seo')
                                ->get();
        $specialLocations   = TourLocation::select('*')
                                ->where('island', '1')
                                ->with('seo')
                                ->get();
        $shipPartners       = ShipPartner::select('*')
                                ->with('seo')
                                ->get();
        $airPartners        = AirPartner::select('*')
                                ->with('seo')
                                ->get();
        return view('main.home.home', compact('item', 'shipLocations', 'airLocations', 'serviceLocations', 'islandLocations', 'specialLocations', 'shipPartners', 'airPartners'));
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
            $content        = \App\Http\Controllers\AdminImageController::replaceImageInContentWithLoading($content);
            return file_put_contents($fileName, $content);
        }
        return false;
    }
    
}
