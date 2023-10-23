<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HotelBookingQuantityAndPrice extends Model {
    use HasFactory;
    protected $table        = 'hotel_booking_quantity_and_price';
    protected $fillable     = [
        'hotel_booking_id',
        'hotel_info_id',
        'hotel_room_id', 
        'hotel_price_id',
        'quantity',
        'check_in',
        'check_out',
        'number_night',
        'hotel_room_name',
        'hotel_room_size',
        'hotel_price_description',
        'hotel_price_number_people',
        'hotel_price_price',
        'hotel_price_price_old',
        'hotel_price_sale_off',
        'hotel_price_bed',
        'hotel_price_breakfast',
        'hotel_price_given'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new HotelBookingQuantityAndPrice();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
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

    public function hotel(){
        return $this->hasOne(\App\Models\Hotel::class, 'id', 'hotel_info_id');
    }

    public function room(){
        return $this->hasOne(\App\Models\HotelRoom::class, 'id', 'hotel_room_id');
    }

    public function price(){
        return $this->hasOne(\App\Models\HotelPrice::class, 'id', 'hotel_price_id');
    }
}
