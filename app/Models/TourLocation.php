<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourLocation extends Model {
    use HasFactory;
    protected $table        = 'tour_location';
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
                            $query->where('relation_table', 'tour_location');
                        }])
                        ->with('seo')
                        ->paginate($params['paginate']);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourLocation();
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

    public function tours(){
        return $this->hasMany(\App\Models\RelationTourLocation::class, 'tour_location_id', 'id');
    }

    public function guides() {
        return $this->hasMany(\App\Models\RelationTourLocationGuide::class, 'tour_location_id', 'id');
    }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }

    public function shipLocations(){
        return $this->hasMany(\App\Models\RelationTourLocationShipLocation::class, 'tour_location_id', 'id');
    }

    public function carrentalLocations(){
        return $this->hasMany(\App\Models\RelationTourLocationCarrentalLocation::class, 'tour_location_id', 'id');
    }

    public function serviceLocations() {
        return $this->hasMany(\App\Models\RelationTourLocationServiceLocation::class, 'tour_location_id', 'id');
    }

    public function airLocations() {
        return $this->hasMany(\App\Models\RelationTourLocationAirLocation::class, 'tour_location_id', 'id');
    }

    public function destinations() {
        return $this->hasMany(\App\Models\RelationTourLocationDestinationList::class, 'tour_location_id', 'id');
    }

    public function specials() {
        return $this->hasMany(\App\Models\RelationTourLocationSpecialList::class, 'tour_location_id', 'id');
    }

    public function comboLocations(){
        return $this->hasMany(\App\Models\RelationTourLocationComboLocation::class, 'tour_location_id', 'id');
    }

    public function hotelLocations(){
        return $this->hasMany(\App\Models\RelationTourLocationHotelLocation::class, 'tour_location_id', 'id');
    }

}
