<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guide extends Model {
    use HasFactory;
    protected $table        = 'guide_info';
    protected $fillable     = [
        'name', 
        'description',
        'seo_id', 
        'district_id',
        'province_id',
        'region_id',
        'note'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $result     = self::select('*')
                        /* tìm theo tên */
                        ->when(!empty($params['search_name']), function($query) use($params){
                            $query->where('name', 'like', '%'.$params['search_name'].'%');
                        })
                        /* tìm theo vùng miền */
                        ->when(!empty($params['search_region']), function($query) use($params){
                            $query->where('region_id', $params['search_region']);
                        })
                        ->with(['files' => function($query){
                            $query->where('relation_table', 'guide_info');
                        }])
                        ->with('seo')
                        ->orderBy('id', 'DESC')
                        ->get();
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new Guide();
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

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function files(){
        return $this->hasMany(\App\Models\SystemFile::class, 'attachment_id', 'id');
    }

    public function region(){
        return $this->hasOne(\App\Models\Region::class, 'id', 'region_id');
    }

    public function province(){
        return $this->hasOne(\App\Models\Province::class, 'id', 'province_id');
    }

    public function district(){
        return $this->hasOne(\App\Models\District::class, 'id', 'district_id');
    }

    public function tourLocations(){
        return $this->hasMany(\App\Models\RelationTourLocationGuide::class, 'guide_info_id', 'id');
    }

}
