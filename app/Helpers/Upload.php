<?php

namespace App\Helpers;

use Intervention\Image\ImageManagerStatic;
use App\Models\SystemFile;

class Upload {
    public static function uploadThumnail($requestImage, $name = null){
        $result             = [];
        if(!empty($requestImage)){
            // ===== folder upload
            $folderUpload   = config('admin.images.folderUpload');
            if(!file_exists(public_path($folderUpload))) mkdir(public_path($folderUpload), 777, true);
            // ===== image upload
            $image          = $requestImage;
            $extension      = $image->getClientOriginalExtension();
            // ===== set filename & checkexists (Small)
            $name           = $name ?? time();
            $filenameSmall  = $name.'-'.time().'-'.config('admin.images.smallResize_width').'.'.$extension;
            $filepathSmall  = $folderUpload.$filenameSmall;
            // save image resize (Small)
            $imageSmall     = ImageManagerStatic::make($image->getRealPath());
            $imageSmall->resize(config('admin.images.smallResize_width'), config('admin.images.smallResize_height'));
            $imageSmall->save(public_path($filepathSmall));
            $result['filePathSmall']    = $filepathSmall;
            // ===== set filename & checkexists (Normal)
            $filenameNormal = $name.'-'.time().'-'.config('admin.images.normalResize_width').'.'.$extension;
            $filepathNormal = $folderUpload.$filenameNormal;
            // save image resize (Normal)
            $imageNormal    = ImageManagerStatic::make($image->getRealPath());              
            $imageNormal->resize(config('admin.images.normalResize_width'), config('admin.images.normalResize_height'));
            $imageNormal->save(public_path($filepathNormal));
            $result['filePathNormal']    = $filepathNormal;
        }
        return $result;
    }

    public static function uploadAvatar($requestImage, $name = null){
        $result             = [];
        if(!empty($requestImage)){
            // ===== folder upload
            $folderUpload   = config('admin.images.folderUpload');
            if(!file_exists(public_path($folderUpload))) mkdir(public_path($folderUpload), 777, true);
            // ===== image upload
            $image          = $requestImage;
            $extension      = $image->getClientOriginalExtension();
            // ===== set filename & checkexists (Small)
            $name           = $name ?? time();
            $fileName       = $name.'-avatar-500x500.'.$extension;
            $filePath       = $folderUpload.$fileName;
            // save image resize (Small)
            $imageUpload    = ImageManagerStatic::make($image->getRealPath());
            $imageUpload->resize(500, 500);
            $imageUpload->save(public_path($filePath));
            $result         = $filePath;
        }
        return $result;
    }

    public static function uploadLogo($requestImage, $name = null){
        $result             = [];
        if(!empty($requestImage)){
            // ===== folder upload
            $folderUpload   = config('admin.images.folderUpload');
            if(!file_exists(public_path($folderUpload))) mkdir(public_path($folderUpload), 777, true);
            // ===== image upload
            $image          = $requestImage;
            $extension      = $image->getClientOriginalExtension();
            // ===== set filename & checkexists
            $name           = $name ?? time();
            $filename       = $name.'-logo-'.config('admin.images.smallResize_width').'.'.$extension;
            $filepath       = $folderUpload.$filename;
            // save image resize
            $imageUpload    = ImageManagerStatic::make($image->getRealPath());
            $imageUpload->resize(660, 660);
            $imageUpload->save(public_path($filepath));
            $result         = $filepath;
        }
        return $result;
    }

    
}