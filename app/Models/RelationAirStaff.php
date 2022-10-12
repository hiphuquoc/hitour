<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationAirStaff extends Model {
    use HasFactory;
    protected $table        = 'relation_air_staff';
    protected $fillable     = [
        'air_info_id', 
        'staff_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationAirStaff();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idAir, $arrayIdStaff){
        /* delete relation trước đó */
        $countDeleted                       = 0;
        if(!empty($idAir)) {
            $countDeleted                   = self::select('*')->where('air_info_id', $idAir)->delete();
        }
        /* insert */
        $countInsert                        = 0;
        if(!empty($arrayIdStaff)){
            foreach($arrayIdStaff as $idStaff){
                $model                      = new RelationAirStaff();
                $model->air_info_id         = $idAir;
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
