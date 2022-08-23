@if(!empty($item))
    @php
        $infoSize       = getimagesize($item);
        $infoImage      = pathinfo($item);
        $urlImage       = Storage::url(config('admin.images.folderUpload')).basename($item);
        /* phân loại ảnh */
        $arrayAction    = \App\Helpers\Image::getActionImageByType($item);
    @endphp
    <div id="{{ $infoImage['filename'] }}" class="imageBox_item" style="{{ $style ?? null }}">
        <div class="imageBox_item_image">
            <img src="{{ $urlImage }}?{{ time() }}" alt="" title="" />
        </div>
        <div class="imageBox_item_content">
            <div class="imageBox_item_content_text">
                <div style="margin-bottom:0.5rem;">{{ $urlImage }}</div>
                <div>width: {{ !empty($infoSize[0]) ? $infoSize[0].'px' : '-' }}</div>
                <div>height: {{ !empty($infoSize[1]) ? $infoSize[1].'px' : '-' }}</div>
                <div>size: 19.4kb</div>
            </div>
            <div class="imageBox_item_content_action">
                <!-- copy đường dẫn -->
                @if(in_array('copy_url', $arrayAction))
                    <div id="js_copyClipboard_{{ $infoImage['filename'] }}" data-bs-placement="top" data-bs-original-title="Đã copy ảnh!"><i class="fa-regular fa-folder-open"></i></div>
                @else 
                    <div><i class="fa-regular fa-folder-open"></i></div>
                @endif
                <!-- thay ảnh -->
                @if(in_array('change_image', $arrayAction))
                    <div data-bs-toggle="modal" data-bs-target="#modalImage" onClick="loadModal('changeImage', '{{ basename($item) }}');"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                @else 
                    <div class="disable" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ảnh này không được phép dùng chức năng này!"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                @endif
                <!-- thay tên ảnh -->
                @if(in_array('change_name', $arrayAction))
                    <div data-bs-toggle="modal" data-bs-target="#modalImage" onClick="loadModal('changeName', '{{ basename($item) }}');"><i class="fa-solid fa-pen-to-square"></i></div>
                @else 
                    <div class="disable" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ảnh này không được phép dùng chức năng này!"><i class="fa-solid fa-pen-to-square"></i></div>
                @endif
                <!-- xóa ảnh -->
                @if(in_array('delete', $arrayAction))
                    <div class="remove" onClick="removeImage('{{ $infoImage['filename'] }}', '{{ basename($item) }}');"><i class="fa-solid fa-trash-can"></i></div>
                @else 
                    <div class="disable" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Ảnh này không được phép dùng chức năng này!"><i class="fa-solid fa-trash-can"></i></div>
                @endif
            </div>
        </div>
    </div>
@endif