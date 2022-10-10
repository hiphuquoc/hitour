<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourLocationServiceLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_location_service_location';
    protected $fillable     = [
        'tour_location_id', 
        'service_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourLocationServiceLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourLocation(){
        return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    }

    public function infoServiceLocation(){
        return $this->hasOne(\App\Models\ServiceLocation::class, 'id', 'service_location_id');
    }
}
