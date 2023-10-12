<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationHotelPriceHotelBed extends Model {
    use HasFactory;
    protected $table        = 'relation_hotel_price_hotel_bed';
    protected $fillable     = [
        'hotel_price_id', 
        'hotel_bed_id',
        'quantity'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationHotelPriceHotelBed();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoHotelPrice(){
        return $this->hasOne(\App\Models\HotelPrice::class, 'id', 'hotel_price_id');
    }

    public function infoHotelBed(){
        return $this->hasOne(\App\Models\HotelBed::class, 'id', 'hotel_bed_id');
    }
}
