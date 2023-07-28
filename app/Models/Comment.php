<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Comment extends Model {
    use HasFactory;
    protected $table        = 'comment_info';
    protected $fillable     = [
        'reference_type',
        'reference_id',
        'title', 
        'author_name',
        'author_phone',
        'author_email',
        'rating',
        'rating_for_local',
        'rating_for_clean',
        'rating_for_service',
        'rating_for_value'
    ];
    public $timestamps      = true;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new Comment();
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
