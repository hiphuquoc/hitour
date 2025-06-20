<?php

namespace App\Helpers;

class Charactor {

    public static function randomString($length = 10){
        $arr    = array_merge(range(0,9),range('A','Z'));
        $str    = implode('', $arr);
        $str    = str_shuffle($str);
        $result = mb_substr($str, 0, $length);
        return $result;
    }

    public static function convertStrToUrl($str=null,$word='-'){
        $result     = '';
        if($str!=null){
            $str    = preg_replace("/(&amp;)/", '', $str);
            $str    = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
            $str    = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
            $str    = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
            $str    = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
            $str    = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
            $str    = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
            $str    = preg_replace("/(đ)/", 'd', $str);
            $str    = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
            $str    = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
            $str    = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
            $str    = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
            $str    = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
            $str    = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
            $str    = preg_replace("/(Đ)/", 'D', $str);
            $str    = strtolower($str);
            $result = explode(' ', $str);
            for($i=0;$i<count($result);++$i){
                if(trim($result[$i])==$word||$result[$i]==''){
                    unset($result[$i]);
                }else {
                    $result[$i] = trim($result[$i]);
                }
            }
            $result = implode($word, $result);
        }
        return $result;
    }
    public static function removeSpecialCharacters($string) {
        // Xóa các ký tự khoảng trống và ký tự đặc biệt từ chuỗi
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
        return $string;
    }
}