<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelLocation extends Model {
    use HasFactory;
    protected $table        = 'hotel_location';
    protected $fillable     = [
        'name', 
        'display_name',
        'description',
        'seo_id', 
        'district_id',
        'province_id',
        'region_id',
        'island',
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
                            $query->where('relation_table', 'hotel_location');
                        }])
                        ->with('seo')
                        ->paginate($params['paginate']);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new HotelLocation();
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

    public function hotels(){
        return $this->hasMany(\App\Models\Hotel::class, 'hotel_location_id', 'id')->orderBy('id', 'DESC');
    }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }

    public function tourLocations(){
        return $this->hasMany(\App\Models\RelationTourLocationHotelLocation::class, 'hotel_location_id', 'id');
    }
}
