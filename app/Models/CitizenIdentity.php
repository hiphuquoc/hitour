<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CitizenIdentity extends Model {
    use HasFactory;
    protected $table        = 'citizen_identity_info';
    protected $fillable     = [
        'reference_id',
        'reference_type',
        'name',
        'identity',
        'year_of_birth'
    ];
    public $timestamps      = true;

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new CitizenIdentity();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id         = $model->id;
        }
        return $id;
    }

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
