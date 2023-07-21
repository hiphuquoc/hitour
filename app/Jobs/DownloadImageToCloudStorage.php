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

class DownloadImageToCloudStorage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $urlImage;
    private $fileName;

    public function __construct($urlImage, $fileName){
        $this->urlImage     = $urlImage;
        $this->fileName     = $fileName;
    }

    public function handle(){
        $flag               = false;
        if(!empty($this->urlImage)&&!empty($this->fileName)){
            $image          = ImageManagerStatic::make($this->urlImage);
            $flag           = Storage::disk('gcs')->put($this->fileName, $image->stream());
        }
        return $flag;
    }
}
