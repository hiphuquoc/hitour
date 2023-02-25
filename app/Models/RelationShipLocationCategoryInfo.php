<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationShipLocationCategoryInfo extends Model {
    use HasFactory;
    protected $table        = 'relation_ship_location_category_info';
    protected $fillable     = [
        'ship_location_id',
        'category_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationShipLocationCategoryInfo();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoCategory(){
        return $this->hasOne(\App\Models\Category::class, 'id', 'category_info_id');
    }

    public function infoShipLocation(){
        return $this->hasOne(\App\Models\ShipLocation::class, 'id', 'ship_location_id');
    }

}
