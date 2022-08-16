<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationShipPartner extends Model {
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
            $model      = new RelationShipPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idShip, $arrayIdPartner){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idShip)) {
            $countDeleted                   = self::select('*')->where('ship_info_id', $idShip)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdPartner)){
            foreach($arrayIdPartner as $idPartner){
                $model                      = new RelationShipPartner();
                $model->ship_info_id        = $idShip;
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
        return $this->hasOne(\App\Models\ShipPartner::class, 'id', 'partner_info_id');
    }
}
