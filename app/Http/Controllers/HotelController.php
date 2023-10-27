<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\HotelPrice;
use App\Models\HotelImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HotelController extends Controller {

    public function loadHotelInfo(Request $request){
        $result                 = [];
        if(!empty($request->get('hotel_info_id'))){
            $hotel              = Hotel::select('*')
                                    ->where('id', $request->get('hotel_info_id'))
                                    ->with('rooms', 'facilities')
                                    ->first();
            $result             = view('main.hotelLocation.oneHotel', compact('hotel'))->render();
        }
        return json_encode($result);
    }

    public function loadHotelPrice(Request $request){
        $result                 = [];
        if(!empty($request->get('hotel_price_id'))){
            $price              = HotelPrice::select('*')
                                    ->where('id', $request->get('hotel_price_id'))
                                    ->with('room.images', 'room.facilities')
                                    ->first();
            $result['row']      = view('main.hotel.oneRoom', compact('price'))->render();
            $result['modal']    = view('main.hotel.oneModalRoom', compact('price'))->render();
        }
        return json_encode($result);
    }

    public function loadHotelImage(Request $request){
        $result         = '';
        if(!empty($request->get('hotel_info_id'))){
            $hotel      = Hotel::select('*')
                            ->where('id', $request->get('hotel_info_id'))
                            ->with('images')
                            ->first();
            foreach($hotel->images as $image){
                $result .= '<div class="hotelImageTab_body_tab_item">
                                <img src="'.config('main.svg.loading_main_nobg').'" data-google-cloud="'.$image->image.'" data-size="'.$hotel->name.'" alt="'.$hotel->name.'" title="'.$hotel->name.'" />
                            </div>';
            }
        }
        echo $result;
    }
    
}
