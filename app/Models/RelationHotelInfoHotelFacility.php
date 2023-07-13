<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationHotelInfoHotelFacility extends Model {
    use HasFactory;
    protected $table        = 'relation_hotel_info_hotel_facility';
    protected $fillable     = [
        'hotel_info_id', 
        'hotel_facility_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationHotelInfoHotelFacility();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoHotel(){
        return $this->hasOne(\App\Models\HotelInfo::class, 'id', 'hotel_info_id');
    }

    public function infoFacility(){
        return $this->hasOne(\App\Models\HotelFacility::class, 'id', 'hotel_facility_id');
    }
}
