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
        return view('main.home.home', compact('item', 'shipLocations', 'airLocations', 'serviceLocations', 'specialLocations', 'shipPartners', 'airPartners'));
    }
}
