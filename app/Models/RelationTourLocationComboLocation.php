<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourLocationComboLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_location_combo_location';
    protected $fillable     = [
        'tour_location_id', 
        'combo_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourLocationComboLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourLocation(){
        return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    }

    public function infoComboLocation(){
        return $this->hasOne(\App\Models\ComboLocation::class, 'id', 'combo_location_id');
    }
}
