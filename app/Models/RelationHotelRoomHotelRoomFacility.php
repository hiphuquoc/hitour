<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationHotelRoomHotelRoomFacility extends Model {
    use HasFactory;
    protected $table        = 'relation_hotel_room_hotel_room_facility';
    protected $fillable     = [
        'hotel_room_id', 
        'hotel_room_facility_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationHotelRoomHotelRoomFacility();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoHotelRoom(){
        return $this->hasOne(\App\Models\HotelRoom::class, 'id', 'hotel_room_id');
    }

    public function infoHotelRoomFacility(){
        return $this->hasOne(\App\Models\HotelRoomFacility::class, 'id', 'hotel_room_facility_id');
    }
}
