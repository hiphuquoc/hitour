<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

use App\Models\Seo;

class Url {

    public static function checkUrlExists($arrayUrl){
        $urlEnd     = end($arrayUrl);
        $urlInput   = implode('/', $arrayUrl);
        $result     = self::buildFullUrlAndGetData($urlEnd);
        if(!empty($result['slug_full'])&&$urlInput===$result['slug_full']) {
            return $result;
        }else {
            return null;
        }
    }

    public static function buildFullUrlAndGetData($urlEnd){
        $arrayUrl[]     = $urlEnd;
        $arrayData      = [];
        $type           = null;
        if(!empty($urlEnd)){
            $infoUrlEnd         = Seo::select('*')
                                    ->where('slug', $urlEnd)
                                    ->first();
            if(!empty($infoUrlEnd)){
                $arrayData[]    = $infoUrlEnd;
                $type           = $infoUrlEnd->type;
                $parent         = $infoUrlEnd->parent;
                for($i=$infoUrlEnd->level;$i>1;--$i){
                    $tmp        = Seo::select('*')
                                    ->where('id', $parent)
                                    ->first();
                    $arrayUrl[] = $tmp->slug;
                    $arrayData[]= $tmp;
                    $parent     = $tmp->parent;
                }
            }
        }
        $result['slug']         = $urlEnd;
        $result['slug_full']    = implode('/', array_reverse($arrayUrl));
        $result['type']         = $type;
        $result['data']         = array_reverse($arrayData);
        return $result;
    }

    public static function buildFullLinkArray($arrayData){
        if(!empty($arrayData)){
            $infoSeo    = Seo::select('id', 'slug')
                            ->get();
            for($i=0;$i<count($arrayData);++$i){
                $url                = $arrayData[$i]->slug;
                $parent             = $arrayData[$i]->parent;
                for($j=1;$j<$arrayData[$i]->level;++$j){
                    foreach($infoSeo as $item){
                        if($item->id==$parent) {
                            $url    = $item->slug.'/'.$url;
                            $parent = $item->parent;
                        }
                    }
                }
                $arrayData[$i]->slug_full    = $url;
            }
        }
        return $arrayData;
    }

    public static function buildFullLinkOne($data){
        if(!empty($data)){
            $infoSeo    = DB::table('seo')
                        ->select('id', 'slug', 'parent')
                        ->get()
                        ->toArray();
            $url                = $data->slug;
            $parent             = $data->parent;
            for($j=1;$j<$data->level;++$j){
                foreach($infoSeo as $item){
                    if($item->id==$parent) {
                        $url    = $item->slug.'/'.$url;
                        $parent = $item->parent;
                    }
                }
            }
            $data->slug_full    = $url;
        }
        return $data;
    }

    // public static function buildParentChild($item, $arrayData){
    //     foreach($arrayData as $data){
    //         if($item->page_id==$data->parent) {
    //             // check đệ quy
    //             $flagNext           = false;
    //             foreach($arrayData as $d) {
    //                 if($data->page_id==$d->parent){
    //                     $flagNext   = true;
    //                     break;
    //                 }
    //             }
    //             // trả dữ liệu
    //             if($flagNext==true){
    //                 $item->child[]  = self::buildParentChild($data, $arrayData);
    //             }else {
    //                 $item->child[]  = $data;
    //             }
    //         }
    //     }
    //     return $item;
    // }
}