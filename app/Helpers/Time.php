<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class Time {

    public static function calcTimeMove($timeStart, $timeEnd){
        $mkDistance         = null;
        if(!empty($timeStart)&&!empty($timeEnd)){
            $hourStart      = date('H:i', strtotime($timeStart));
            $hourEnd        = date('H:i', strtotime($timeEnd));
            /* chênh lệch mktime */
            $mkDistance     = strtotime($hourEnd) - strtotime($hourStart);
        }
        return $mkDistance;
    }

    public static function convertMkToTimeMove($mkTime){
        $result             = null;
        if(!empty($mkTime)){
            $hours          = number_format(floor($mkTime/3600), 0);
            $minutes        = ($mkTime%3600)/60;
            $result         = $hours.' giờ';
            if(!empty($minutes)) $result  .= ' '.$minutes.' phút';
        }
        return $result;
    }

    
}