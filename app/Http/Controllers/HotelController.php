<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Response;

class HotelController extends Controller {

    public function loadHotelRoom(Request $request){
        $result         = [];
        if(!empty($request->get('hotel_room_id'))){
            $room       = HotelRoom::select('*')
                            ->where('id', $request->get('hotel_room_id'))
                            ->with('images', 'facilities')
                            ->first();
            $result['row']      = view('main.hotel.oneRoom', compact('room'))->render();
            $result['modal']    = view('main.hotel.oneModalRoom', compact('room'))->render();
        }
        return json_encode($result);
    }
    
}
