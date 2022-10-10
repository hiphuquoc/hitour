<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model {
    use HasFactory;
    protected $table        = 'ship_info';
    protected $fillable     = [
        'seo_id', 
        'name',
        'name_round',
        'ship_port_departure_id',
        'ship_departure_id',
        'ship_port_location_id',
        'ship_location_id',
        'note',
        'created_by'
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
                            $query->whereHas('location', function($q) use ($params){
                                $q->where('id', $params['search_location']);
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
                            $query->where('relation_table', 'ship_info');
                        }])
                        ->with('seo', 'location', 'departure', 'staffs.infoStaff', 'partners.infoPartner')
                        ->get();
        return $result;
    }

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new Ship();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }

    public static function updateItem($id, $params){
        $flag               = false;
        if(!empty($id)&&!empty($params)){
            $model          = self::find($id);
            foreach($params as $key => $value) $model->{$key}  = $value;
            $flag           = $model->update();
        }
        return $flag;
    }

    public function seo() {
        return $this->hasOne(\App\Models\Seo::class, 'id', 'seo_id');
    }

    public function files(){
        return $this->hasMany(\App\Models\SystemFile::class, 'attachment_id', 'id');
    }

    public function departure(){
        return $this->hasOne(\App\Models\ShipDeparture::class, 'id', 'ship_departure_id');
    }

    public function portDeparture(){
        return $this->hasOne(\App\Models\ShipPort::class, 'id', 'ship_port_departure_id');
    }

    public function location(){
        return $this->hasOne(\App\Models\ShipLocation::class, 'id', 'ship_location_id');
    }

    public function portLocation(){
        return $this->hasOne(\App\Models\ShipPort::class, 'id', 'ship_port_location_id');
    }

    public function prices(){
        return $this->hasMany(\App\Models\ShipPrice::class, 'ship_info_id', 'id')->orderBy('date_start', 'asc')->orderBy('date_end', 'asc');
    }

    public function staffs(){
        return $this->hasMany(\App\Models\RelationShipStaff::class, 'ship_info_id', 'id');
    }

    public function partners(){
        return $this->hasMany(\App\Models\RelationShipPartner::class, 'ship_info_id', 'id');
    }

    public function questions(){
        return $this->hasMany(\App\Models\QuestionAnswer::class, 'reference_id', 'id');
    }
}
