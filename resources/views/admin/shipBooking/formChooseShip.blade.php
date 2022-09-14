@if(!empty($data)&&!empty($date))
    @php
        $timeDepartureChoose    = null;
        $timeArriveChoose       = null;
        $typeTicketChoose       = null;
        $partnerChoose          = null;
        $item                   = null;
        foreach($data as $key => $value){
            if($key==$date) {
                $item           = $value;
                break;
            }
        }
    @endphp
    @if(!empty($item))
        <label class="form-label">Giờ tàu & loại vé</label>
        <div class="chooseDepartureShipBox">
            @foreach($item['times'] as $time)
                <div class="chooseDepartureShipBox_row">
                    <div class="chooseDepartureShipBox_row_item">
                        <div class="highLight">{{ $time['time_departure'] }} - {{ $time['time_arrive'] }}</div>
                        <div>{{ $item['partner'] ?? '-' }}</div>
                    </div>
                    <div class="chooseDepartureShipBox_row_item" style="padding-right:0 !important;">
                        @php
                            $active = null;
                            foreach($booking->infoDeparture as $departure){
                                if($departure->time_departure==$time['time_departure'] /* trùng giờ khởi hành */
                                &&$departure->port_departure==$time['ship_from'] /* trùng giờ cảng khởi hành */
                                &&$departure->partner_name==$item['partner'] /* trùng hãng tàu */
                                &&$departure->type=='eco'){
                                    $timeDepartureChoose    = $time['time_departure'];
                                    $timeArriveChoose       = $time['time_arrive'];
                                    $typeTicketChoose       = 'eco';
                                    $partnerChoose          = $item['partner'];
                                    $active                 = 'active';
                                    break;
                                }
                            }
                        @endphp
                        <div class="chooseDepartureShipBox_row_item_choose option {{ $active }}" onClick="chooseDeparture(this, '{{ $code }}', '{{ $item['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'eco', '{{ $item['partner'] }}');">
                            <div class="highLight">ECO</div>
                            <div>{{ number_format($item['price_adult']) }}<sup>đ</sup></div>
                        </div>
                    </div>
                    <div class="chooseDepartureShipBox_row_item">
                        @if(!empty($item['price_vip']))
                            @php
                                $active = null;
                                foreach($booking->infoDeparture as $departure){
                                    if($departure->time_departure==$time['time_departure'] /* trùng giờ khởi hành */
                                    &&$departure->port_departure==$time['ship_from'] /* trùng giờ cảng khởi hành */
                                    &&$departure->partner_name==$item['partner'] /* trùng hãng tàu */
                                    &&$departure->type=='vip'){
                                        $timeDepartureChoose    = $time['time_departure'];
                                        $timeArriveChoose       = $time['time_arrive'];
                                        $typeTicketChoose       = 'vip';
                                        $partnerChoose          = $item['partner'];
                                        $active     = 'active';
                                        break;
                                    }
                                }
                            @endphp
                            <div class="chooseDepartureShipBox_row_item_choose option {{ $active }}" onClick="chooseDeparture(this, '{{ $code }}', '{{ $item['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'vip', '{{ $item['partner'] }}');">
                                <div class="highLight">VIP</div>
                                <div>{{ number_format($item['price_vip']) }}<sup>đ</sup></div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @php
            $valueDp        = null;
            if(!empty($timeDepartureChoose)&&!empty($timeArriveChoose)&&!empty($typeTicketChoose)){
                $valueDp    =  $item['ship_price_id'].'|'.$timeDepartureChoose.'|'.$timeArriveChoose.'|'.$typeTicketChoose.'|'.$partnerChoose;
            }
        @endphp
        <input id="js_chooseDeparture_dp{{ $code }}" name="dp{{ $code }}" type="hidden" value="{{ $valueDp }}" />
        <input type="hidden" name="ship_info_id_{{ $code }}" value="{{ $item['ship_info_id'] ?? null }}" />
    @endif
@endif