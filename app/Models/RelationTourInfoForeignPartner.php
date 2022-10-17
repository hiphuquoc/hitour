<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourInfoForeignPartner extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_info_foreign_partner';
    protected $fillable     = [
        'tour_info_foreign_id', 
        'partner_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourInfoForeignPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoTourContinent(){
        return $this->hasOne(\App\Models\TourInfoForeign::class, 'id', 'tour_info_foreign_id');
    }

    public function infoPartner(){
        return $this->hasOne(\App\Models\TourPartner::class, 'id', 'partner_info_id');
    }
}
