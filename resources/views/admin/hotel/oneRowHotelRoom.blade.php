<div id="hotelRoom_{{ $item->id }}" class="hotelRoomBox_item">
    <div class="hotelRoomBox_item_img">
        @foreach($item->images as $image)
            @php
                $base64Image       = config('admin.images.default_750x460');
                $contentImage      = Storage::disk('gcs')->get($image['image']);
                if(!empty($contentImage)){
                    $thumbnail     = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();
                    $base64Image   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                }
            @endphp
            <img src="{{ $base64Image }}" />
        @endforeach
    </div>
    <div class="hotelRoomBox_item_info">
        <div class="hotelRoomBox_item_info_title highLight">{{ $item->name }}</div> 
        <div>Kích thước: {{ $item->size }}m<sup>2</sup></div>
        @if(!empty($item->condition))
            <div class="hotelRoomBox_item_info_desc">{!! $item->condition !!}</div> 
        @endif
        <div class="hotelRoomBox_item_info_facility">
            @foreach($item->facilities as $facility)
                <span>{!! !empty($facility->infoHotelRoomFacility->icon)&&!empty($facility->infoHotelRoomFacility->name) ? $facility->infoHotelRoomFacility->icon.$facility->infoHotelRoomFacility->name : null !!}</span>
            @endforeach
        </div>
        @if(!empty($item->prices)&&$item->prices->isNotEmpty())
            <div class="hotelRoomBox_item_info_price">
                @foreach($item->prices as $price)
                    <div class="hotelRoomBox_item_info_price_item">
                        Số người: <span class="highLight">{{ $price->number_people }}</span> - Giá: <span class="highLight">{{ number_format($price->price) }} <sup>đ</sup></span> 
                        @if(!empty($price->price_old)&&!empty($price->sale_off))
                            (<span style="text-decoration:line-through;">{{ number_format($price->price_old) }}</span>) 
                            <span style="background:red;color:#fff;border-radius:5px;padding:0.15rem 0.5rem;font-size:0.9rem;">-{{ $price->sale_off }}%</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="hotelRoomBox_item_action">
        <div class="icon-wrapper iconAction">
            <a href="#" data-bs-toggle="modal" data-bs-target="#formModalHotelRoom" onclick="loadFormHotelRoom({{ $item->id }})">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <div>Sửa</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <div class="actionDelete" onclick="deleteRoom({{ $item->id }});">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                <div>Xóa</div>
            </div>
        </div>

    </div>
</div>