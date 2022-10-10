<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tour extends Model {
    use HasFactory;
    protected $table        = 'tour_info';
    protected $fillable     = [
        'seo_id', 
        'tour_departure_id',
        'code',
        'name', 
        'price_show',
        'price_del',
        'departure_schedule',
        'days',
        'nights',
        'time_start',
        'time_end',
        'pick_up',
        'status_show',
        'status_sidebar'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $result     = self::select('*')
                        /* tìm theo tên */
                        ->when(!empty($params['search_name']), function($query) use($params){
                            $query->where('name', 'like', '%'.$params['search_name'].'%');
                        })
                        /* tìm theo khu vực */
                        ->when(!empty($params['search_location']), function($query) use($params){
                            $query->whereHas('locations', function($q) use ($params){
                                $q->where('tour_location_id', $params['search_location']);
                            });
                        })
                        /* tìm theo đối tác */
                        ->when(!empty($params['search_partner']), function($query) use($params){
                            $query->whereHas('partners', function($q) use ($params){
                                $q->where('partner_info_id', $params['search_partner']);
                            });
                        })
                        /* tìm theo nhân viên */
                        ->when(!empty($params['search_staff']), function($query) use($params){
                            $query->whereHas('staffs', function($q) use ($params){
                                $q->where('staff_info_id', $params['search_staff']);
                            });
                        })
                        ->orderBy('created_at', 'DESC')
                        ->with(['files' => function($query){
                            $query->where('relation_table', 'tour_info');
                        }])
                        ->with('seo', 'locations.infoLocation', 'departure', 'staffs.infoStaff', 'partners.infoPartner')
                        ->get();
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new Tour();
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

    public static function getItemBySlug($slug = null){
        $result         = null;
        if(!empty($slug)){
            $result     = DB::table('seo')
                            ->join('tour_info', 'tour_info.seo_id', '=', 'seo.id')
                            ->select(array_merge(config('table.seo'), config('table.tour_info')))
                            ->where('slug', $slug)
                            ->first();
        }
        return $result;
    }

    public static function getItemById($id = null){
        $result         = null;
        if(!empty($id)){
            $result     = Tour::select('*')
                                ->where('id', $id)
                                ->with('seo', 'files', 'locations', 'staffs', 'partners.infoPartner', 'options.prices', 'content', 'timetables')
                                ->first();
        }
        return $result;
    }

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function files(){
        return $this->hasMany(\App\Models\SystemFile::class, 'attachment_id', 'id');
    }

    public function locations(){
        return $this->hasMany(\App\Models\RelationTourLocation::class, 'tour_info_id', 'id');
    }

    public function departure(){
        return $this->hasOne(\App\Models\TourDeparture::class, 'id', 'tour_departure_id');
    }

    public function staffs(){
        return $this->hasMany(\App\Models\RelationTourStaff::class, 'tour_info_id', 'id');
    }

    public function partners(){
        return $this->hasMany(\App\Models\RelationTourPartner::class, 'tour_info_id', 'id');
    }

    public function options(){
        return $this->hasMany(\App\Models\TourOption::class, 'tour_info_id', 'id');
    }

    public function content(){
        return $this->hasOne(\App\Models\TourContent::class, 'tour_info_id', 'id');
    }

    public function timetables(){
        return $this->hasMany(\App\Models\TourTimetable::class, 'tour_info_id', 'id');
    }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }
}
