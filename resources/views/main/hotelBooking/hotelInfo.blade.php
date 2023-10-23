<div class="hotelBookingInfoBox">
    <div class="hotelBookingInfoBox_hotel">
        <div class="hotelBookingInfoBox_hotel_image">
            <img src="{{ config('main.svg.loading_main') }}"  data-google-cloud="{{ $hotel->images[0]->image }}" data-size="200" />
        </div>
        <div class="hotelBookingInfoBox_hotel_info">
            <div class="hotelBookingInfoBox_hotel_info_title">{{ $hotel->name }}</div>
            <div class="hotelBookingInfoBox_hotel_info_type">
                <div class="hotelBookingInfoBox_hotel_info_type_name">
                    {{ $hotel->type_name }}
                </div>
                <div class="hotelBookingInfoBox_hotel_info_type_rating">
                    @for($i=0;$i<$hotel->type_rating;++$i)
                        <i class="fa-solid fa-star"></i>
                    @endfor
                </div>
            </div>
            <div class="hotelBookingInfoBox_hotel_info_address maxLine_1">
                {{ $hotel->address }}
            </div>
        </div>
    </div>
    <div class="hotelBookingInfoBox_booking">
        <div class="hotelBookingInfoBox_booking_room">
            <div class="hotelBookingInfoBox_booking_room_info">
                <div class="hotelBookingInfoBox_booking_room_info_title">
                    {{ $room->name }}
                    @if($price->breakfast==1||$price->given==1)
                        @php
                            $tmp            = [];
                            if($price->breakfast==1) $tmp[] = 'Bữa sáng';
                            if($price->given==1) $tmp[] = 'Đưa đón';
                            $xhtmlInclude   = implode(' + ', $tmp);
                        @endphp
                            ({{ $xhtmlInclude }})
                    @endif
                </div>
                @if(!empty($price->breakfast)||!empty($price->given))
                    @php
                        $tmp = [];
                        if($price->breakfast==1) $tmp[] = '<i class="fa-solid fa-check"></i>Bữa sáng ngon';
                        if($price->given==1) $tmp[] = '<i class="fa-solid fa-check"></i>Đưa - đón khách sạn';
                        $xhtmlInclude = implode(' ', $tmp);
                    @endphp
                    <div class="hotelBookingInfoBox_booking_room_info_include">
                        Bao gồm: {!! $xhtmlInclude !!}
                    </div>
                @endif
                <div class="hotelBookingInfoBox_booking_room_info_sub">
                    <div> 
                        <svg class="bk-icon -streamline-room_size" fill="#678" size="medium" width="16" height="16" viewBox="0 0 24 24"><path d="M3.75 23.25V7.5a.75.75 0 0 0-1.5 0v15.75a.75.75 0 0 0 1.5 0zM.22 21.53l2.25 2.25a.75.75 0 0 0 1.06 0l2.25-2.25a.75.75 0 1 0-1.06-1.06l-2.25 2.25h1.06l-2.25-2.25a.75.75 0 0 0-1.06 1.06zM5.78 9.22L3.53 6.97a.75.75 0 0 0-1.06 0L.22 9.22a.75.75 0 1 0 1.06 1.06l2.25-2.25H2.47l2.25 2.25a.75.75 0 1 0 1.06-1.06zM7.5 3.75h15.75a.75.75 0 0 0 0-1.5H7.5a.75.75 0 0 0 0 1.5zM9.22.22L6.97 2.47a.75.75 0 0 0 0 1.06l2.25 2.25a.75.75 0 1 0 1.06-1.06L8.03 2.47v1.06l2.25-2.25A.75.75 0 1 0 9.22.22zm12.31 5.56l2.25-2.25a.75.75 0 0 0 0-1.06L21.53.22a.75.75 0 1 0-1.06 1.06l2.25 2.25V2.47l-2.25 2.25a.75.75 0 0 0 1.06 1.06zM10.5 13.05v7.2a2.25 2.25 0 0 0 2.25 2.25h6A2.25 2.25 0 0 0 21 20.25v-7.2a.75.75 0 0 0-1.5 0v7.2a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1-.75-.75v-7.2a.75.75 0 0 0-1.5 0zm13.252 2.143l-6.497-5.85a2.25 2.25 0 0 0-3.01 0l-6.497 5.85a.75.75 0 0 0 1.004 1.114l6.497-5.85a.75.75 0 0 1 1.002 0l6.497 5.85a.75.75 0 0 0 1.004-1.114z"></path></svg> 
                        <span>Diện tích: {{ $price->room->size }} m2</span>
                    </div>
                </div>
                @if(!empty($price->beds)&&$price->beds->isNotEmpty())
                    <div class="hotelBookingInfoBox_booking_room_info_bed">
                        <i class="fa-solid fa-bed"></i>Loại giường:
                        @foreach($price->beds as $bed)
                            <span>{{ $bed->quantity }}</span> {{ $bed->infoHotelBed->name }}
                            @if($loop->index!=($price->beds->count()-1))
                                +
                            @endif
                        @endforeach
                    </div>
                @endif
                <div class="hotelBookingInfoBox_booking_room_info_bed">
                    <i class="fa-solid fa-user-check"></i>Đủ chỗ ngủ cho: {{ $price->number_people }} người lớn
                </div>
                @if(!empty($price->description))
                    <div class="hotelBookingInfoBox_booking_room_info_condition">
                        {!! $price->description !!}
                    </div>
                @endif

            </div>
            <div class="hotelBookingInfoBox_booking_room_image">
                @foreach($room->images as $image)
                    <div class="hotelBookingInfoBox_booking_room_image_item">
                        <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $image->image }}" data-size="400" />
                    </div>
                    @php
                        if($loop->index==2) break;
                    @endphp
                @endforeach
            </div>
            @if(empty($pageConfirm))
                <div class="iconAction" onclick="openCloseModalEditHotelPrice('bookingModal');">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
            @endif
        </div>
    </div>
    @if(!empty($pageConfirm)&&$pageConfirm==true&&!empty($booking))
        <div class="hotelBookingInfoBox_time">
            <div class="hotelBookingInfoBox_time_item">
                <div class="hotelBookingInfoBox_time_item_label">
                    Ngày Check-In:
                </div>
                <div class="hotelBookingInfoBox_time_item_text">
                    @php
                        $dayOfWeekCheckIn   = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($booking->check_in));
                        $xhtmlCheckIn       = $dayOfWeekCheckIn.', '.date('d-m-Y', strtotime($booking->check_in));
                    @endphp
                    {{ $xhtmlCheckIn }}
                </div>
            </div>
            <div class="hotelBookingInfoBox_time_item">
                <div class="hotelBookingInfoBox_time_item_label">
                    Ngày Check-Out:
                </div>
                <div class="hotelBookingInfoBox_time_item_text">
                    @php
                        $dayOfWeekCheckIn   = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($booking->check_out));
                        $xhtmlCheckIn       = $dayOfWeekCheckIn.', '.date('d-m-Y', strtotime($booking->check_out));
                    @endphp
                    {{ $xhtmlCheckIn }}
                </div>
            </div>
            <div class="hotelBookingInfoBox_time_item">
                <div class="hotelBookingInfoBox_time_item_label">
                    Số đêm:
                </div>
                <div class="hotelBookingInfoBox_time_item_text">
                    {{ $booking->number_night }}
                </div>
            </div>
            <div class="hotelBookingInfoBox_time_item">
                <div class="hotelBookingInfoBox_time_item_label">
                    Số phòng
                </div>
                <div class="hotelBookingInfoBox_time_item_text">
                    {{ $booking->quantity }}
                </div>
            </div>
        </div>
    @endif
</div>