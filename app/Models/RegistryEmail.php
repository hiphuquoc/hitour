<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistryEmail extends Model {
    use HasFactory;
    protected $table        = 'registry_email';
    protected $fillable     = [
        'email'
    ];
    public $timestamps = true;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RegistryEmail();
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

    // public function seo() {
    //     return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    // }

    // public function files(){
    //     return $this->hasMany(\App\Models\SystemFile::class, 'attachment_id', 'id');
    // }
}
