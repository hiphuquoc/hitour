<?php

namespace App\Helpers;

class Orther {

    public static function getRandomInArray($array, $numberGet = 3){
        $result             = [];
        if(!empty($array)){
            $randomKey      = array_rand($array, $numberGet);
            foreach($randomKey as $key){
                $result[]   = $array[$key];
            }
        }
        return $result;
    }

}