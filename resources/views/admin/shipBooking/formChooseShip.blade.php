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
        // dd($booking);
    @endphp
    {{-- @if(!empty($item)) --}}
        <label class="form-label">Giờ tàu & loại vé</label>
        <div class="chooseDepartureShipBox">
            @foreach($data['times'] as $time)
                <div class="chooseDepartureShipBox_row">
                    <div class="chooseDepartureShipBox_row_item">
                        <div class="highLight">{{ $time['time_departure'] }} - {{ $time['time_arrive'] }}</div>
                        <div>{{ $data['partner'] ?? '-' }}</div>
                    </div>
                    <div class="chooseDepartureShipBox_row_item" style="padding-right:0 !important;">
                        @php
                            $active = null;
                            foreach($booking->infoDeparture as $departure){
                                if($departure->time_departure==$time['time_departure'] /* trùng giờ khởi hành */
                                &&$departure->port_departure==$time['ship_from'] /* trùng giờ cảng khởi hành */
                                &&$departure->partner_name==$data['partner'] /* trùng hãng tàu */
                                &&$departure->type=='eco'){
                                    $timeDepartureChoose    = $time['time_departure'];
                                    $timeArriveChoose       = $time['time_arrive'];
                                    $typeTicketChoose       = 'eco';
                                    $partnerChoose          = $data['partner'];
                                    $active                 = 'active';
                                    break;
                                }
                            }
                        @endphp
                        <div class="chooseDepartureShipBox_row_item_choose option {{ $active }}" onClick="chooseDeparture(this, '{{ $code }}', '{{ $data['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'eco', '{{ $data['partner'] }}');">
                            <div class="highLight">ECO</div>
                            <div>{{ number_format($data['price_adult']).config('main.unit_currency') }}</div>
                        </div>
                    </div>
                    <div class="chooseDepartureShipBox_row_item">
                        @if(!empty($data['price_vip']))
                            @php
                                $active = null;
                                foreach($booking->infoDeparture as $departure){
                                    if($departure->time_departure==$time['time_departure'] /* trùng giờ khởi hành */
                                    &&$departure->port_departure==$time['ship_from'] /* trùng giờ cảng khởi hành */
                                    &&$departure->partner_name==$data['partner'] /* trùng hãng tàu */
                                    &&$departure->type=='vip'){
                                        $timeDepartureChoose    = $time['time_departure'];
                                        $timeArriveChoose       = $time['time_arrive'];
                                        $typeTicketChoose       = 'vip';
                                        $partnerChoose          = $data['partner'];
                                        $active     = 'active';
                                        break;
                                    }
                                }
                            @endphp
                            <div class="chooseDepartureShipBox_row_item_choose option {{ $active }}" onClick="chooseDeparture(this, '{{ $code }}', '{{ $data['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'vip', '{{ $data['partner'] }}');">
                                <div class="highLight">VIP</div>
                                <div>{{ number_format($data['price_vip']).config('main.unit_currency') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        @php
            $valueDp        = null;
            if(!empty($timeDepartureChoose)&&!empty($timeArriveChoose)&&!empty($typeTicketChoose)){
                $valueDp    =  $data['ship_price_id'].'|'.$timeDepartureChoose.'|'.$timeArriveChoose.'|'.$typeTicketChoose.'|'.$partnerChoose;
            }
        @endphp
        <input id="js_chooseDeparture_dp{{ $code }}" name="dp{{ $code }}" type="hidden" value="{{ $valueDp }}" />
        <input type="hidden" name="ship_info_id_{{ $code }}" value="{{ $data['ship_info_id'] ?? null }}" />
    {{-- @endif --}}
@endif