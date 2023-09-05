<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

class AdminCacheController extends Controller {

    public static function clear(){
        $caches = glob(Storage::path(config('main.cache.folderSave')).'*');
        foreach($caches as $cache){
            if(file_exists($cache)) @unlink($cache);
        }
        return true;
    }

}
