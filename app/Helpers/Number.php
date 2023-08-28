<?php

namespace App\Helpers;

class Number {

    public static function calculatorPriceBeforeSaleoff($price, $saleOff){
        $result         = 0;
        if(!empty($price)&&!empty($saleOff)){
            $result     = ($price*100)/(100 - $saleOff);
        }
        return $result;
    }

    public static function calculatorSaleoff($priceDel, $priceNow){
        $result         = 0;
        if(!empty($priceDel)&&!empty($priceNow)){
            $result     = (($priceDel - $priceNow)/$priceDel)*100;
        }
        return number_format($result, 0);
    }
}