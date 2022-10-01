<?php

namespace App\Http\Controllers;

use App\Models\SystemFile;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\Storage;

use App\Services\BuildInsertUpdateModel;

class AdminImageController extends Controller {

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel  = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params['search_name']  = $request->get('search_name') ?? null;
        $list                   = null;
        if(!empty($params['search_name'])){
            $list               = glob(Storage::path(config('admin.images.folderUpload')).'*'.$params['search_name'].'*');
        }
        return view('admin.image.list', compact('list', 'params'));
    }

    public function loadImage(Request $request){
        if(!empty($request->get('image_name'))){
            $tmp                = glob(Storage::path(config('admin.images.folderUpload').$request->get('image_name').'*'));
            $item               = $tmp[0];
            return view('admin.image.oneRow', compact('item'));
        }
    }

    public function loadModal(Request $request){
        $result             = [];
        if(!empty($request->get('type'))&&!empty($request->get('basename'))){
            $image          = Storage::url(config('admin.images.folderUpload')).$request->get('basename');
            if($request->get('type')==='changeName'){
                $head       = 'Sửa tên ảnh';
                $body       = view('admin.image.formModalChangeName', compact('image'))->render();
                $action     = route('admin.image.changeName');
            }else if($request->get('type')=='changeImage'){
                $head       = 'Thay đổi ảnh';
                $body       = view('admin.image.formModalChangeImage', compact('image'))->render();
                $action     = route('admin.image.changeImage');
            }
        }
        $result['head']     = $head;
        $result['body']     = $body;
        $result['action']   = $action;
        return json_encode($result);
    }

    public function removeImage(Request $request){
        $flag               = false;
        if(!empty($request->get('basename_image'))){
            $imagePath      = Storage::path(config('admin.images.folderUpload')).$request->get('basename_image');
            /* remove folder */
            if(file_exists($imagePath)) {
                if(unlink($imagePath)) $flag = true;
            }
            /* remove database */
            $imageUrl       = Storage::url(config('admin.images.folderUpload')).$request->get('basename_image');
            SystemFile::select('*')
                    ->where('file_path', $imageUrl)
                    ->delete();
        }
        return $flag;
    }

    public function changeName(Request $request){
        if(!empty($request->get('basename_old'))&&!empty($request->get('name_new'))){
            $filenameOld    = $request->get('basename_old');
            $tmp            = explode(config('admin.images.keyType'), pathinfo($filenameOld)['filename']);
            $typeImageOld   = null;
            if(key_exists(end($tmp), config('admin.images.type'))) $typeImageOld = config('admin.images.keyType').end($tmp);
            /* thông tin image cũ */
            $imageOld       = Storage::path(config('admin.images.folderUpload')).$filenameOld;
            $infoImageOld   = pathinfo($imageOld);
            $extension      = $infoImageOld['extension'];
            /* thông tin image mới */
            $filenameNew    = $request->get('name_new').$typeImageOld.'.'.$extension;
            $arrayFlag      = $this->checkImageExists($filenameOld, $filenameNew);
            /* rename */
            if($arrayFlag['flag']==true){
                /* thay trong folder */
                rename(Storage::path(config('admin.images.folderUpload')).$filenameOld, Storage::path(config('admin.images.folderUpload')).$filenameNew);
                /* trả kết quả */
                $result['flag']     = true;
                $result['message']  = 'Thay tên ảnh thành công!';
                return json_encode($result);
            }else {
                return json_encode($arrayFlag);
            }
            /* không thay trong database vì tính năng này hiện chỉ dùng cho các ảnh upload bằng manager image */
        }
        $result['flag']             = false;
        $result['message']          = 'Tên ảnh cũ /mới không được để trống!';
        return json_encode($result);
    }

    public function changeImage(Request $request){
        $flag                       = false;
        $message                    = '';
        if(!empty($request->get('basename_image'))&&!empty($request->file('image_new'))){
            /* thông tin ảnh cũ */
            $imagePathOld           = Storage::path(config('admin.images.folderUpload')).$request->get('basename_image');
            $fileSaved              = self::uploadImage($request->file('image_new'), $imagePathOld);
            if(!empty($fileSaved)) $flag = true;
        }
        $result['flag']             = $flag;
        $result['message']          = $message;
        return json_encode($result);
    }

    public function checkImageExists($basenameOld, $basenameNew){
        $result                     = [];
        if(!empty($basenameOld)&&!empty($basenameNew)){
            /* kiểm tra trường hợp cả 2 trùng nhau */
            if($basenameOld==$basenameNew) {
                $result['flag']     = false;
                $result['message']  = 'Tên ảnh mới trùng với Tên ảnh cũ!';
                return $result;
            }
            /* kiểm tra trường hợp trùng trong thư mục */
            if(file_exists(public_path($basenameNew))){
                $result['flag']     = false;
                $result['message']  = 'Ảnh mới trùng với một ảnh khác trong thư mục!';
                return $result;
            }
            /* kiểm tra trường hợp trùng trong database */
            $tmp                    = SystemFile::select('*')
                                        ->where('file_name', $basenameNew)
                                        ->first();
            if(!empty($tmp)){
                $result['flag']     = false;
                $result['message']  = 'Ảnh mới trùng với một ảnh khác trong CSDL!';
                return $result;
            }
            /* hợp lệ */
            $result['flag']         = true;
            $result['message']      = null;
        }
        return $result;
    }

    public function uploadImages(Request $request){
        $count                  = 0;
        $content                = '';
        if(!empty($request->file('image_upload'))){
            foreach($request->file('image_upload') as $image){
                $imageName      = $image->getClientOriginalName();
                $imageFileName  = \App\Helpers\Charactor::convertStrToUrl(pathinfo($imageName)['filename']);
                $extension      = config('admin.images.extension');
                $filePathUpload = Storage::path(config('admin.images.folderUpload')).$imageFileName.'.'.$extension;
                $fileSaved      = self::uploadImage($image, $filePathUpload, 'copy', '-type-manager-upload');
                $content        .= view('admin.image.oneRow', [
                    'item'  => $fileSaved,
                    'style' => 'box-shadow: 0 0 5px rgb(0, 123, 255)'
                ]);
                ++$count;
            }
        }
        $result['count']    = $count;
        $result['content']  = $content;
        return json_encode($result);
    }

    public static function uploadImage($requestImage, $filePathUpload, $action = 'rewrite', $addType = null){
        $fileSaved          = null;
        if(!empty($requestImage)){
            /* thêm type cho filePath */
            $imageFileName  = pathinfo($filePathUpload)['filename'];
            $extension      = config('admin.images.extension');
            $fileNameUpload = $imageFileName.$addType.'.'.$extension;
            $filePathUpload = Storage::path(config('admin.images.folderUpload')).$fileNameUpload;
            /* trường hợp copy */
            if($action=='copy') {
                if(file_exists($filePathUpload)){
                    $fileNameUpload = $imageFileName.'-'.time().$addType.'.'.$extension;
                    $filePathUpload = Storage::path(config('admin.images.folderUpload')).$fileNameUpload;
                }
            }
            /* thêm ảnh */     
            // dd($filePathUpload);     
            ImageManagerStatic::make($requestImage->getRealPath())
                ->save($filePathUpload);
            $fileSaved      = $filePathUpload;
        }
        return $fileSaved;
    }

    // public static function toolRename(){
    //     ini_set('max_execution_time', '0');
    //     $data           = glob(Storage::path('public/images/backup/'.'*'));
    //     foreach($data as $image){
    //         $tmp        = pathinfo($image);
    //         $newName    = $tmp['dirname'].'/'.$tmp['filename'].'-type-manager-upload'.'.'.$tmp['extension'];
    //         rename($image, $newName);
    //     }
    // }

}
