<?php

namespace App\Http\Controllers;

use App\Models\HotelRoom;
use App\Models\HotelImage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Response;
use PDO;

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

    public function loadHotelImage(Request $request){
        $result['content'] = '';
        $loadPerTime    = 6;
        if(!empty($request->get('hotel_info_id'))){
            $total      = $request->get('total');
            $loaded     = $request->get('loaded');
            $images     = HotelImage::select('*')
                            ->where('reference_type', 'hotel_info')
                            ->where('reference_id', $request->get('hotel_info_id'))
                            ->skip($loaded)
                            ->take($loadPerTime)
                            ->get();
            $i          = 1;
            foreach($images as $image){
                $imageContent       = config('admin.images.default_750x460');
                $contentImage       = Storage::disk('gcs')->get($image->image);
                $resize             = 500;
                if($i==1||$i%2==0) $resize = 800;
                if(!empty($contentImage)){
                    $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize($resize, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();
                    $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                }
                $result['content']  .= '<div class="hotelImageTab_body_tab_item">
                                            <img src="'.$imageContent.'" />
                                        </div>';
                ++$i;
            }
            $result['total']    = $total;
            $result['loaded']   = $loaded + $images->count();
        }
        return json_encode($result);
    }
    
}
