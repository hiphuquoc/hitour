<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Booking extends Model {
    use HasFactory;
    protected $table        = 'booking_info';
    protected $fillable     = [
        'customer_info_id', 
        'reference_id',
        'type',
        'status_id',
        'date_from',
        'date_to',
        'required_deposit',
        'paid',
        'expiration_at',
        'note_customer',
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
                            ->with('status', 'customer_contact', 'service', 'tour', 'quantityAndPrice')
                            ->orderBy('id', 'DESC')
                            ->paginate($paginate);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new Booking();
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

    public function customer_list(){
        return $this->hasMany(\App\Models\CitizenIdentity::class, 'reference_id', 'id')->where('reference_type', 'booking_info');
    }

    public function status(){
        return $this->hasOne(\App\Models\BookingStatus::class, 'id', 'status_id');
    }

    public function service(){
        return $this->hasOne(\App\Models\Service::class, 'id', 'reference_id');
    }

    public function tour(){
        return $this->hasOne(\App\Models\Tour::class, 'id', 'reference_id');
    }

    public function quantityAndPrice(){
        return $this->hasMany(\App\Models\BookingQuantityAndPrice::class, 'booking_info_id', 'id');
    }

    public function costMoreLess(){
        return $this->hasMany(\App\Models\CostMoreLess::class, 'reference_id', 'id')->where('reference_type', 'booking_info');
    }

    public function detailMoreLess(){
        return $this->hasMany(\App\Models\DetailMoreLess::class, 'reference_id', 'id')->where('reference_type', 'booking_info');
    }

    public function vat(){
        return $this->hasOne(\App\Models\VAT::class, 'reference_id', 'id');
    }
}
