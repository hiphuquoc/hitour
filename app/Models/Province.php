<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model {
    use HasFactory;
    protected $table        = 'province_info';

    public static function getItemByIdRegion($idRegion){
        $result         = [];
        if(!empty($idRegion)){
            $result     = Province::select('id', 'province_name as name', 'province_type as type', 'province_name_with_type as name_with_type')
                            ->where('region_id', $idRegion)
                            ->get();
        }
        return $result;
    }
}
