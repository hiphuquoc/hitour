<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

use App\Models\Seo;

class Url {

    public static function checkUrlExists($slug){
        $infoPage       = Seo::select('*')
                            ->where('slug', $slug)
                            ->first();
        if(!empty($infoPage->slug_full)) return $infoPage;
        return null;
    }

    public static function buildBreadcrumb($slugFull){
        $tmp            = explode('/', $slugFull);
        $result         = new \Illuminate\Database\Eloquent\Collection;
        foreach($tmp as $item){
            $infoItem   = Seo::select('*')
                            ->where('slug', $item)
                            ->first();
            if(empty($infoItem)) return null;
            $result[]   = $infoItem;
        }
        return $result;
    }
}