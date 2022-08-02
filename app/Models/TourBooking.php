<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TourBooking extends Model {
    use HasFactory;
    protected $table        = 'tour_booking';
    protected $fillable     = [
        'customer_info_id', 
        'tour_info_id',
        'tour_booking_status_id',
        'tour_option_id',
        'paid',
        'note_customer',
        'created_by'
    ];
    public $timestamps      = true;

    public static function getList($params = null){
        $paginate       = $params['paginate'] ?? null;
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
                            /* tìm theo khu vực */
                            ->when(!empty($params['search_location']), function($query) use($params){
                                $query->whereHas('tour.locations', function($q) use ($params){
                                    $q->where('tour_location_id', $params['search_location']);
                                });
                            })
                            /* tìm theo trạng thái */
                            ->when(!empty($params['search_status']), function($query) use($params){
                                $query->whereHas('status', function($q) use ($params){
                                    $q->where('id', $params['search_status']);
                                });
                            })
                            ->with('tour.options.prices', 'customer_contact', 'option', 'quantiesAndPrices', 'status')
                            ->orderBy('created_at', 'DESC')
                            ->paginate($paginate);
        return $result;
    }

    public static function insertItem($params){
        $id             = 0;
        if(!empty($params)){
            $model      = new TourBooking();
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

    public function tour(){
        return $this->hasOne(\App\Models\Tour::class, 'id', 'tour_info_id');
    }

    public function customer_contact(){
        return $this->hasOne(\App\Models\Customer::class, 'id', 'customer_info_id');
    }

    public function option(){
        return $this->hasOne(\App\Models\TourOption::class, 'id', 'tour_option_id');
    }

    public function quantiesAndPrices(){
        return $this->hasMany(\App\Models\TourBookingQuantityAndPrice::class, 'tour_booking_id', 'id');
    }

    public function customer_list(){
        return $this->hasMany(\App\Models\CitizenIdentity::class, 'tour_booking_id', 'id');
    }

    public function status(){
        return $this->hasOne(\App\Models\TourBookingStatus::class, 'id', 'tour_booking_status_id');
    }

    public function costMoreLess(){
        return $this->hasMany(\App\Models\CostMoreLess::class, 'reference_id', 'id');
    }

    public function vat(){
        return $this->hasOne(\App\Models\VAT::class, 'reference_id', 'id');
    }
}
