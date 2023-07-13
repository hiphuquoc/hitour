<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRoom extends Model {
    use HasFactory;
    protected $table        = 'hotel_room';
    protected $fillable     = [
        'hotel_info_id',
        'name', 
        'condition',
        'size',
        'number_people',
        'price',
        'note'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new HotelRoom();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = self::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }

    public function facilities() {
        return $this->hasMany(\App\Models\RelationHotelRoomHotelRoomFacility::class, 'hotel_room_id', 'id');
    }

    public function details() {
        return $this->hasMany(\App\Models\HotelRoomDetail::class, 'hotel_room_id', 'id');
    }

    public function images(){
        return $this->hasMany(\App\Models\HotelImage::class, 'reference_id', 'id')->where('reference_type', 'hotel_room');
    }
}
