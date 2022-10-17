<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourContinent extends Model {
    use HasFactory;
    protected $table        = 'tour_continent';
    protected $fillable     = [
        'name', 
        'display_name',
        'description',
        'seo_id',
        'note'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $result     = self::select('*')
                        /* tìm theo tên */
                        ->when(!empty($params['search_name']), function($query) use($params){
                            $query->where('name', 'like', '%'.$params['search_name'].'%');
                        })
                        ->with(['files' => function($query){
                            $query->where('relation_table', 'tour_continent');
                        }])
                        ->with('seo')
                        ->get();
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourContinent();
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

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }

    public function tourCountries(){
        return $this->hasMany(\App\Models\TourCountry::class, 'tour_continent_id', 'id');
    }

    public function guides() {
        return $this->hasMany(\App\Models\RelationTourContinentGuideInfo::class, 'tour_continent_id', 'id');
    }

    public function serviceLocations() {
        return $this->hasMany(\App\Models\RelationTourContinentServiceLocation::class, 'tour_continent_id', 'id');
    }

    public function airLocations() {
        return $this->hasMany(\App\Models\RelationTourContinentAirLocation::class, 'tour_continent_id', 'id');
    }

}
