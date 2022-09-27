<?php

namespace App\Http\Controllers;
use App\Models\Tour;
use App\Models\TourLocation;
use Illuminate\Http\Request;

use App\Services\BuildInsertUpdateModel;

class TourBookingController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function form(Request $request){
        $tourLocations  = TourLocation::select('*')
                            ->with('region')
                            ->get();
        return view('main.tourBooking.form', compact('tourLocations'));
    }

    public static function loadTour(Request $request){
        $result                 = null;
        if(!empty($request->get('tour_location_id'))){
            $idTourLocation     = $request->get('tour_location_id');
            $data               = Tour::select('*')
                                    ->whereHas('locations.infoLocation', function($query) use($idTourLocation){
                                        $query->where('id', $idTourLocation);
                                    })
                                    ->get();
            $result             = view('main.shipBooking.selectboxLocation', compact('data'));
        }
        return $result;
    }

    
}
