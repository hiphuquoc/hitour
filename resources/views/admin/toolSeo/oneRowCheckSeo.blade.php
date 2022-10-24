@if(!empty($item->checkSeos)&&$item->checkSeos->isNotEmpty())
@php
    $dataCheckSeo = [];
    foreach($item->checkSeos as $check){
        if($check->type=='heading') $dataCheckSeo['heading'][] = $check;
        if($check->type=='link') $dataCheckSeo['link'][] = $check;
        if($check->type=='image') $dataCheckSeo['image'][] = $check;
    }
    /* xác định lỗi nặng nhất của heading */
    $errorHeading   = 0;
    if(!empty($dataCheckSeo['heading'])){
        foreach($dataCheckSeo['heading'] as $heading){
            if($heading->error_type>$errorHeading) $errorHeading = $heading->error_type;
        }
    }
    /* xác định lỗi nặng nhất của link */
    $errorLink      = 0;
    if(!empty($dataCheckSeo['link'])){
        foreach($dataCheckSeo['link'] as $link){
            if($link->error_type>$errorLink) $errorLink = $link->error_type;
        }
    }
    /* xác định lỗi nặng nhất của image */
    $errorImage     = 0;
    if(!empty($dataCheckSeo['image'])){
        foreach($dataCheckSeo['image'] as $image){
            if($image->error_type>$errorImage) $errorImage = $image->error_type;
        }
    }
@endphp
<tr id="row_{{ $item->id }}" style="{{ $style ?? null }}">
    <td class="text-center">{{ $no ?? '-' }}</td>
    <td>
        <div>
            <b>{{ $item->title }}</b> - {{ $item->slug_full }}
        </div>
        <div>
            <a href="https://{{ $item->url }}" target="_blank">{{ $item->url }}</a>
        </div>
    </td>
    <td>
        <div class="flexBox">
            <div class="flexBox_item iconWithNumber" data-bs-toggle="modal" data-bs-target="#modalBox" onClick="loadDetailCheckSeo({{ $item->id }}, 'heading');">
                <i class="fa-solid fa-heading"></i>
                <div class="iconWithNumber_number" style="background:{{ config('admin.error_color')[$errorHeading] }}">{{ !empty($dataCheckSeo['heading']) ? count($dataCheckSeo['heading']) : 0 }}</div>
            </div>
            <div class="flexBox_item iconWithNumber" data-bs-toggle="modal" data-bs-target="#modalBox" onClick="loadDetailCheckSeo({{ $item->id }}, 'link');">
                <i class="fa-solid fa-link"></i>
                <div class="iconWithNumber_number" style="background:{{ config('admin.error_color')[$errorLink] }}">{{ !empty($dataCheckSeo['link']) ? count($dataCheckSeo['link']) : 0 }}</div>
            </div>
            <div class="flexBox_item iconWithNumber" data-bs-toggle="modal" data-bs-target="#modalBox" onClick="loadDetailCheckSeo({{ $item->id }}, 'image');">
                <i class="fa-regular fa-image"></i>
                <div class="iconWithNumber_number" style="background:{{ config('admin.error_color')[$errorImage] }}">{{ !empty($dataCheckSeo['image']) ? count($dataCheckSeo['image']) : 0 }}</div>
            </div>
        </div>
    </td>
</tr>
@endif