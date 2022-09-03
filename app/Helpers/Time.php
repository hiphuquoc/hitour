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
            $result         = $hours.'h';
            if(!empty($minutes)) $result  .= ' '.$minutes.'m';
        }
        return $result;
    }

    public static function createDateRangeArray($strDateFrom, $strDateTo){
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = [];

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));

        if ($iDateTo >= $iDateFrom) {
            array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo) {
                $iDateFrom += 86400; // add 24 hours
                array_push($aryRange, date('Y-m-d', $iDateFrom));
            }
        }
        return $aryRange;
    }
}