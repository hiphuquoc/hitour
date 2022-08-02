<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationTourLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_tour_location';
    protected $fillable     = [
        'tour_info_id', 
        'tour_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationTourLocation();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idTour, $arrayIdTourLocation){
        // delete relation trước đó
        $countDeleted                       = 0;
        if(!empty($idTour)) {
            $countDeleted                   = RelationTourLocation::select('*')->where('tour_info_id', $idTour)->delete();
        }
        // insert
        $countInsert                        = 0;
        if(!empty($arrayIdTourLocation)){
            foreach($arrayIdTourLocation as $idTourLocation){
                $model                      = new RelationTourLocation();
                $model->tour_info_id        = $idTour;
                $model->tour_location_id    = $idTourLocation;
                $model->save();
                $countInsert                += 1;
            }
        }
        $result['delete']                   = $countDeleted;
        $result['insert']                   = $countInsert;
        return $result;
    }

    public function infoLocation(){
        return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    }
}
