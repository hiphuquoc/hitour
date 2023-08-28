<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Intervention\Image\ImageManagerStatic;
use Illuminate\Support\Facades\Storage;

class DownloadHotelImageToCloudStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;
    /* data nhận vào 
        - url_image là url địa chỉ ảnh
        - file_name đường dẫn ảnh lưu trữ
        - reference_id
        - reference_type
    */

    public function __construct($data){
        $this->data     = $data;
    }

    public function handle(){
        $flag               = false;
        /* lưu ảnh vào cloud */
        if(!empty($this->data['url_image'])&&!empty($this->data['file_name'])){
            $image          = ImageManagerStatic::make($this->data['url_image']);
            $flag           = Storage::disk('gcs')->put($this->data['file_name'], $image->stream());
        }
        /* lưu thông tin ảnh vừa tải vào CSDL */
        $idImage            = null;
        if($flag==true){
            \App\Models\HotelImage::insertItem([
                'reference_type'    => $this->data['reference_type'],
                'reference_id'      => $this->data['reference_id'],
                'image'             => $this->data['file_name'],
                'image_small'       => null
            ]);
        }
        return $idImage;
    }
}
