@php
    $info = pathinfo($image);
@endphp
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="basename_old">Tên cũ</label>
            <input type="text" class="form-control" name="name_old" value="{{ $info['filename'] }}" disabled>
            <input type="hidden" class="form-control" id="basename_old" name="basename_old" value="{{ $info['basename'] }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="name_new">Tên mới</label>
            <input type="text" class="form-control" id="name_new" name="name_new" value="" required>
        </div>
    </div>
</div>