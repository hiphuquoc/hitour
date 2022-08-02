<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;

use Intervention\Image\ImageManagerStatic;
use App\Models\SystemFile;

use Illuminate\Support\Facades\DB;

class AdminSliderController extends Controller {

    public static function uploadSlider($arrayImage, $params = null){
        $result     = [];
        if(!empty($arrayImage)){
            // ===== folder upload
            $folderUpload       = config('admin.images.folderUpload');
            if(!file_exists(public_path($folderUpload))) mkdir(public_path($folderUpload), 777, true);
            $i                  = 1;
            foreach($arrayImage as $image){
                // ===== image upload
                $extension      = $image->getClientOriginalExtension();
                // ===== set filename & checkexists (Small)
                $name           = $params['name'] ?? time();
                $filename       = $name.'-slider-'.time().'-'.$i.'.'.$extension;
                $filepath       = $folderUpload.$filename;
                // save image resize (Small)
                $imageSlider    = ImageManagerStatic::make($image->getRealPath());
                $imageSlider->save(public_path($filepath));
                $result[]       = $filepath;
                /* cập nhật thông tin CSDL */
                $arrayInsert    = [];
                $arrayInsert['attachment_id']   = $params['attachment_id'] ?? 0;
                $arrayInsert['relation_table']  = $params['relation_table'] ?? null;
                $arrayInsert['file_name']       = $filename;
                $arrayInsert['file_path']       = $filepath;
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
                $filepath   = $infofile['file_path'] ?? null;
                if(!empty($filepath)&&file_exists($filepath)) unlink($filepath);
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

}
