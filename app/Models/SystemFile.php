<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemFile extends Model {
    use HasFactory;
    protected $table        = 'system_file';
    protected $fillable     = [
        'attachment_id', 
        'relation_table', 
        'file_name',
        'file_path', 
        'file_extension', 
        'file_type'
    ];

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new SystemFile();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

    public static function removeItem($id){
        $res = SystemFile::find($id)->delete();
        return $res;
    }
}
