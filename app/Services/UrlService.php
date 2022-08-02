<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Seo;
use App\Models\Blog;

class UrlService {

    public static function checkUrlExists($arrayUrl){
        $urlFull        = null;
        if(!empty($arrayUrl)){
            $urlEnd     = end($arrayUrl);
            $urlInput   = implode('/', $arrayUrl);
            $urlCheck   = self::buildFullUrlFormUrl($urlEnd);
            if($urlInput===$urlCheck) return $urlCheck;
        }
        return $urlFull;
    }

    public static function checkUrlType($arrayUrl){
        $result         = null;
        // dd($arrayUrl);
        if(!empty($arrayUrl)){
            /* Check Category */
            $tmp        = Category::getInfoBySeoAlias(end($arrayUrl));
            if(!empty($tmp)) {
                $result['type'] = 'category';
                $result['info'] = $tmp;
                return $result;
            }
            /* Check Blog */
            $tmp        = Blog::getInfoBySeoAlias(end($arrayUrl));
            if(!empty($tmp)) {
                $result['type'] = 'blog';
                $result['info'] = $tmp;
                return $result;
            }
        }
        return $result;
    }

    public static function buildFullUrlFormUrl($urlEnd){
        $result     = null;
        if(!empty($urlEnd)){
            $tmp                = Seo::select('*')
                                    ->where('seo_alias', $urlEnd)
                                    ->first();
            if(!empty($tmp)){
                $result         = $urlEnd;
                for($i=$tmp->level;$i>1;--$i){
                    $tmp        = Seo::select('*')
                                    ->where('id', $tmp->parent)
                                    ->first();
                    if(!empty($tmp)) {
                        $result = $tmp->seo_alias.'/'.$result;
                    }else {
                        return null;
                    }
                }
            }
        }
        return $result;
    }
    
}