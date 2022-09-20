<?php

namespace App\Helpers;

class Build {

    public static function buildFullShipPort($collectionShipPort){
        $result   = [];
        if(!empty($collectionShipPort->district->district_name)) $result[] = $collectionShipPort->district->district_name;
        if(!empty($collectionShipPort->name)) $result[] = $collectionShipPort->name;
        // if(!empty($collectionShipPort->province->province_name)) $result[] = $collectionShipPort->province->province_name;
        return implode(', ', $result);
    }

}