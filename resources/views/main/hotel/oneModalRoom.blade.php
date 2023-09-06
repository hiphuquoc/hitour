<div class="modalHotelRoom_box customScrollBar-y">
    <!-- icon close -->
    <div class="modalHotelRoom_box_close" onClick="openCloseModal('js_loadHotelPrice_modal_{{ $price->id }}');">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="modalHotelRoom_box_body">
        <div class="modalHotelRoom_box_body_gallery">
            @if(!empty($price->room->images)&&$price->room->images->isNotEmpty())
                <div class="modalHotelRoom_box_body_gallery_top">
                    @php
                        $imageContent       = config('admin.images.default_750x460');
                        $contentImage       = Storage::disk('gcs')->get($price->room->images[0]->image);
                        if(!empty($contentImage)){
                            $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(750, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode();
                            $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                        }
                    @endphp
                    <img src="{{ $imageContent }}" alt="Ảnh phòng {{ $price->room->name }}" title="Ảnh phòng {{ $price->room->name }}" />
                </div>
                <div class="modalHotelRoom_box_body_gallery_bottom">
                    @foreach($price->room->images as $image)
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
                            <img src="{{ $imageContent }}" alt="Ảnh phòng {{ $price->room->name }}" title="Ảnh phòng {{ $price->room->name }}" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modalHotelRoom_box_body_info">
            <!-- title -->
            <div class="modalHotelRoom_box_body_info_title">
                {{ $price->room->name }}
            </div>
            <!-- facilities -->
            @if(!empty($price->room->facilities)&&$price->room->facilities->isNotEmpty())
                <div class="modalHotelRoom_box_body_info_facilities">
                    <div class="modalHotelRoom_box_body_info_facilities_label">
                        Tiện nghi cơ bản:
                    </div>
                    <div class="modalHotelRoom_box_body_info_facilities_box">

                        @foreach($price->room->facilities as $facility)
                            <div class="modalHotelRoom_box_body_info_facilities_box_item">
                                {!! $facility->infoHotelRoomFacility->icon !!}
                                {{ $facility->infoHotelRoomFacility->name }}
                            </div>
                        @endforeach

                    </div>
                </div>
            @endif
            <!-- size -->
            @if(!empty($price->size))
                <div class="modalHotelRoom_box_body_info_size">
                    Kích thước phòng: <span class="highLight">{{ $room->size }} m<sup>2</sup></span>
                </div>
            @endif
            <!-- số người tối đa -->
            @if(!empty($price->number_people))
                <div class="modalHotelRoom_box_body_info_size">
                    Số người tối đa: 
                    <span class="highLight">
                        @for($i=0;$i<$price->number_people;++$i)
                            <i class="fa-solid fa-person"></i>
                            @php
                                if($price->number_people>4){
                                    echo 'x'.$price->number_people;
                                    break;
                                }
                            @endphp
                        @endfor
                    </span>
                </div>
            @endif
            <!-- condition -->
            @if(!empty($price->description))
                <div class="modalHotelRoom_box_body_info_condition">
                    {!! $price->description !!}
                </div>
            @endif
            <!-- details -->
            @if(!empty($price->room->details)&&$price->room->details->isNotEmpty())
            <div class="modalHotelRoom_box_body_info_details">

                @foreach($price->room->details as $detail)
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
        @if(!empty($price->price))
            <div class="modalHotelRoom_box_footer_price">
                @if(!empty($price->sale_off)&&!empty($price->price_old))
                    <div class="modalHotelRoom_box_footer_price_old">
                        <div class="modalHotelRoom_box_footer_price_old_number">
                            {{ number_format($price->price_old) }} <sup>đ</sup>
                        </div>
                        <div class="modalHotelRoom_box_footer_price_old_saleoff">
                            -{{ $price->sale_off }}%
                        </div>
                    </div>
                @endif
                <div class="modalHotelRoom_box_footer_price_now">
                    {{ number_format($price->price) }} <sup>đ</sup>
                </div>
            </div>
        @endif
        <div class="modalHotelRoom_box_footer_button">Đặt phòng này!</div>
    </div>
</div> 
<div class="modalHotelRoom_bg" onClick="openCloseModal('js_loadHotelPrice_modal_{{ $price->id }}');"></div>