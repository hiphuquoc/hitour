<div class="bookingModal_box_body_item">
    <div class="bookingModal_box_body_item_head">
        Thông tin chung
    </div>
    <div class="bookingModal_box_body_item_body">

        <!-- One Row -->
        <div class="bookingModal_box_body_item_body_item">
            <div class="inputWithLabelInside date">
                <label for="modal_range_time">Ngày Check-In Check-Out</label>
                <input type="text" class="form-control flatpickr-disabled-range flatpickr-input" id="modal_range_time" name="modal_range_time" value="{{ $dataForm['range_time'] ?? null }}" />
            </div>
        </div>

        <!-- One Row -->
        <div class="bookingModal_box_body_item_body_item">
            <div class="inputWithLabelInside">
                <label for="modal_quantity">Số phòng</label>
                <input type="number" id="modal_quantity" name="modal_quantity" value="{{ $quantity }}" />
            </div>
        </div>
        
    </div>
</div>

<div class="bookingModal_box_body_item">
    <div class="bookingModal_box_body_item_head">
        Chọn loại phòng
    </div>
    <div class="bookingModal_box_body_item_body">
        <!-- input hidden modal_hotel_price_id -->
        <input type="hidden" id="modal_hotel_price_id" name="modal_hotel_price_id" value="{{ $price->id }}" />
        <div class="formChooseHotelRoom">
            @foreach($hotel->rooms as $room)
                @foreach($room->prices as $p)
                
                @php
                    $selected = null;
                    if($p->id==$price->id) $selected = 'selected';
                @endphp
                <div id="js_chooseHotelPrice_{{ $p->id }}" class="formChooseHotelRoom_item {{ $selected }}" onclick="chooseHotelPrice({{ $p->id }});">

                    <div class="formChooseHotelRoom_item_image">
                        <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $room->images[0]->image }}" data-size="300" />
                    </div>

                    <div class="formChooseHotelRoom_item_info">
                        <div class="formChooseHotelRoom_item_info_item">
                            <span class="title">
                                {{ $room->name }}
                                @if($p->breakfast==1||$p->given==1)
                                    @php
                                        $tmp            = [];
                                        if($p->breakfast==1) $tmp[] = 'Bữa sáng';
                                        if($p->given==1) $tmp[] = 'Đưa đón';
                                        $xhtmlInclude   = implode(' + ', $tmp);
                                    @endphp
                                        ({{ $xhtmlInclude }})
                                @endif
                            </span>
                        </div>
                        @if(!empty($p->breakfast)||!empty($p->given))
                            @php
                                $tmp = [];
                                if($p->breakfast==1) $tmp[] = '<i class="fa-solid fa-check"></i>Bữa sáng ngon';
                                if($p->given==1) $tmp[] = '<i class="fa-solid fa-check"></i>Đưa - đón khách sạn';
                                $xhtmlInclude = implode(' ', $tmp);
                            @endphp
                            <div class="formChooseHotelRoom_item_info_item">
                                Bao gồm: {!! $xhtmlInclude !!}
                            </div>
                        @endif
                        <div class="formChooseHotelRoom_item_info_item">
                            <div> 
                                <svg class="bk-icon -streamline-room_size" fill="#678" size="medium" width="16" height="16" viewBox="0 0 24 24"><path d="M3.75 23.25V7.5a.75.75 0 0 0-1.5 0v15.75a.75.75 0 0 0 1.5 0zM.22 21.53l2.25 2.25a.75.75 0 0 0 1.06 0l2.25-2.25a.75.75 0 1 0-1.06-1.06l-2.25 2.25h1.06l-2.25-2.25a.75.75 0 0 0-1.06 1.06zM5.78 9.22L3.53 6.97a.75.75 0 0 0-1.06 0L.22 9.22a.75.75 0 1 0 1.06 1.06l2.25-2.25H2.47l2.25 2.25a.75.75 0 1 0 1.06-1.06zM7.5 3.75h15.75a.75.75 0 0 0 0-1.5H7.5a.75.75 0 0 0 0 1.5zM9.22.22L6.97 2.47a.75.75 0 0 0 0 1.06l2.25 2.25a.75.75 0 1 0 1.06-1.06L8.03 2.47v1.06l2.25-2.25A.75.75 0 1 0 9.22.22zm12.31 5.56l2.25-2.25a.75.75 0 0 0 0-1.06L21.53.22a.75.75 0 1 0-1.06 1.06l2.25 2.25V2.47l-2.25 2.25a.75.75 0 0 0 1.06 1.06zM10.5 13.05v7.2a2.25 2.25 0 0 0 2.25 2.25h6A2.25 2.25 0 0 0 21 20.25v-7.2a.75.75 0 0 0-1.5 0v7.2a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1-.75-.75v-7.2a.75.75 0 0 0-1.5 0zm13.252 2.143l-6.497-5.85a2.25 2.25 0 0 0-3.01 0l-6.497 5.85a.75.75 0 0 0 1.004 1.114l6.497-5.85a.75.75 0 0 1 1.002 0l6.497 5.85a.75.75 0 0 0 1.004-1.114z"></path></svg> 
                                <span>Diện tích: {{ $p->room->size }} m2</span>
                            </div>
                        </div>
                        @if(!empty($p->beds)&&$p->beds->isNotEmpty())
                            <div class="formChooseHotelRoom_item_info_item">
                                <i class="fa-solid fa-bed"></i>Loại giường:
                                @foreach($p->beds as $bed)
                                    <span>{{ $bed->quantity }}</span> {{ $bed->infoHotelBed->name }}
                                    @if($loop->index!=($p->beds->count()-1))
                                        +
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <div class="formChooseHotelRoom_item_info_item">
                            <i class="fa-solid fa-user-check"></i>Đủ chỗ ngủ cho: {{ $p->number_people }} người lớn
                        </div>
                        @if(!empty($p->description))
                            <div class="formChooseHotelRoom_item_info_item">
                                {!! $p->description !!}
                            </div>
                        @endif
                    </div>
                    
                </div>
                @endforeach
            @endforeach
        </div>

    </div>
</div>