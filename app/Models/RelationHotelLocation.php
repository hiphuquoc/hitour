<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationHotelLocation extends Model {
    use HasFactory;
    protected $table        = 'relation_hotel_location';
    protected $fillable     = [
        'hotel_info_id', 
        'hotel_location_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationHotelLocation();
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
            $countDeleted                   = self::select('*')->where('hotel_info_id', $idCombo)->delete();
        }
        /* insert */
        $countInsert                        = 0;
        if(!empty($arrayIdComboLocation)){
            foreach($arrayIdComboLocation as $idComboLocation){
                $model                      = new RelationHotelLocation();
                $model->hotel_info_id        = $idCombo;
                $model->hotel_location_id    = $idComboLocation;
                $model->save();
                $countInsert                += 1;
            }
        }
        $result['delete']                   = $countDeleted;
        $result['insert']                   = $countInsert;
        return $result;
    }

    public function infoLocation(){
        return $this->hasOne(\App\Models\ComboLocation::class, 'id', 'hotel_location_id');
    }

    public function infoHotel(){
        return $this->hasOne(\App\Models\Hotel::class, 'id', 'hotel_info_id');
    }
}
