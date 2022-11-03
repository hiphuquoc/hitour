<!-- One Row -->
<div class="formBox_full_item">
    <span data-toggle="tooltip" data-placement="top" title="
        Đây là Iframe Video Youtube
    ">
        <i class="explainInput" data-feather='alert-circle'></i>
        <label class="form-label inputRequired" for="video">Iframe Youtube</label>
    </span>
    <textarea class="form-control" name="video" rows="3" placeholder="Bỏ thẻ hight và width của thẻ Iframe">{{ $item->seo->video ?? null }}</textarea>
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
    {{-- <div class="imageUpload">
        @if(!empty($item->seo->image)&&file_exists(public_path($item->seo->image))&&$type!='copy')
            @php
                $image      = $item->seo->image ?? $item->seo->image_small;
                $infoPixel  = getimagesize(public_path($image));
                $extension  = pathinfo(public_path($image))['extension'];
                $infoSize   = round(filesize(public_path($image))/1024, 2);
            @endphp
            <img id="imageUpload" src="{{ $image }}?{{ time() }}" />
            <div style="margin-top:0.25rem;color:#789;display:flex;justify-content:space-between;">
                <span>.{{ $extension }}</span>
                <span>{{ $infoPixel[0] }}*{{ $infoPixel[1] }} px</span>
                <span>{{ $infoSize }} MB</span>
            </div>
        @else
            <img id="imageUpload" src="{{ config('admin.images.default_750x460') }}" />
        @endif
    </div> --}}
</div>

@push('scripts-custom')
    <script type="text/javascript">

        // function readURL(input, idShow) {
        //     if (input.files && input.files[0]) {
        //         var reader = new FileReader();

        //         reader.onload = function (e) {
        //             $('#'+idShow).attr('src', e.target.result);
        //         };

        //         reader.readAsDataURL(input.files[0]);
        //     }
        // }

    </script>
@endpush