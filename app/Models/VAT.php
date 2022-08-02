<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VAT extends Model {
    use HasFactory;
    protected $table        = 'vat_info';
    protected $fillable     = [
        'tour_booking_id',
        'vat_name', 
        'vat_code',
        'vat_address',
        'vat_path',
        'vat_note'
    ];
    public $timestamps      = false;

    public static function insertItem($params){
        $id                 = 0;
        if(!empty($params)){
            $model          = new VAT();
            foreach($params as $key => $value) $model->{$key}  = $value;
            $model->save();
            $id             = $model->id;
        }
        return $id;
    }
}
