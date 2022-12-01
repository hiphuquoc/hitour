<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipBooking extends Model {
    use HasFactory;
    protected $table        = 'ship_booking';
    protected $fillable     = [
        'no', 
        'customer_info_id',
        'ship_booking_status_id',
        'paid',
        'note_customer',
        'created_by',
        'expiration_at'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $paginate       = $params['paginate'];
        $result         = self::select('*')
                            /* tìm theo khách hàng */
                            ->when(!empty($params['search_customer']), function($query) use($params){
                                $query->whereHas('customer_contact', function($q) use ($params){
                                    $q->where('name', 'like', '%'.$params['search_customer'].'%')
                                    ->orwhere('phone', 'like', '%'.$params['search_customer'].'%')
                                    ->orwhere('zalo', 'like', '%'.$params['search_customer'].'%')
                                    ->orwhere('email', 'like', '%'.$params['search_customer'].'%');
                                });
                            })
                            /* tìm theo cảng đi */
                            ->when(!empty($params['search_departure']), function($query) use($params){
                                $query->whereHas('infoDeparture', function($q) use ($params){
                                    $q->where('port_departure', $params['search_departure']);
                                });
                            })
                            /* tìm theo cảng đến */
                            ->when(!empty($params['search_location']), function($query) use($params){
                                $query->whereHas('infoDeparture', function($q) use ($params){
                                    $q->where('port_location', $params['search_location']);
                                });
                            })
                            /* tìm theo trạng thái */
                            ->when(!empty($params['search_status']), function($query) use($params){
                                $query->whereHas('status', function($q) use ($params){
                                    $q->where('id', $params['search_status']);
                                });
                            })
                            ->with('customer_contact', 'infoDeparture')
                            ->orderBy('created_at', 'DESC')
                            ->paginate($paginate);
        return $result;
    }

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new ShipBooking();
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

    public function infoDeparture() {
        return $this->hasMany(\App\Models\ShipBookingQuantityAndPrice::class, 'ship_booking_id', 'id');
    }

    public function customer_contact() {
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_info_id');
    }

    public function status(){
        return $this->hasOne(\App\Models\ShipBookingStatus::class, 'id', 'ship_booking_status_id');
    }

    public function customer_list(){
        return $this->hasMany(\App\Models\CitizenIdentity::class, 'booking_id', 'id');
    }
}
