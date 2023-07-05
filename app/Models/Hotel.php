<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Hotel extends Model {
    use HasFactory;
    protected $table        = 'hotel_info';
    protected $fillable     = [
        'seo_id',
        'hotel_location_id',
        'name', 
        'code',
        'company_name',
        'company_code',
        'address',
        'website',
        'hotline',
        'email',
        'logo',
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
                                $q->where('combo_location_id', $params['search_location']);
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
                            $query->where('relation_table', 'hotel_info');
                        }])
                        ->with('seo', 'locations.infoLocation', 'staffs.infoStaff', 'partners.infoPartner')
                        ->paginate($params['paginate']);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new Hotel();
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
                            ->join('combo_location', 'combo_location.seo_id', '=', 'seo.id')
                            ->select(array_merge(config('table.seo'), config('table.combo_location')))
                            ->where('slug', $slug)
                            ->first();
        }
        return $result;
    }

    public static function getItemById($id = null){
        $result         = null;
        if(!empty($id)){
            $result     = Combo::select('*')
                            ->where('id', $id)
                            ->with('seo', 'files', 'staffs', 'partners.infoPartner', 'options.prices', 'contents')
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

    public function location(){
        return $this->hasOne(\App\Models\HotelLocation::class, 'id', 'hotel_location_id');
    }

    public function staffs(){
        return $this->hasMany(\App\Models\RelationHotelStaff::class, 'hotel_info_id', 'id');
    }

    public function contacts(){
        return $this->hasMany(\App\Models\RelationHotelContact::class, 'hotel_info_id', 'id');
    }

    // public function options(){
    //     return $this->hasMany(\App\Models\ComboOption::class, 'hotel_info_id', 'id');
    // }

    // public function contents(){
    //     return $this->hasMany(\App\Models\ComboContent::class, 'combo_info_id', 'id');
    // }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }
}
