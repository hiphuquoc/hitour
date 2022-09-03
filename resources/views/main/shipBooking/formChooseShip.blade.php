@if(!empty($data)&&!empty($date))
    <input id="js_chooseDeparture_dp1" type="hidden" name="dp1" value="" />
    <div class="bookingForm_item_head">
        Chuyến Rạch Giá - Phú Quốc
    </div>
    <div class="bookingForm_item_body" style="padding:0;background:unset;border:none;box-shadow:none;">
        <div class="formBox">
            <div class="formBox_full">
                <!-- One Row -->
                <div class="formBox_full_item">
                    <div class="chooseDepartureShipBox">
                        <div class="chooseDepartureShipBox_head">
                            @foreach($data as $day => $value)
                                @php
                                    $active     = $day==$date ? ' active' : '';
                                    $dayOfWeek  = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($day));
                                @endphp
                                <div class="chooseDepartureShipBox_head_item{{ $active }}">
                                    <div class="chooseDepartureShipBox_head_item_day">{{ $dayOfWeek }}, {{ date('d/m', strtotime($day)) }}</div>
                                    <div class="chooseDepartureShipBox_head_item_price">{!! !empty($value['price_adult']) ? number_format($value['price_adult']).'<sup>đ</sup>' : '-' !!}</div>
                                </div>
                            @endforeach
                        </div>
                        <div class="chooseDepartureShipBox_body">
                            @foreach($data[$date]['times'] as $time)
                            <div class="chooseDepartureShipBox_body_row">
                                <div class="chooseDepartureShipBox_body_row_item">
                                    @php
                                        $timeMove   = \App\Helpers\Time::convertMkToTimeMove($time['time_move']);
                                    @endphp
                                    <div><span class="highLight">{{ $time['time_departure'] }}</span> - <span class="highLight">{{ $time['time_arrive'] }}</span> ({{ $timeMove }})</div>
                                    <div style="font-weight:500;">{{ $data[$date]['partner'] }}</div>
                                </div>
                                <div class="chooseDepartureShipBox_body_row_item">
                                    <div class="option" onClick="chooseDeparture(this, 1, '{{ $time['time_departure'] }}', 'eco');">
                                        <div>ECO</div>
                                        <div class="price">{{ number_format($data[$date]['price_adult']) }}<sup>đ</sup></div>
                                    </div>
                                </div>
                                <div class="chooseDepartureShipBox_body_row_item">
                                    @if(!empty($data[$date]['price_vip']))
                                    <div class="option" onClick="chooseDeparture(this, 1, '{{ $time['time_departure'] }}', 'vip');">
                                        <div>VIP</div>
                                        <div class="price">{{ number_format($data[$date]['price_vip']) }}<sup>đ</sup></div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif