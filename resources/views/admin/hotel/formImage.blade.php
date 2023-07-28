<!-- One Row -->
<div class="formBox_full_item">
    <span data-toggle="tooltip" data-placement="top" title="
        Đây là ảnh dùng làm slider hiển thị ở phần giới thiệu và phần ảnh đẹp của Tour
    ">
        <i class="explainInput" data-feather='alert-circle'></i>
        <label class="form-label">Ảnh Hotel <span data-bs-toggle="modal" data-bs-target="#formModalDownloadImageHotelInfo" style="color:#26cf8e;font-size:1rem;"><i class="fa-solid fa-download"></i> Tải tự động</span></label>
    </span>
    <input class="form-control" type="file" id="gallery" name="gallery[]" onChange="readURLs(this, 'galleryUpload');" multiple />
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
    <div id="galleryUpload" class="imageUpload">
        @if(!empty($item->images)&&$item->images->isNotEmpty())
            <div id="js_removeAllImageHotelInfo_box" style="position:relative;">
                @php
                    $imageFirst     = config('admin.images.default_750x460');
                    $contentImage   = Storage::disk('gcs')->get($item->images[0]->image);
                    if(!empty($contentImage)){
                        $thumbnail  = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(200, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode();
                        $imageFirst = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                    }
                @endphp
                <img src="{{ $imageFirst }}" />
                @if(count($item->images)>1)
                    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);font-size:2rem;color:#fff;font-weight:bold;border-radius:50%;border:3px solid #fff;padding:0.2rem 1rem;background:rgba(0,0,0,0.2);">{{ count($item->images) }}</span>
                @endif
                <i class="fa-solid fa-circle-xmark" onclick="removeAllImageHotelInfo();"></i>
            </div>
        @endif
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">

        function downloadImageHotelInfo(){
            const contentImage = $('#content_image').val();
            $.ajax({
                url         : "{{ route('admin.hotel.downloadImageHotelInfo') }}",
                type        : "post",
                dataType    : "json",
                data        : { 
                    '_token'        : '{{ csrf_token() }}',
                    content_image : contentImage
                }
            }).done(function(data){
                console.log(data);
                for(let i = 0; i<data.length; i++){
                    var div         = document.createElement("div");
                    div.innerHTML   = '<img src="'+data[i]+'" />';
                    div.innerHTML   += '<input type="hidden" name="images[]" value="'+data[i]+'" />';
                    $('#galleryUpload').append(div);
                }
                $('#formModalDownloadImageHotelInfo').modal('hide');
            });
        }

        function readURLs(input, idShow) {
            if(input.files){
                const data          = input.files;
                for(let i = 0; i<data.length; i++){
                    let file        = data[i];
                    if (!file.type.match('image')) continue;
                    var picReader   = new FileReader();
                    picReader.addEventListener("load", function (event) {
                        var picFile = event.target;
                        var div     = document.createElement("div");
                        div.innerHTML = '<img src="'+picFile.result+'" />';
                        $('#'+idShow).append(div);
                    });
                    picReader.readAsDataURL(file);
                }
            }
        }

        function removeAllImageHotelInfo(){
            const idHotelInfo = $('#hotel_info_id').val();
            loaddingFullScreen();
            $.ajax({
                url         : "{{ route('admin.hotel.removeAllImageHotelInfo') }}",
                type        : "POST",
                dataType    : "html",
                data        : { 
                    '_token'        : '{{ csrf_token() }}',
                    hotel_info_id   : idHotelInfo
                }
            }).done(function(data){
                if(data==true) {
                    $('#js_removeAllImageHotelInfo_box').remove();
                    loaddingFullScreen();
                }
            });
        }

    </script>
@endpush