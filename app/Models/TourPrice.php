<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model {
    use HasFactory;
    protected $table        = 'tour_price';
    protected $fillable     = [
        'tour_option_id',
        'apply_age', 
        'price',
        'profit',
        'date_start',
        'date_end'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new TourPrice();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }
}
