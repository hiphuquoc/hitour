<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HotelBooking extends Model {
    use HasFactory;
    protected $table        = 'hotel_booking';
    protected $fillable     = [
        'customer_info_id', 
        'booking_status_id',
        'paid',
        'expiration_at',
        'created_by'
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
                            /* tìm theo loại */
                            ->when(!empty($params['search_type']), function($query) use($params){
                                $query->where('type', $params['search_type']);
                            })
                            /* tìm theo trạng thái */
                            ->when(!empty($params['search_status']), function($query) use($params){
                                $query->whereHas('status', function($q) use ($params){
                                    $q->where('id', $params['search_status']);
                                });
                            })
                            ->whereHas('quantityAndPrice', function($query){
                                
                            })
                            ->with('status', 'customer_contact', 'service', 'tour', 'quantityAndPrice')
                            ->orderBy('id', 'DESC')
                            ->paginate($paginate);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new HotelBooking();
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

    public function customer_contact(){
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_info_id');
    }

    public function status(){
        return $this->hasOne(\App\Models\HotelBookingStatus::class, 'id', 'status_id');
    }

    public function request(){
        return $this->hasMany(\App\Models\HotelBookingRequest::class, 'hotel_booking_id', 'id');
    }

    public function hotel(){
        return $this->hasOne(\App\Models\Hotel::class, 'id', 'hotel_info_id');
    }

    public function room(){
        return $this->hasOne(\App\Models\HotelRoom::class, 'id', 'hotel_room_id');
    }

    public function price(){
        return $this->hasOne(\App\Models\HotelPrice::class, 'id', 'hotel_price_id');
    }

    public function quantityAndPrice(){
        return $this->hasMany(\App\Models\HotelBookingQuantityAndPrice::class, 'hotel_booking_id', 'id');
    }

    public function costMoreLess(){
        return $this->hasMany(\App\Models\CostMoreLess::class, 'reference_id', 'id')->where('reference_type', 'hotel_booking_info');
    }

    public function detailMoreLess(){
        return $this->hasMany(\App\Models\DetailMoreLess::class, 'reference_id', 'id')->where('reference_type', 'hotel_booking_info');
    }

    // public function vat(){
    //     return $this->hasOne(\App\Models\VAT::class, 'reference_id', 'id');
    // }
}
