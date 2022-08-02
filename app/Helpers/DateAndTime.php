<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic;
use App\Models\SystemFile;

class DateAndTime {

    public static function convertMktimeToDayOfWeek($mkTime){
        $result             = null;
        if(!empty($mkTime)){
            $dataConvert    = [
                0   => 'Chủ nhật',
                1   => 'Thứ 2',
                2   => 'Thứ 3',
                3   => 'Thứ 4',
                4   => 'Thứ 5',
                5   => 'Thứ 6',
                6   => 'Thứ 7'
            ];
            $dayOfWeek      = date('w', $mkTime);
            $result         = $dataConvert[$dayOfWeek];
        }
        return $result;
    }

    public static function calculatorDayEndTour($dayStart, $infoTour, $format = 'd-m-Y'){
        $result         = null;
        if(!empty($infoTour)){
            $mkEnd      = strtotime($dayStart) + 86400*($infoTour->days - 1);
            $result     = date($format, $mkEnd);
        }
        return $result;
    }

}