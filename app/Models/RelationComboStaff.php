<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationComboStaff extends Model {
    use HasFactory;
    protected $table        = 'relation_combo_staff';
    protected $fillable     = [
        'combo_info_id', 
        'staff_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationComboStaff();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idCombo, $arrayIdStaff){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idCombo)) {
            $countDeleted                   = self::select('*')->where('combo_info_id', $idCombo)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdStaff)){
            foreach($arrayIdStaff as $idStaff){
                $model                      = new RelationComboStaff();
                $model->combo_info_id       = $idCombo;
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
