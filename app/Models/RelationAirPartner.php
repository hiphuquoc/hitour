<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationAirPartner extends Model {
    use HasFactory;
    protected $table        = 'relation_ship_partner';
    protected $fillable     = [
        'ship_info_id', 
        'partner_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationAirPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoPartner(){
        return $this->hasOne(\App\Models\AirPartner::class, 'id', 'partner_info_id');
    }
}
