<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPrice extends Model {
    use HasFactory;
    protected $table        = 'hotel_price';
    protected $fillable     = [
        'hotel_room_id',
        'description', 
        'number_people',
        'price',
        'price_old',
        'sale_off'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new HotelPrice();
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

    public function room() {
        return $this->hasOne(\App\Models\HotelRoom::class, 'id', 'hotel_room_id');
    }
}
