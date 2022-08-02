<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TourBookingQuantityAndPrice extends Model {
    use HasFactory;
    protected $table        = 'tour_booking_quantity_and_price';
    protected $fillable     = [
        'tour_booking_id', 
        'option_name',
        'option_age',
        'quantity',
        'price'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourBookingQuantityAndPrice();
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

    public static function getListByTourBookingId($idBooking){
        $result         = [];
        if(!empty($idBooking)){
            $result     = self::select('*')
                            ->where('tour_booking_id', $idBooking)
                            ->get();
        }
        return $result;
    }
}
