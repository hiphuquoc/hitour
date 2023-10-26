<div class="modalHotelRoom_box">
    <!-- icon close -->
    <div class="modalHotelRoom_box_close" onClick="openCloseModalRoom({{ $price->id }});">
        <i class="fa-solid fa-xmark"></i>
    </div>
    
    <div id="js_setHeightBoxByBox_element_{{ $price->id }}" class="modalHotelRoom_box_body">
        <div id="js_setHeightBoxByBox_rule_{{ $price->id }}" class="modalHotelRoom_box_body_gallery">
            @if(!empty($price->room->images)&&$price->room->images->isNotEmpty())
                <div class="modalHotelRoom_box_body_gallery_top">
                    <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $price->room->images[0]->image }}" data-size="600" alt="Ảnh phòng {{ $price->room->name }}" title="Ảnh phòng {{ $price->room->name }}" />
                </div>
                <div class="modalHotelRoom_box_body_gallery_bottom">
                    @foreach($price->room->images as $image)
                        <div class="modalHotelRoom_box_body_gallery_bottom_item">
                            <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $image->image }}" data-size="200" alt="Ảnh phòng {{ $price->room->name }}" title="Ảnh phòng {{ $price->room->name }}" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="modalHotelRoom_box_body_info customScrollBar-y">
                <!-- title -->
                <div class="modalHotelRoom_box_body_info_title">
                    {{ $price->room->name }}
                </div>
                <!-- số người tối đa & kích thước phòng -->
                <div class="modalHotelRoom_box_body_info_item">
                    <div class="modalHotelRoom_box_body_info_item_text">
                        <div> 
                            <img src="{{ Storage::url('images/svg/icon-sizeroom.svg') }}" alt="kích thước phòng" title="kích thước phòng" />
                            <span>{{ $price->room->size }} m<sup>2</sup></span>
                        </div>
                        <div> 
                            <img src="{{ Storage::url('images/svg/icon-adult.svg') }}" alt="số người tối đa trong phòng" title="số người tối đa trong phòng" />
                            <span>{{ $price->number_people }}</span>
                        </div>
                    </div>
                </div>
                <!-- bao gồm -->
                @if($price->breakfast==1||$price->given==1)
                    <div class="modalHotelRoom_box_body_info_item">
                        <div class="modalHotelRoom_box_body_info_item_label">
                            Phòng đã bao gồm
                        </div>
                        <div class="modalHotelRoom_box_body_info_item_text">
                            @if($price->breakfast==1)
                                <div class="modalHotelRoom_box_body_info_item_text_item">
                                    <img src="{{ Storage::url('images/svg/icon-breakfast.svg') }}" alt="đã bao gồm bữa sáng ngon" title="đã bao gồm bữa sáng ngon" />
                                    <span>Bữa sáng ngon</span>
                                </div>
                            @endif
                            @if($price->given==1)
                                <div class="modalHotelRoom_box_body_info_item_text_item">
                                    <img src="{{ Storage::url('images/svg/icon-given.png') }}" alt="đã bao gồm đưa đón khách sạn" title="đã bao gồm đưa đón khách sạn" />
                                    <span>Đưa đón</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="modalHotelRoom_box_body_info_item">
                    <div class="modalHotelRoom_box_body_info_item_label">
                        Loại giường
                    </div>
                    <div class="modalHotelRoom_box_body_info_item_text">
                        @php
                            $tmp        = [];
                            foreach($price->beds as $bed){
                                $tmp[]  = $bed->quantity.' - '.$bed->infoHotelBed->name;
                            }
                            $xhtmlBed   = implode(' và ', $tmp);
                            if(empty($xhtmlBed)) $xhtmlBed = 'Chưa xác định';
                        @endphp
                        <div class="modalHotelRoom_box_body_info_item_text_item" style="width:100%;">
                            <img src="{{ Storage::url('images/svg/icon-bed.svg') }}" alt="loại giường trong phòng" title="loại giường trong phòng" />
                            <span>{{ $xhtmlBed }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- facilities -->
                @if(!empty($price->room->facilities)&&$price->room->facilities->isNotEmpty())
                    <div class="modalHotelRoom_box_body_info_item">
                        <div class="modalHotelRoom_box_body_info_item_label">
                            Tiện nghi cơ bản:
                        </div>
                        <div class="modalHotelRoom_box_body_info_item_facilities">

                            @foreach($price->room->facilities as $facility)
                                <div class="modalHotelRoom_box_body_info_item_facilities_item">
                                    {!! $facility->infoHotelRoomFacility->icon !!}
                                    {{ $facility->infoHotelRoomFacility->name }}
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endif

                {{-- <!-- condition -->
                @if(!empty($price->description))
                    <div class="modalHotelRoom_box_body_info_condition">
                        {!! $price->description !!}
                    </div>
                @endif --}}
                <!-- details -->
                @if(!empty($price->room->details)&&$price->room->details->isNotEmpty())
                
                    
                    @foreach($price->room->details as $detail)
                    <div class="modalHotelRoom_box_body_info_item">
                        <div class="modalHotelRoom_box_body_info_item_label">
                            {{ $detail->name }}
                        </div>
                        <div class="modalHotelRoom_box_body_info_item_text">
                            {!! $detail->detail !!}
                        </div>
                    </div>
                    @endforeach

                
                @endif
        </div>
    </div>
    <!-- button -->
    <div class="modalHotelRoom_box_footer">
        @if(!empty($price->price))
            <div class="modalHotelRoom_box_footer_price">
                {{-- @if(!empty($price->sale_off)&&!empty($price->price_old))
                    <div class="modalHotelRoom_box_footer_price_old">
                        <div class="modalHotelRoom_box_footer_price_old_number">
                            {{ number_format($price->price_old) }} <sup>đ</sup>
                        </div>
                        <div class="modalHotelRoom_box_footer_price_old_saleoff">
                            -{{ $price->sale_off }}%
                        </div>
                    </div>
                @endif --}}
                <div class="modalHotelRoom_box_footer_price_now">
                    {{ number_format($price->price) }} <sup>đ</sup>
                </div>
            </div>
        @endif
        <a href="{{ route('main.hotelBooking.form', ['hotel_price_id' => $price->id]) }}" class="modalHotelRoom_box_footer_button">Đặt phòng này!</a>
    </div>
</div> 
<div class="modalHotelRoom_bg" onClick="openCloseModalRoom({{ $price->id }});"></div>