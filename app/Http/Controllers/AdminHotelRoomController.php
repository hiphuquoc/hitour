<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BuildInsertUpdateModel;
use App\Models\Hotel;
use App\Models\HotelFacility;
use App\Models\HotelRoom;
use App\Models\HotelImage;
use App\Models\HotelRoomDetail;
use App\Models\HotelRoomFacility;
use App\Models\RelationHotelRoomHotelRoomFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// use Goutte\Client;
// use Symfony\Component\BrowserKit\CookieJar;
// use Symfony\Component\BrowserKit\Cookie;
// use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class AdminHotelRoomController extends Controller {
    private $arrayData;
    private $count;

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function loadFormHotelRoom(Request $request){
        $data                           = [];
        if(!empty($request->get('hotel_room_id'))){
            $data                       = HotelRoom::select('*')
                                            ->where('id', $request->get('hotel_room_id'))
                                            ->with('facilities', 'details', 'images')
                                            ->first();
            $result['head']             = 'Chỉnh sửa Phòng';
        }else {
            $result['head']             = 'Thêm mới Phòng';
        }
        /* lấy tất cả room facilities */
        $roomFacilities                 = HotelRoomFacility::all();
        /* viết lại array images */
        $result['body']                 = view('admin.hotel.formHotelRoom', compact('data', 'roomFacilities'))->render();
        return $result;
    }

    public function downloadHotelRoom(Request $request){
        $result     = null;
        if(!empty($request->get('data'))){
            $data               = $request->get('data');
            $crawlerRoom        = new Crawler($data);
            /* lấy hình ảnh phòng */
            $crawlerRoom->filter('.slick-track img')->each(function($node){
                if(!empty($node->attr('src'))) {
                    $this->arrayData['images'][]   = $node->attr('src');
                }else if(!empty($node->attr('data-lazy'))){
                    $this->arrayData['images'][]   = $node->attr('data-lazy');
                }
            });
            /* lấy mô tả tiện ích phòng */
            $this->count        = 0;
            $crawlerRoom->filter('.more-facilities-space h2')->each(function($node){
                if(!empty($node->filter('h2')->text())) $this->arrayData['details'][$this->count]['name'] = $node->filter('h2')->text();
                $this->count    += 1;
            });
            $this->count = 0;
            $crawlerRoom->filter('.more-facilities-space ul')->each(function($node){
                if(!empty($node->html())) $this->arrayData['details'][$this->count]['detail'] = '<ul>'.trim($node->html()).'</ul>';
                $this->count    += 1;
            });
            /* lấy số người tối đa của phòng */
            $numberPeople   = null;
            $tmp            = $crawlerRoom->filter('.tpi-hprt-lightbox-book-conditions__occupancy')->count()>0 ? $crawlerRoom->filter('.tpi-hprt-lightbox-book-conditions__occupancy')->attr('title') : null;
            if(!empty($tmp)){
                $pattern    = '/\d+/';
                preg_match($pattern, $tmp, $matches);
                if (!empty($matches)) $numberPeople = $matches[0];
            }
            $this->arrayData['number_people'] = $numberPeople;
            /* lấy giá phòng */
            $price          = null;
            $tmp            = $crawlerRoom->filter('.hprt-lightbox-book-price')->count()>0 ? $crawlerRoom->filter('.hprt-lightbox-book-price')->text() : null;
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
                if($node->html()!=null){ /* sửa lỗi có class trống */
                    $spanNode       = $node->filter('svg')->getNode(0);
                    $spanDom        = $node->getNode(0)->ownerDocument->saveHTML($spanNode);
                    $this->arrayData['tmp'][$this->count]['icon'] = trim($spanDom);
                    $this->count    += 1;
                }
            });
            /* => tiến hành lọc qua xem nào chưa có trong bảng CSDL thì tạo ra */
            $allRoomFacilities  = HotelRoomFacility::all();
            
            foreach($this->arrayData['tmp'] as $r){
                $flag           = false;
                foreach($allRoomFacilities as $roomFacility){
                    /* facility này đã có trong cơ sở dữ liệu => lấy id đưa vào mảng */
                    if($r['name']==$roomFacility->name){
                        $flag   =  true;
                        $this->arrayData['facilities'][] = $roomFacility->id;
                        break;
                    }
                }
                /* flag = false => facility này chưa có trong cơ sở dữ liệu => insert vào sau đó lấy id đưa vào mảng */
                if($flag==false){
                    $idRoomFacility = HotelRoomFacility::insertItem([
                        'name'  => $r['name'],
                        'icon'  => $r['icon']
                    ]);
                    $this->arrayData['facilities'][]     = $idRoomFacility;
                }
            }
            
            $this->arrayData['facilities'] = HotelRoomFacility::select('*')
                                                ->whereIn('id', $this->arrayData['facilities'])
                                                ->get();
            /* truyền vào form */
            $roomFacilities     = HotelRoomFacility::all();
            $result = view('admin.hotel.formHotelRoomPart2', [
                'data'              => $this->arrayData,
                'roomFacilities'    => $roomFacilities
            ])->render();
        }
        echo $result;
    }

    public function create(Request $request){
        $flag                           = false;
        try {
            DB::beginTransaction();
            $idHotelInfo                = $request->get('hotel_info_id');
            $dataForm                   = $request->get('dataForm');
            /* insert hotel_room */
            $hotelRoom                  = $this->BuildInsertUpdateModel->buildArrayTableHotelRoom($dataForm, $idHotelInfo);
            $idHotelRoom                = HotelRoom::insertItem($hotelRoom);
            /* lưu ảnh vào cơ sở dữ liệu */
            if(!empty($dataForm['images'])){
                $imageName              = \App\Helpers\Charactor::convertStrToUrl($dataForm['name']);
                AdminHotelInfoController::saveImage($imageName, $idHotelRoom, $dataForm['images'], 'hotel_room');
            }
            /* insert relation_hotel_room_hotel_room_facility */
            if(!empty($dataForm['facilities'])){
                foreach($dataForm['facilities'] as $idRoomFacility){
                    RelationHotelRoomHotelRoomFacility::insertItem([
                        'hotel_room_id'             => $idHotelRoom, 
                        'hotel_room_facility_id'    => $idRoomFacility
                    ]);
                }
            }
            /* insert hotel_room_details */
            if(!empty($dataForm['details'])){
                foreach($dataForm['details'] as $roomDetail){
                    HotelRoomDetail::insertItem([
                        'hotel_room_id' => $idHotelRoom,
                        'name'          => $roomDetail['name'],
                        'detail'        => $roomDetail['detail']
                    ]);
                }
            }
            DB::commit();
            if(!empty($idHotelRoom)) $flag = true;
        } catch (\Exception $exception){
            DB::rollBack();
        }
        $response['status']     = $flag;
        $response['content']    = self::getHtmlHotelRoom($idHotelRoom);
        return json_encode($response);
    }

    public function update(Request $request){
        $flag                           = false;
        try {
            DB::beginTransaction();
            $idHotelInfo                = $request->get('hotel_info_id');
            $dataForm                   = $request->get('dataForm');
            $idHotelRoom                = $dataForm['hotel_room_id'];
            /* update hotel_room */
            $hotelRoom                  = $this->BuildInsertUpdateModel->buildArrayTableHotelRoom($dataForm, $idHotelInfo);
            HotelRoom::updateItem($idHotelRoom, $hotelRoom);
            /* update relation_hotel_room_hotel_room_facility */
            RelationHotelRoomHotelRoomFacility::select('*')
                ->where('hotel_room_id', $idHotelRoom)
                ->delete();
            if(!empty($dataForm['facilities'])){
                foreach($dataForm['facilities'] as $idRoomFacility){
                    RelationHotelRoomHotelRoomFacility::insertItem([
                        'hotel_room_id'             => $idHotelRoom, 
                        'hotel_room_facility_id'    => $idRoomFacility
                    ]);
                }
            }
            /* update hotel_room_details */
            HotelRoomDetail::select('*')
                ->where('hotel_room_id', $idHotelRoom)
                ->delete();
            if(!empty($dataForm['details'])){
                foreach($dataForm['details'] as $roomDetail){
                    HotelRoomDetail::insertItem([
                        'hotel_room_id' => $idHotelRoom,
                        'name'          => $roomDetail['name'],
                        'detail'        => $roomDetail['detail']
                    ]);
                }
            }
            /* Message */
            if(!empty($idHotelRoom)) $flag = true;
            DB::commit();
            return true;
        } catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
        echo $flag;
    }

    public static function delete(Request $request){
        $flag               = false;
        try {
            DB::beginTransaction();
            $idHotelRoom    = $request->get('id');
            $infoHotelRoom  = HotelRoom::select('*')
                                ->where('id', $idHotelRoom)
                                ->with('facilities', 'details', 'images')
                                ->first();
            /* xóa ảnh trong storage */
            foreach($infoHotelRoom->images as $image) self::deleteHotelImage($image->id);
            /* xóa các relation */
            $infoHotelRoom->facilities()->delete();
            $infoHotelRoom->details()->delete();
            /* xóa hotel room */
            $infoHotelRoom->delete();
            $flag   = true;
            DB::commit();
            return true;
        } catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
        return $flag;
    }

    public static function deleteById($idHotelRoom){
        $flag               = false;
        try {
            DB::beginTransaction();
            $infoHotelRoom  = HotelRoom::select('*')
                                ->where('id', $idHotelRoom)
                                ->with('facilities', 'details', 'images')
                                ->first();
            /* xóa ảnh trong storage và dữ liệu trên database */
            foreach($infoHotelRoom->images as $image) self::deleteHotelImage($image->id);
            /* xóa các relation */
            $infoHotelRoom->facilities()->delete();
            $infoHotelRoom->details()->delete();
            /* xóa hotel room */
            $infoHotelRoom->delete();
            $flag   = true;
            DB::commit();
            return true;
        } catch (\Exception $exception){
            DB::rollBack();
            return false;
        }
        return $flag;
    }

    public static function deleteHotelImage($idHotelImage = null){
        $flag               = false;
        if(!empty($idHotelImage)){
            $infoHotelImage = HotelImage::select('*')
                                ->where('id', $idHotelImage)
                                ->first();
            /* xóa ảnh trên cloud */
            $flag           = Storage::disk('gcs')->delete($infoHotelImage->image);
            if($flag) $infoHotelImage->delete();
        }
        return $flag;
    }

    public function loadHotelRoom(Request $request){
        $result     = self::getHtmlHotelRoom($request->get('hotel_room_id'));
        echo $result;
    }

    private static function getHtmlHotelRoom($idHotelRoom = 0){
        $result = null;
        $item   = HotelRoom::select('*')
                        ->where('id', $idHotelRoom)
                        ->first();
        if(!empty($item)) $result = view('admin.hotel.oneRowHotelRoom', compact('item'))->render();
        return $result;
    }

    public function loadOptionHotelRoomByIdHotel(Request $request){
        $response           = '<option value="0">- Vui lòng chọn -</option>';
        if(!empty($request->get('hotel_info_id'))){
            
            $hotelRooms     = HotelRoom::select('*')
                                ->where('hotel_info_id', $request->get('hotel_info_id'))
                                ->get();
            $idHotelRoom    = $request->get('hotel_room_id') ?? 0;
            foreach($hotelRooms as $hotelRoom){
                $selected   = '';
                if($idHotelRoom==$hotelRoom->id) $selected = 'selected';
                $response   .= '<option value="'.$hotelRoom->id.'" '.$selected.'>'.$hotelRoom->name.'</option>';
            }
        }
        return $response;
    }
}
