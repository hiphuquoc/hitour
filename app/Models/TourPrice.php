<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourPrice extends Model {
    use HasFactory;
    protected $table        = 'tour_price';
    protected $fillable     = [
        'tour_option_id',
        'apply_age', 
        'price',
        'profit'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new TourPrice();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }

    public static function deleteAndInsertItem($idOption, $data){
        /* delete relation trước đó */
        $countDeleted                       = 0;
        if(!empty($idOption)) {
            $countDeleted                   = TourPrice::select('*')->where('tour_option_id', $idOption)->delete();
        }
        /* insert */
        $countInsert                        = 0;
        if(!empty($data)){
            for($i=0;$i<count($data['apply_age']);++$i){
                $flag                       = self::insertItem([
                    'tour_option_id'    => $idOption,
                    'apply_age'         => $data['apply_age'][$i],
                    'price'             => $data['price'][$i],
                    'profit'            => $data['profit'][$i]
                ]);
                if(!empty($flag)) $countInsert  += 1;
            }
        }
        $result['delete']                   = $countDeleted;
        $result['insert']                   = $countInsert;
        return $result;
    }

    // public function infoLocation(){
    //     return $this->hasOne(\App\Models\TourLocation::class, 'id', 'tour_location_id');
    // }
}
