@php
    $info = pathinfo($image);
@endphp
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <input type="hidden" id="basename_image" name="basename_image" value="{{ basename($image) }}" />
            <input type="hidden" id="filename_image" name="filename_image" value="{{ $info['filename'] }}" />
            <input class="form-control" type="file" id="image_new" name="image_new" onChange="readURL(this,'js_readURL_idShow_modal');">
        </div>
        <div class="formBox_full_item">
            <div class="columnBox">
                <div class="columnBox_item" style="justify-content:center;align-items:center;">
                    <div class="columnBox_item_column" style="height:150px;background:url(/images/admin/background-of-image.jpg) no-repeat;background-size:100% 100%;">
                        <img src="{{ $image }}?{{ time() }}" alt="" title="" style="width:100%;border:1px solid #d1d1d1;object-fit:contain;height:100%;" />
                    </div>
                    <div class="columnBox_item_column" style="flex:0 0 40px;text-align:center;">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <div class="columnBox_item_column" style="height:150px;background:url(/images/admin/background-of-image.jpg) no-repeat;background-size:100% 100%;">
                        <img id="js_readURL_idShow_modal" src="{{ config('admin.images.default_750x460') }}" alt="" title="" style="width:100%;border:1px solid #d1d1d1;object-fit:contain;height:100%;" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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