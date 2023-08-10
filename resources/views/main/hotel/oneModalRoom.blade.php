<div class="modalHotelRoom_box customScrollBar-y">
    <div class="modalHotelRoom_box_body">
        <!-- icon close -->
        <div class="modalHotelRoom_box_body_close" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
            <i class="fa-solid fa-xmark"></i>
        </div>
        <div class="modalHotelRoom_box_body_gallery">
            <div class="modalHotelRoom_box_body_gallery_top">
                @php
                    $imageContent       = config('admin.images.default_750x460');
                    $contentImage       = Storage::disk('gcs')->get($room->images[0]->image);
                    if(!empty($contentImage)){
                        $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(600, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode();
                        $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                    }
                @endphp
                <img src="{{ $imageContent }}" alt="Ảnh phòng {{ $room->name }}" title="Ảnh phòng {{ $room->name }}" />
            </div>
            <div class="modalHotelRoom_box_body_gallery_bottom">
                @foreach($room->images as $image)
                    @php
                        $imageContent       = config('admin.images.default_750x460');
                        $contentImage       = Storage::disk('gcs')->get($image->image);
                        if(!empty($contentImage)){
                            $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(100, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode();
                            $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                        }
                    @endphp
                    <div class="modalHotelRoom_box_body_gallery_bottom_item">
                        <img src="{{ $imageContent }}" alt="Ảnh phòng {{ $room->name }}" title="Ảnh phòng {{ $room->name }}" />
                    </div>
                @endforeach
            </div>
        </div>
        <div class="modalHotelRoom_box_body_info">
            <!-- title -->
            <div class="modalHotelRoom_box_body_info_title">
                {{ $room->name }}
            </div>
            <!-- facilities -->
            @if(!empty($room->facilities)&&$room->facilities->isNotEmpty())
                <div class="modalHotelRoom_box_body_info_facilities">
                    <div class="modalHotelRoom_box_body_info_facilities_label">
                        Tiện nghi cơ bản:
                    </div>
                    <div class="modalHotelRoom_box_body_info_facilities_box">

                        @foreach($room->facilities as $facility)
                            <div class="modalHotelRoom_box_body_info_facilities_box_item">
                                {!! $facility->infoHotelRoomFacility->icon !!}
                                {{ $facility->infoHotelRoomFacility->name }}
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
            <!-- size -->
            @if(!empty($room->size))
                <div class="modalHotelRoom_box_body_info_size">
                    Kích thước phòng: <span class="highLight">{{ $room->size }} m<sup>2</sup></span>
                </div>
            @endif
            <!-- số người tối đa -->
            @if(!empty($room->number_people))
                <div class="modalHotelRoom_box_body_info_size">
                    Số người tối đa: 
                    <span class="highLight">
                        @for($i=0;$i<$room->number_people;++$i)
                            <i class="fa-solid fa-person"></i>
                        @endfor
                    </span>
                </div>
            @endif
            <!-- condition -->
            @if(!empty($room->condition))
                <div class="modalHotelRoom_box_body_info_condition">
                    {!! $room->condition !!}
                </div>
            @endif
            <!-- details -->
            @if(!empty($room->details)&&$room->details->isNotEmpty())
            <div class="modalHotelRoom_box_body_info_details">

                @foreach($room->details as $detail)
                <div class="modalHotelRoom_box_body_info_details_item">
                    <div class="modalHotelRoom_box_body_info_details_item_name">
                        {{ $detail->name }}
                    </div>
                    <div class="modalHotelRoom_box_body_info_details_item_detail">
                        {!! $detail->detail !!}
                    </div>
                </div>
                @endforeach

            </div>
            @endif
        </div>
    </div>
    <!-- button -->
    <div class="modalHotelRoom_box_footer">
        @if(!empty($room->price))
            <div class="modalHotelRoom_box_footer_price">
                @if(!empty($room->sale_off)&&!empty($room->price_old))
                    <div class="modalHotelRoom_box_footer_price_old">
                        <div class="modalHotelRoom_box_footer_price_old_number">
                            {{ number_format($room->price_old) }} <sup>đ</sup>
                        </div>
                        <div class="modalHotelRoom_box_footer_price_old_saleoff">
                            -{{ $room->sale_off }}%
                        </div>
                    </div>
                @endif
                <div class="modalHotelRoom_box_footer_price_now">
                    {{ number_format($room->price) }} <sup>đ</sup>
                </div>
            </div>
        @endif
        <div class="modalHotelRoom_box_footer_button">Đặt phòng này!</div>
    </div>
</div> 
<div class="modalHotelRoom_bg" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');"></div>