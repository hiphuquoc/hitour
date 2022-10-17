<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourCountry extends Model {
    use HasFactory;
    protected $table        = 'tour_country';
    protected $fillable     = [
        'tour_continent_id',
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
                        /* tìm theo châu lục */
                        ->when(!empty($params['search_continent']), function($query) use($params){
                            $query->where('tour_continent_id', $params['search_continent']);
                        })
                        ->with(['files' => function($query){
                            $query->where('relation_table', 'tour_country');
                        }])
                        ->with('seo')
                        ->get();
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourCountry();
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

    public function tours(){
        return $this->hasMany(\App\Models\RelationTourInfoForeignTourCountry::class, 'tour_country_id', 'id');
    }

    public function guides() {
        return $this->hasMany(\App\Models\RelationTourCountryGuideInfo::class, 'tour_country_id', 'id');
    }

    public function serviceLocations() {
        return $this->hasMany(\App\Models\RelationTourCountryServiceLocation::class, 'tour_country_id', 'id');
    }

    public function airLocations() {
        return $this->hasMany(\App\Models\RelationTourCountryAirLocation::class, 'tour_country_id', 'id');
    }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }

}
