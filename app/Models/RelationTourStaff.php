<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourStaff extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_staff';
    protected $fillable     = [
        'tour_info_id', 
        'staff_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourStaff();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idTour, $arrayIdStaff){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idTour)) {
            $countDeleted                   = RelationTourStaff::select('*')->where('tour_info_id', $idTour)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdStaff)){
            foreach($arrayIdStaff as $idStaff){
                $model                      = new RelationTourStaff();
                $model->tour_info_id        = $idTour;
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
