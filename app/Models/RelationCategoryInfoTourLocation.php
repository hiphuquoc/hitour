<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationCategoryInfoTourLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_category_info_tour_location';
    protected $fillable     = [
        'category_info_id', 
        'tour_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationCategoryInfoTourLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoCategory(){
        return $this->hasOne(\App\Models\Category::class, 'id', 'category_info_id');
    }

    public function infoTourLocation(){
        return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    }
}
