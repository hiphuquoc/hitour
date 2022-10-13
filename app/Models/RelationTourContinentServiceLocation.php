<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourContinentServiceLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_continent_service_location';
    protected $fillable     = [
        'tour_continent_id', 
        'service_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourContinentServiceLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourContinent(){
        return $this->hasOne(\App\Models\TourContinent::class, 'id', 'tour_continent_id');
    }

    public function infoServiceLocation(){
        return $this->hasOne(\App\Models\ServiceLocation::class, 'id', 'service_location_id');
    }
}
