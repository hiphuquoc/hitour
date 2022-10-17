<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourInfoForeignStaff extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_info_foreign_staff';
    protected $fillable     = [
        'tour_info_foreign_id', 
        'staff_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourInfoForeignStaff();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourContinent(){
        return $this->hasOne(\App\Models\TourInfoForeign::class, 'id', 'tour_info_foreign_id');
    }

    public function infoStaff(){
        return $this->hasOne(\App\Models\Staff::class, 'id', 'staff_info_id');
    }
}
