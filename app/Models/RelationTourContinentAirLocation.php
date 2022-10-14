<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourContinentAirLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_continent_air_location';
    protected $fillable     = [
        'tour_continent_id', 
        'air_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourContinentAirLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourContinent(){
        return $this->hasOne(\App\Models\TourContinent::class, 'id', 'tour_continent_id');
    }

    public function infoAirLocation(){
        return $this->hasOne(\App\Models\AirLocation::class, 'id', 'air_location_id');
    }
}
