<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelContact extends Model {
    use HasFactory;
    protected $table        = 'hotel_contact';
    protected $fillable     = [
        'hotel_info_id', 
        'name',
        'address',
        'phone', 
        'zalo',
        'email',
        'default'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new HotelContact();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = HotelContact::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }

    public static function deleteItem($id){
        $flag           = false;
        if(!empty($id)) $flag = HotelContact::find($id)->delete();
        return $flag;
    }

}
