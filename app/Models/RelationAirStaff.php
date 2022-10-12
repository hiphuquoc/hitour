<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationAirStaff extends Model {
    use HasFactory;
    protected $table        = 'relation_air_staff';
    protected $fillable     = [
        'air_info_id', 
        'staff_info_id'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationAirStaff();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public function infoStaff(){
        return $this->hasOne(\App\Models\Staff::class, 'id', 'staff_info_id');
    }
}
