<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourLocationGuide extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_guide';
    protected $fillable     = [
        'tour_location_id', 
        'guide_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourLocationGuide();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoGuide() {
        return $this->hasOne(\App\Models\Guide::class, 'id', 'guide_info_id');
    }

    public function infoTourLocation() {
        return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    }
}
