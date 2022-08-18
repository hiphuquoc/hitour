<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipPrice extends Model {
    use HasFactory;
    protected $table        = 'ship_price';
    protected $fillable     = [
        'ship_info_id', 
        'ship_partner_id',
        'date_start',
        'date_end',
        'price_adult',
        'price_child',
        'price_old',
        'price_vip',
        'profit_percent'
    ];
    public $timestamps      = true;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new ShipPrice();
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
