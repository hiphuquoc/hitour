<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboPrice extends Model {
    use HasFactory;
    protected $table        = 'combo_price';
    protected $fillable     = [
        'departure_id',
        'combo_option_id',
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
            $model          = new ComboPrice();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }

    public function departure() {
        return $this->hasOne(\App\Models\TourDeparture::class, 'id', 'departure_id');
    }
}
