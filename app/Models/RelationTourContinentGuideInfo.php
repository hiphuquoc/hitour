<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourContinentGuideInfo extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_continent_guide_info';
    protected $fillable     = [
        'tour_continent_id', 
        'guide_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourContinentGuideInfo();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourContinent(){
        return $this->hasOne(\App\Models\TourContinent::class, 'id', 'tour_continent_id');
    }

    public function infoGuide(){
        return $this->hasOne(\App\Models\Guide::class, 'id', 'guide_info_id');
    }
}
