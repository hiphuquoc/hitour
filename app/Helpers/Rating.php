<?php

namespace App\Helpers;

class Rating {

    public static function getTextRatingByRule($score){
        $result         = '-';
        if(!empty($score)){
            $arrayRule  = config('main.rating_rule');
            foreach($arrayRule as $rule){
                if($score>$rule['score']){
                    $result = $rule['text'];
                    break;
                }
            }
        }
        return $result;
    }

}