<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingAction extends Model {
    use HasFactory;
    protected $table        = 'booking_action';
    protected $fillable     = [
        'name', 
        'color',
        'icon'
    ];
    public $timestamps      = false;

    // public static function BookingAction($params){
    //     $id             = 0;
    //     if(!empty($params)){
    //         $model      = new BookingStatus();
    //         foreach($params as $key => $value) $model->{$key}  = $value;
    //         $model->save();
    //         $id         = $model->id;
    //     }
    //     return $id;
    // }

    // public static function updateItem($id, $params){
    //     $flag           = false;
    //     if(!empty($id)&&!empty($params)){
    //         $model      = self::find($id);
    //         foreach($params as $key => $value) $model->{$key}  = $value;
    //         $flag       = $model->update();
    //     }
    //     return $flag;
    // }
}
