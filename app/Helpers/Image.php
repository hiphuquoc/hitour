<?php

namespace App\Helpers;
use Illuminate\Support\Facades\DB;
use App\Models\Seo;

class Image {

    public static function getActionImageByType($image){
        $arrayAction            = [];
        if(!empty($image)){
            $infoImage          = pathinfo($image);
            $filename           = $infoImage['filename'];
            $tmp                = explode(config('admin.images.keyType'), $filename);
            $key                = end($tmp);
            $fullAction         = config('admin.images.type');
            if(key_exists($key, $fullAction)){
                $arrayAction    = $fullAction[$key];
            }else {
                $arrayAction    = $fullAction['default'];
            }
        }
        return $arrayAction;
    }
}