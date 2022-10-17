<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourInfoForeignTourCountry extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_info_foreign_tour_country';
    protected $fillable     = [
        'tour_info_foreign_id', 
        'tour_country_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourInfoForeignTourCountry();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourInfoForeign(){
        return $this->hasOne(\App\Models\TourInfoForeign::class, 'id', 'tour_info_foreign_id');
    }

    public function infoCountry(){
        return $this->hasOne(\App\Models\TourCountry::class, 'id', 'tour_country_id');
    }
}
