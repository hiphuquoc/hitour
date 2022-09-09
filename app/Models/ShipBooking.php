<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBooking extends Model {
    use HasFactory;
    protected $table        = 'ship_booking';
    protected $fillable     = [
        'no', 
        'customer_info_id',
        'ship_booking_status_id',
        'paid',
        'note_customer',
        'created_by',
        'expiration_at'
    ];
    public $timestamps      = true;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new ShipBooking();
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

    public function infoDeparture() {
        return $this->hasMany(\App\Models\ShipBookingQuantityAndPrice::class, 'ship_booking_id', 'id');
    }

    public function customer() {
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_info_id');
    }
}
