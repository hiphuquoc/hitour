<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model {
    use HasFactory;
    protected $table        = 'region_info';

    // public static function getItemByIdProvince($idProvince){
    //     $result         = [];
    //     if(!empty($idProvince)){
    //         $result     = District::select('id', 'district_name as name', 'district_type as type', 'district_name_with_type as name_with_type')
    //                                 ->where('province_id', $idProvince)
    //                                 ->get()
    //                                 ->toArray();
    //     }
    //     return $result;
    // }
}
