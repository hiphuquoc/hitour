<div class="hotelRoom_body_item_image" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
    <div class="hotelRoom_body_item_image_top">
        @php
            $imageContent       = config('admin.images.default_750x460');
            $contentImage       = Storage::disk('gcs')->get($room->images[0]->image);
            if(!empty($contentImage)){
                $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode();
                $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
            }
        @endphp
        <img src="{{ $imageContent }}" alt="{{ $room->name }}" title="{{ $room->name }}" />
    </div>
    <div class="hotelRoom_body_item_image_bottom">
        @foreach($room->images as $image)
            @php
                if($loop->index==0) continue;
                if($loop->index==4) break;
                $imageContent       = config('admin.images.default_750x460');
                $contentImage       = Storage::disk('gcs')->get($image->image);
                if(!empty($contentImage)){
                    $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();
                    $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                }
            @endphp
            <div class="hotelRoom_body_item_image_bottom_item">
                <img src="{{ $imageContent }}" alt="{{ $room->name }}" title="{{ $room->name }}" />
                @if($loop->index==3&&$room->images->count()>3) 
                    <div class="hotelRoom_body_item_image_bottom_item_number">
                        <span>+{{ ($room->images->count()-3) }}</span>
                        <i class="fa-solid fa-image"></i>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
<div class="hotelRoom_body_item_info">
    <div class="hotelRoom_body_item_info_name maxLine_2" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
        {{ $room->name }}
    </div>
    <div class="hotelRoom_body_item_info_desc">
        {!! $room->condition ?? null !!}
        <div>Kích thước phòng: {{ $room->size }} m<sup>2</sup></div>
    </div>
    <div class="hotelRoom_body_item_info_facilities">
        @foreach($room->facilities as $facility)
            @if(!empty($facility->infoHotelRoomFacility->icon)&&!empty($facility->infoHotelRoomFacility->name))
                <div class="hotelRoom_body_item_info_facilities_item">
                    {!! $facility->infoHotelRoomFacility->icon !!}
                    <span>{{ $facility->infoHotelRoomFacility->name }}</span>
                </div>
            @endif
        @endforeach
    </div>
</div>
<div class="hotelRoom_body_item_numberpeople">
    @for($i=0;$i<$room->number_people;++$i)
        <i class="fa-solid fa-person"></i>
    @endfor
    
</div>
<div class="hotelRoom_body_item_action">
    @if(!empty($room->price))
    <div class="hotelRoom_body_item_action_price">
        @if(!empty($room->sale_off)&&!empty($room->price_old))
            <div class="hotelRoom_body_item_action_price_old">
                <div class="hotelRoom_body_item_action_price_old_number">
                    {{ number_format($room->price_old) }} <sup>đ</sup>
                </div>
                <div class="hotelRoom_body_item_action_price_old_saleoff">
                    -{{ $room->sale_off }}%
                </div>
            </div>
        @endif
        <div class="hotelRoom_body_item_action_price_now">
            {{ number_format($room->price) }} <sup>đ</sup>
        </div>
    </div>
    @endif
    <div class="hotelRoom_body_item_action_button">đặt phòng</div>
</div>