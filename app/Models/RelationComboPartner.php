<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationComboPartner extends Model {
    use HasFactory;
    protected $table        = 'relation_combo_partner';
    protected $fillable     = [
        'combo_info_id', 
        'partner_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationComboPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idCombo, $arrayIdPartner){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idCombo)) {
            $countDeleted                   = self::select('*')->where('combo_info_id', $idCombo)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdPartner)){
            foreach($arrayIdPartner as $idPartner){
                $model                      = new RelationComboPartner();
                $model->combo_info_id       = $idCombo;
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
        return $this->hasOne(\App\Models\ComboPartner::class, 'id', 'partner_info_id');
    }

    public function infoCombo(){
        return $this->hasOne(\App\Models\Combo::class, 'id', 'combo_info_id');
    }
}
