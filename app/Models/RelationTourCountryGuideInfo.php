<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourCountryGuideInfo extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_country_guide_info';
    protected $fillable     = [
        'tour_country_id', 
        'guide_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourCountryGuideInfo();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourCountry(){
        return $this->hasOne(\App\Models\TourCountry::class, 'id', 'tour_country_id');
    }

    public function infoGuide(){
        return $this->hasOne(\App\Models\Guide::class, 'id', 'guide_info_id');
    }
}
