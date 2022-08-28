<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationShipPort extends Model {
    use HasFactory;
    protected $table        = 'relation_ship_port';
    protected $fillable     = [
        'ship_info_id', 
        'ship_port_id'
    ];
    public $timestamps      = false;

    // public static function getList($params = null){
    //     $result     = self::select('*')
    //                     /* tìm theo tên */
    //                     ->when(!empty($params['search_name']), function($query) use($params){
    //                         $query->where('name', 'like', '%'.$params['search_name'].'%');
    //                     })
    //                     /* tìm theo vùng miền */
    //                     ->when(!empty($params['search_region']), function($query) use($params){
    //                         $query->where('region_id', $params['search_region']);
    //                     })
    //                     ->with(['files' => function($query){
    //                         $query->where('relation_table', 'ship_location');
    //                     }], 'seo')
    //                     ->get();
    //     return $result;
    // }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new RelationShipPort();
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

    public function infoPort() {
        return $this->hasOne(\App\Models\ShipPort::class, 'id', 'ship_port_id');
    }
}
