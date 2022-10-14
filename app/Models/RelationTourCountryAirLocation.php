<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourCountryAirLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_country_air_location';
    protected $fillable     = [
        'tour_country_id', 
        'air_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourCountryAirLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourCountry(){
        return $this->hasOne(\App\Models\TourCountry::class, 'id', 'tour_country_id');
    }

    public function infoAirLocation(){
        return $this->hasOne(\App\Models\AirLocation::class, 'id', 'air_location_id');
    }
}
