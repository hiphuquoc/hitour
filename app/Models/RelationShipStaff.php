<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationShipStaff extends Model {
    use HasFactory;
    protected $table        = 'relation_ship_staff';
    protected $fillable     = [
        'ship_info_id', 
        'staff_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationShipStaff();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idShip, $arrayIdStaff){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idShip)) {
            $countDeleted                   = self::select('*')->where('ship_info_id', $idShip)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdStaff)){
            foreach($arrayIdStaff as $idStaff){
                $model                      = new RelationShipStaff();
                $model->ship_info_id        = $idShip;
                $model->staff_info_id       = $idStaff;
                $model->save();
                $countInsert                += 1;
            }
        }
        $result['delete']                   = $countDeleted;
        $result['insert']                   = $countInsert;
        return $result;
    }

    public function infoStaff(){
        return $this->hasOne(\App\Models\Staff::class, 'id', 'staff_info_id');
    }
}
