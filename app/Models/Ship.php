<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model {
    use HasFactory;
    protected $table        = 'ship_info';
    protected $fillable     = [
        'seo_id', 
        'name',
        'name_round',
        'ship_location_id',
        'ship_departure_id',
        'note',
        'created_by'
    ];
    public $timestamps      = true;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new Ship();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag               = false;
        if(!empty($id)&&!empty($params)){
            $model          = self::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag           = $model->update();
        }
        return $flag;
    }

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function files(){
        return $this->hasMany(\App\Models\SystemFile::class, 'attachment_id', 'id');
    }

    public function location(){
        return $this->hasOne(\App\Models\ShipLocation::class, 'id', 'ship_location_id');
    }

    public function departure(){
        return $this->hasOne(\App\Models\ShipDeparture::class, 'id', 'ship_departure_id');
    }

    public function timesAndPrices(){
        return $this->hasMany(\App\Models\ShipTimeAndPrice::class, 'ship_info_id', 'id');
    }

    public function staffs(){
        return $this->hasMany(\App\Models\RelationShipStaff::class, 'ship_info_id', 'id');
    }

    public function partners(){
        return $this->hasMany(\App\Models\RelationShipPartner::class, 'partner_info_id', 'id');
    }
}
