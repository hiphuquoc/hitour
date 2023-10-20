<div class="modalHotelRoom_box">
    <!-- icon close -->
    <div class="modalHotelRoom_box_close" onClick="openCloseModal('js_loadHotelPrice_modal_{{ $price->id }}');">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="customScrollBar-y">
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
                <!-- bao gồm -->
                @if($price->breakfast==1||$price->given==1)
                    <div class="modalHotelRoom_box_body_info_include">
                        @if($price->breakfast==1)
                            <span>
                                <i class="fa-solid fa-check"></i>Bao gồm bữa sáng ngon
                            </span>
                        @endif
                        @if($price->given==1)
                            <span>
                                <i class="fa-solid fa-check"></i>Bao gồm đưa đón
                            </span>
                        @endif
                    </div>
                @endif
                <!-- số người tối đa & kích thước phòng -->
                @if(!empty($price->number_people))
                    <div class="modalHotelRoom_box_body_info_size">
                        <div> 
                            <svg class="bk-icon -streamline-room_size" fill="#678" size="medium" width="16" height="16" viewBox="0 0 24 24"><path d="M3.75 23.25V7.5a.75.75 0 0 0-1.5 0v15.75a.75.75 0 0 0 1.5 0zM.22 21.53l2.25 2.25a.75.75 0 0 0 1.06 0l2.25-2.25a.75.75 0 1 0-1.06-1.06l-2.25 2.25h1.06l-2.25-2.25a.75.75 0 0 0-1.06 1.06zM5.78 9.22L3.53 6.97a.75.75 0 0 0-1.06 0L.22 9.22a.75.75 0 1 0 1.06 1.06l2.25-2.25H2.47l2.25 2.25a.75.75 0 1 0 1.06-1.06zM7.5 3.75h15.75a.75.75 0 0 0 0-1.5H7.5a.75.75 0 0 0 0 1.5zM9.22.22L6.97 2.47a.75.75 0 0 0 0 1.06l2.25 2.25a.75.75 0 1 0 1.06-1.06L8.03 2.47v1.06l2.25-2.25A.75.75 0 1 0 9.22.22zm12.31 5.56l2.25-2.25a.75.75 0 0 0 0-1.06L21.53.22a.75.75 0 1 0-1.06 1.06l2.25 2.25V2.47l-2.25 2.25a.75.75 0 0 0 1.06 1.06zM10.5 13.05v7.2a2.25 2.25 0 0 0 2.25 2.25h6A2.25 2.25 0 0 0 21 20.25v-7.2a.75.75 0 0 0-1.5 0v7.2a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1-.75-.75v-7.2a.75.75 0 0 0-1.5 0zm13.252 2.143l-6.497-5.85a2.25 2.25 0 0 0-3.01 0l-6.497 5.85a.75.75 0 0 0 1.004 1.114l6.497-5.85a.75.75 0 0 1 1.002 0l6.497 5.85a.75.75 0 0 0 1.004-1.114z"></path></svg> 
                            <span>309 m2</span>
                        </div>
                        <div>
                            Đủ chỗ ngủ cho
                            <span>
                                <i class="fa-solid fa-person"></i>
                                <i class="fa-solid fa-person"></i>
                                <i class="fa-solid fa-person"></i>
                                <i class="fa-solid fa-person"></i>
                            </span>
                        </div>
                    </div>
                @endif
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
        <a href="{{ route('main.hotelBooking.form', ['hotel_price_id' => $price->id]) }}" class="modalHotelRoom_box_footer_button">Đặt phòng này!</a>
    </div>
</div> 
<div class="modalHotelRoom_bg" onClick="openCloseModal('js_loadHotelPrice_modal_{{ $price->id }}');"></div>