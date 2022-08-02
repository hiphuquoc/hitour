<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourPartner extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_partner';
    protected $fillable     = [
        'tour_info_id', 
        'partner_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourPartner();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idTour, $arrayIdPartner){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idTour)) {
            $countDeleted                   = RelationTourPartner::select('*')->where('tour_info_id', $idTour)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdPartner)){
            foreach($arrayIdPartner as $idPartner){
                $model                      = new RelationTourPartner();
                $model->tour_info_id        = $idTour;
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
        return $this->hasOne(\App\Models\Partner::class, 'id', 'partner_info_id');
    }
}
