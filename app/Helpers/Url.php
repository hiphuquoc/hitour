<?php

namespace App\Helpers;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class Url {
    public static function buildFullLinkArray($arrayData){
        if(!empty($arrayData)){
            $infoSeo    = DB::table('seo')
                        ->select(array_merge(['id'], config('column.seo')))
                        ->get()
                        ->toArray();
            for($i=0;$i<count($arrayData);++$i){
                $url                = $arrayData[$i]->seo_alias;
                $parent             = $arrayData[$i]->parent;
                for($j=1;$j<$arrayData[$i]->level;++$j){
                    foreach($infoSeo as $item){
                        if($item->id==$parent) {
                            $url    = $item->seo_alias.'/'.$url;
                            $parent = $item->parent;
                        }
                    }
                }
                $arrayData[$i]->seo_alias_full    = $url;
            }
        }
        return $arrayData;
    }

    public static function buildFullLinkOne($data){
        if(!empty($data)){
            $infoSeo    = DB::table('seo')
                        ->select(array_merge(['id'], config('column.seo')))
                        ->get()
                        ->toArray();
            $url                = $data->seo_alias;
            $parent             = $data->parent;
            for($j=1;$j<$data->level;++$j){
                foreach($infoSeo as $item){
                    if($item->id==$parent) {
                        $url    = $item->seo_alias.'/'.$url;
                        $parent = $item->parent;
                    }
                }
            }
            $data->seo_alias_full    = $url;
        }
        return $data;
    }

    public static function buildArrayBreadcrumb($data, $type = 'category'){
        $result         = [];
        if(!empty($data)){
            $result[]   = $data;
            $infoSeo    = DB::table('seo')
                        ->select(array_merge(['id'], config('column.seo')))
                        ->get()
                        ->toArray();
            if($type==='category'){
                $parent                 = $data->parent;
                for($i=1;$i<$data->level;++$i){
                    foreach($infoSeo as $item){
                        if($item->id==$parent) {
                            $parent     = $item->parent;
                            $result[]   = $item;
                        }
                    }
                }
            }else if($type==='blog'){
                $tmp    = Category::getListCategoryByBlogId($data->id);
                if(!empty($tmp)){
                    $infoCategoryNear   = $tmp[0];
                    foreach($tmp as $t){
                        if($t->level > $infoCategoryNear->level) $infoCategoryNear = $t;
                    }
                    /* gọi ngược lại để buil arrray category */
                    $result             = self::buildArrayBreadcrumb($infoCategoryNear, 'category');
                }
            }
            /* sắp xếp theo level */
            $level = array_column($result, 'level');
            array_multisort($level, SORT_ASC, $result);
            /* nếu là blog thì ghép thêm data blog vào */
            if($type==='blog') $result[] = $data;
            /* build link full cho result */
            $result = self::buildFullLinkArray($result);
        }
        return $result;
    }

    public static function buildParentChild($item, $arrayData){
        foreach($arrayData as $data){
            if($item->page_id==$data->parent) {
                // check đệ quy
                $flagNext           = false;
                foreach($arrayData as $d) {
                    if($data->page_id==$d->parent){
                        $flagNext   = true;
                        break;
                    }
                }
                // trả dữ liệu
                if($flagNext==true){
                    $item->child[]  = self::buildParentChild($data, $arrayData);
                }else {
                    $item->child[]  = $data;
                }
            }
        }
        return $item;
    }
}