<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationComboLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_combo_location';
    protected $fillable     = [
        'combo_info_id', 
        'combo_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationComboLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idCombo, $arrayIdComboLocation){
        /* delete relation trước đó */
        $countDeleted                       = 0;
        if(!empty($idCombo)) {
            $countDeleted                   = self::select('*')->where('combo_info_id', $idCombo)->delete();
        }
        /* insert */
        $countInsert                        = 0;
        if(!empty($arrayIdComboLocation)){
            foreach($arrayIdComboLocation as $idComboLocation){
                $model                      = new RelationComboLocation();
                $model->combo_info_id        = $idCombo;
                $model->combo_location_id    = $idComboLocation;
                $model->save();
                $countInsert                += 1;
            }
        }
        $result['delete']                   = $countDeleted;
        $result['insert']                   = $countInsert;
        return $result;
    }

    public function infoLocation(){
        return $this->hasOne(\App\Models\ComboLocation::class, 'id', 'combo_location_id');
    }

    public function infoCombo(){
        return $this->hasOne(\App\Models\Combo::class, 'id', 'combo_info_id');
    }
}
