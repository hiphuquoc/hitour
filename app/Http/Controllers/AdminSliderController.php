<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Intervention\Image\ImageManagerStatic;
use App\Models\SystemFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminSliderController extends Controller {

    public static function uploadSlider($arrayImage, $params = null){
        $result     = [];
        if(!empty($arrayImage)){
            // ===== folder upload
            $folderUpload       = config('admin.images.folderUpload');
            $extension          = config('admin.images.extension');
            $name               = $params['name'] ?? time();
            $i                  = 1;
            foreach($arrayImage as $image){
                // ===== set filename & checkexists (Small)
                $filename       = $folderUpload.$name.'-slider-'.time().'-'.$i.'.'.$extension;
                $imageSlider    = ImageManagerStatic::make($image->getRealPath())
                    ->encode($extension, config('admin.images.quality'))
                    ->save(Storage::path($filename));
                $result[]       = Storage::url($filename);
                /* cập nhật thông tin CSDL */
                $arrayInsert    = [];
                $arrayInsert['attachment_id']   = $params['attachment_id'] ?? 0;
                $arrayInsert['relation_table']  = $params['relation_table'] ?? null;
                $arrayInsert['file_name']       = $filename;
                $arrayInsert['file_path']       = Storage::url($filename);
                $arrayInsert['file_extension']  = $extension;
                $arrayInsert['file_type']       = 'slider';
                SystemFile::insertItem($arrayInsert);
                ++$i;
            }
        }
        return $result;
    }

    public static function removeSlider(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                /* xóa file */
                $infofile   = SystemFile::find($request->get('id'));
                $fileUrl    = $infofile['file_path'] ?? null;
                $filePath   = Storage::path(config('admin.images.folderUpload').basename($fileUrl));
                if(file_exists($filePath)) @unlink($filePath);
                /* xóa khỏi CSDL */
                $flag       = SystemFile::removeItem($request->get('id'));
                DB::commit();
                return $flag;
            } catch(\Exception $exception) {
                DB::rollBack();
                return false;
            }
        }
    }

    public static function removeSliderById($id){
        if(!empty($id)){
            try {
                DB::beginTransaction();
                /* xóa file */
                $infofile   = SystemFile::find($id);
                $fileUrl    = $infofile['file_path'] ?? null;
                $filePath   = Storage::path(config('admin.images.folderUpload').basename($fileUrl));
                if(file_exists($filePath)) @unlink($filePath);
                /* xóa khỏi CSDL */
                $flag       = SystemFile::removeItem($id);
                DB::commit();
                return $flag;
            } catch(\Exception $exception) {
                DB::rollBack();
                return false;
            }
        }
    }

}
