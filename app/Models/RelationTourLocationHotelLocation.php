<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourLocationHotelLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_location_hotel_location';
    protected $fillable     = [
        'tour_location_id', 
        'hotel_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourLocationHotelLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourLocation(){
        return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    }

    public function infoHotelLocation(){
        return $this->hasOne(\App\Models\HotelLocation::class, 'id', 'hotel_location_id');
    }
}
