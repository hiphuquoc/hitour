<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipTime extends Model {
    use HasFactory;
    protected $table        = 'ship_time';
    protected $fillable     = [
        'ship_price_id', 
        'time_departure',
        'time_arrive',
        'time_move'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new ShipTime();
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
