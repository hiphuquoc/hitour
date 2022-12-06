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
    {{-- @if(!empty($item)) --}}
        <label class="form-label">Giờ tàu & loại vé</label>
        <div class="chooseDepartureShipBox">
            @foreach($data as $ship)
                @foreach($ship['times'] as $time)
                    <div class="chooseDepartureShipBox_row">
                        <div class="chooseDepartureShipBox_row_item">
                            <div class="highLight">{{ $time['time_departure'] }} - {{ $time['time_arrive'] }}</div>
                            <div>{{ $ship['partner']['name'] ?? '-' }}</div>
                        </div>
                        <div class="chooseDepartureShipBox_row_item" style="padding-right:0 !important;">
                            @php
                                $active = null;
                                foreach($booking->infoDeparture as $departure){
                                    if($departure->time_departure==$time['time_departure'] /* trùng giờ khởi hành */
                                    &&$departure->port_departure==$time['ship_from'] /* trùng cảng khởi hành */
                                    &&$departure->partner_name==$ship['partner']['name'] /* trùng hãng tàu */
                                    &&$departure->type=='eco'){
                                        $timeDepartureChoose    = $time['time_departure'];
                                        $timeArriveChoose       = $time['time_arrive'];
                                        $typeTicketChoose       = 'eco';
                                        $partnerChoose          = $ship['partner']['name'];
                                        $active                 = 'active';
                                        break;
                                    }
                                }
                            @endphp
                            <div class="chooseDepartureShipBox_row_item_choose option {{ $active }}" onClick="chooseDeparture(this, '{{ $code }}', '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'eco', '{{ $ship['partner']['name'] }}');">
                                <div class="highLight">ECO</div>
                                <div>{{ number_format($ship['price_adult']).config('main.unit_currency') }}</div>
                            </div>
                        </div>
                        <div class="chooseDepartureShipBox_row_item">
                            @if(!empty($ship['price_vip']))
                                @php
                                    $active = null;
                                    foreach($booking->infoDeparture as $departure){
                                        if($departure->time_departure==$time['time_departure'] /* trùng giờ khởi hành */
                                        &&$departure->port_departure==$time['ship_from'] /* trùng giờ cảng khởi hành */
                                        &&$departure->partner_name==$ship['partner']['name'] /* trùng hãng tàu */
                                        &&$departure->type=='vip'){
                                            $timeDepartureChoose    = $time['time_departure'];
                                            $timeArriveChoose       = $time['time_arrive'];
                                            $typeTicketChoose       = 'vip';
                                            $partnerChoose          = $ship['partner']['name'];
                                            $active     = 'active';
                                            break;
                                        }
                                    }
                                @endphp
                                <div class="chooseDepartureShipBox_row_item_choose option {{ $active }}" onClick="chooseDeparture(this, '{{ $code }}', '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'vip', '{{ $ship['partner']['name'] }}');">
                                    <div class="highLight">VIP</div>
                                    <div>{{ number_format($ship['price_vip']).config('main.unit_currency') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        @php
            $valueDp            = null;
            if(!empty($timeDepartureChoose)&&!empty($timeArriveChoose)&&!empty($typeTicketChoose)){
                $idShipPrice    = 0;
                foreach($data as $ship){
                    foreach($ship['times'] as $time){
                        if(!empty($time['ship_price_id'])) {
                            $idShipPrice    = $time['ship_price_id'];
                            break;
                        }
                    }
                }
                $valueDp    =  $idShipPrice.'|'.$timeDepartureChoose.'|'.$timeArriveChoose.'|'.$typeTicketChoose;
            }
        @endphp
        <input id="js_chooseDeparture_dp{{ $code }}" name="dp{{ $code }}" type="hidden" value="{{ $valueDp }}" />
        <input type="hidden" name="ship_info_id_{{ $code }}" value="{{ $data[0]['id'] }}" />
        <input type="hidden" name="name_dp{{ $code }}" value="{{ $portShipDeparture->district->district_name }} - {{ $portShipLocation->district->district_name }}" />
    {{-- @endif --}}
@endif