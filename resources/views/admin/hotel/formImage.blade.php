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
        {{-- @if(!empty($item['images'])&&$type==='edit') --}}
            @foreach($item['images'] as $image)
                @if(Storage::path($image['image']))
                    <div id="imageHotelInfo_{{ $image->id }}">
                        <img src="{{ Storage::url($image['image_small']) }}" />
                        <i class="fa-solid fa-circle-xmark" onClick="removeImageHotelInfo('{{ $image['id'] ?? null }}');"></i>
                        @php
                            $infoPixel  = getimagesize(Storage::path($image['image']));
                            $extension  = pathinfo(Storage::path($image['image']))['extension'];
                            $infoSize   = round(filesize(Storage::path($image['image']))/1024, 2);
                        @endphp
                        <div style="margin-top:0.25rem;color:#789;display:flex;justify-content:space-between;">
                            <span>.{{ $extension }}</span>
                            <span>{{ $infoPixel[0] }}*{{ $infoPixel[1] }} px</span>
                            <span>{{ $infoSize }} MB</span>
                        </div>
                    </div>
                @endif
            @endforeach
        {{-- @endif --}}
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

        function removeImageHotelInfo(id){
            $.ajax({
                url         : "{{ route('admin.hotel.removeImageHotelInfo') }}",
                type        : "POST",
                dataType    : "html",
                data        : { 
                    '_token'        : '{{ csrf_token() }}',
                    hotel_image_id  : id
                }
            }).done(function(data){
                if(data==true) $('#imageHotelInfo_'+id).remove();
            });
        }

    </script>
@endpush