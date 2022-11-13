<!-- One Row -->
<div class="formBox_full_item">
    <span data-toggle="tooltip" data-placement="top" title="
        Đây là ảnh dùng làm slider hiển thị trên website ở phần đầu
    ">
        <i class="explainInput" data-feather='alert-circle'></i>
        <label class="form-label" for="slider">Ảnh slider <span style="font-weight:700;">1920 * 300 px</span></label>
    </span>
    <input class="form-control" type="file" id="slider" name="slider[]" onChange="readURLs(this, 'sliderUpload');" multiple />
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
    <div id="sliderUpload" class="imageUpload">
        @if(!empty($item->files)&&$type==='edit')
            @foreach($item->files as $file)
                @if($file->file_type==='slider'&&file_exists(public_path($file->file_path)))
                    <div id="slider-{{ $file->id }}">
                        <img src="{{ $file->file_path }}" />
                        <i class="fa-solid fa-circle-xmark" onClick="removeSlider('{{ $file->id }}');"></i>
                        @php
                            $infoPixel  = getimagesize(public_path($file->file_path));
                            $extension  = pathinfo(public_path($file->file_path))['extension'];
                            $infoSize   = round(filesize(public_path($file->file_path))/1024, 2);
                        @endphp
                        <div style="margin-top:0.25rem;color:#789;display:flex;justify-content:space-between;">
                            <span>.{{ $extension }}</span>
                            <span>{{ $infoPixel[0] }}*{{ $infoPixel[1] }} px</span>
                            <span>{{ $infoSize }} MB</span>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">

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

        function removeSlider(id){
            $.ajax({
                url         : "{{ route('admin.slider.removeSlider') }}",
                type        : "post",
                dataType    : "html",
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    id : id
                }
            }).done(function(data){
                if(data==true) $('#slider-'+id).remove();
            });
        }

    </script>
@endpush