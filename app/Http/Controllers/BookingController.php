<?php

namespace App\Http\Controllers;

use App\Models\ShipLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller {

    public static function shipBookingForm(Request $request){
        $locations  = ShipLocation::select('id', 'province_id', 'district_id')
                        ->with('province', 'district')
                        ->get();
        return view('main.shipBooking.bookingForm', compact('locations'));
    }

    public static function handleShipBookingForm(Request $request){

        dd($request->all());
    }
}
