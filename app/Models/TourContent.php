<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourContent extends Model {
    use HasFactory;
    protected $table        = 'tour_content';
    protected $fillable     = [
        'tour_info_id', 
        'special_content',
        'special_list',
        'include',
        'not_include',
        'policy_child',
        'menu',
        'hotel'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourContent();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag           = false;
        if(!empty($id)&&!empty($params)){
            $model      = self::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag       = $model->update();
        }
        return $flag;
    }
}
