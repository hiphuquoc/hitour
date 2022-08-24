<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic;
use App\Models\SystemFile;
use Illuminate\Support\Facades\Storage;

class Upload {
    public static function uploadThumnail($requestImage, $name = null){
        $result             = [];
        if(!empty($requestImage)){
            // ===== folder upload
            $folderUpload   = config('admin.images.folderUpload');
            // ===== image upload
            $image          = $requestImage;
            $extension      = config('admin.images.extension');
            // ===== set filename & checkexists (Small)
            $name           = $name ?? time();
            $filenameSmall  = $folderUpload.$name.'-'.config('admin.images.smallResize_width').'.'.$extension;
            // save image resize (Small)
            ImageManagerStatic::make($image->getRealPath())
                ->encode($extension, config('admin.images.quality'))
                ->resize(config('admin.images.smallResize_width'), config('admin.images.smallResize_height'))
                ->save(Storage::path($filenameSmall));
            $result['filePathSmall']    = Storage::url($filenameSmall);
            // ===== set filename & checkexists (Normal)
            $filenameNormal = $folderUpload.$name.'-'.config('admin.images.normalResize_width').'.'.$extension;
            // save image resize (Normal)
            ImageManagerStatic::make($image->getRealPath())
                ->encode($extension, config('admin.images.quality'))
                ->resize(config('admin.images.normalResize_width'), config('admin.images.normalResize_height'))
                ->save(Storage::path($filenameNormal));
            $result['filePathNormal']    = Storage::url($filenameNormal);
        }
        return $result;
    }

    public static function uploadAvatar($requestImage, $name = null){
        $result             = [];
        if(!empty($requestImage)){
            // ===== folder upload
            $folderUpload   = config('admin.images.folderUpload');
            // ===== image upload
            $image          = $requestImage;
            $extension      = config('admin.images.extension');
            // ===== set filename & checkexists (Small)
            $name           = $name ?? time();
            $fileName       = $name.'-avatar-500x500.'.$extension;
            $fileUrl        = $folderUpload.$fileName;
            // save image resize (Small)
            ImageManagerStatic::make($image->getRealPath())
                ->encode($extension, config('admin.images.quality'))
                ->resize(500, 500)
                ->save(Storage::path($fileUrl));
            $result         = Storage::url($fileUrl);
        }
        return $result;
    }

    public static function uploadLogo($requestImage, $name = null){
        $result             = [];
        if(!empty($requestImage)){
            // ===== folder upload
            $folderUpload   = config('admin.images.folderUpload');
            // ===== image upload
            $image          = $requestImage;
            $extension      = config('admin.images.extension');
            // ===== set filename & checkexists
            $name           = $name ?? time();
            $filename       = $name.'-logo-'.config('admin.images.smallResize_width').'.'.$extension;
            $fileUrl        = $folderUpload.$filename;
            // save image resize
            ImageManagerStatic::make($image->getRealPath())
                ->encode($extension, config('admin.images.quality'))
                ->resize(660, 660)
                ->save(Storage::path($fileUrl));
            $result         = Storage::url($fileUrl);
        }
        return $result;
    }

    
}