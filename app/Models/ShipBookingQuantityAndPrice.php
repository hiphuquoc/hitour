<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBookingQuantityAndPrice extends Model {
    use HasFactory;
    protected $table        = 'ship_booking_quantity_and_price';
    protected $fillable     = [
        'ship_booking_id', 
        'time_departure',
        'time_arrive',
        'date',
        'port_departure',
        'port_departure_address',
        'port_departure_district',
        'port_departure_province',
        'port_location',
        'departure',
        'location',
        'quantity_adult',
        'quantity_child',
        'quantity_old',
        'partner_name',
        'price_adult',
        'price_child',
        'price_old',
        'type'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new ShipBookingQuantityAndPrice();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag               = false;
        if(!empty($id)&&!empty($params)){
            $model          = self::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag           = $model->update();
        }
        return $flag;
    }
}
