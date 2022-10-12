<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationAirPartner extends Model {
    use HasFactory;
    protected $table        = 'relation_air_partner';
    protected $fillable     = [
        'air_info_id', 
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

    public static function deleteAndInsertItem($idAir, $arrayIdPartner){
        /* delete relation trước đó */
        $countDeleted                       = 0;
        if(!empty($idAir)) {
            $countDeleted                   = self::select('*')->where('air_info_id', $idAir)->delete();
        }
        /* insert */
        $countInsert                        = 0;
        if(!empty($arrayIdPartner)){
            foreach($arrayIdPartner as $idPartner){
                $model                      = new RelationAirPartner();
                $model->air_info_id         = $idAir;
                $model->partner_info_id     = $idPartner;
                $model->save();
                $countInsert                += 1;
            }
        }
        $result['delete']                   = $countDeleted;
        $result['insert']                   = $countInsert;
        return $result;
    }

    public function infoPartner(){
        return $this->hasOne(\App\Models\AirPartner::class, 'id', 'partner_info_id');
    }
}
