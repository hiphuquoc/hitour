<!-- One Row -->
<div class="formBox_full_item">
    <span data-toggle="tooltip" data-placement="top" title="
        Đây là Ảnh đại diện dùng làm Ảnh đại diện trên website, Ảnh đại diện ngoài Google, Ảnh đại diện khi Share link
    ">
        <i class="explainInput" data-feather='alert-circle'></i>
        <label class="form-label" for="image">Ảnh Logo <span style="font-weight:700;">555 * 210 px</span></label>
    </span>
    <input class="form-control" type="file" id="image" name="image" onchange="readURL(this, 'imageUpload');">
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
    <div class="imageUpload">
        @php
            $image  = config('admin.images.default_750x460');
            if(!empty($item->company_logo)&&$type!='copy') $image = $item->company_logo;
        @endphp
        <img id="imageUpload" src="{{ $image }}" />
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">

        function readURL(input, idShow) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+idShow).attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

    </script>
@endpush