@if(!empty($data)&&!empty($date))
    <div class="bookingForm_item_head">
        Chuyến {{ $data[$date]['departure'] }} - {{ $data[$date]['location'] }}
        <div>
            <input id="js_chooseDeparture_dp{{ $code }}" type="hidden" name="dp{{ $code }}" value="" required />
            <input type="hidden" name="ship_info_id_{{ $code }}" value="{{ $data[$date]['ship_info_id'] ?? null }}" />
            <input type="hidden" name="name_dp{{ $code }}" value="{{ !empty($data[$date]['departure'])&&!empty($data[$date]['location']) ? $data[$date]['departure'].' - '.$data[$date]['location'] : null }}" />
        </div>
        <div class="messageValidate_error" data-validate="dp{{ $code }}" style="font-weight:normal;">Vui lòng chọn giờ khởi hành và loại vé!</div>
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
                                    $active         = $day==$date ? ' active' : '';
                                    $dayOfWeek      = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($day));
                                @endphp
                                <div class="chooseDepartureShipBox_head_item{{ $active }}" onClick="resetDeparture({{ $code }}, '{{ date('Y-m-d', strtotime($day)) }}');">
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
                                    <div class="option" onClick="chooseDeparture(this, {{ $code }}, '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'eco', '{{ $data[$date]['partner'] }}');">
                                        <div>ECO</div>
                                        <div class="price">{{ number_format($data[$date]['price_adult']) }}<sup>đ</sup></div>
                                    </div>
                                </div>
                                <div class="chooseDepartureShipBox_body_row_item">
                                    @if(!empty($data[$date]['price_vip']))
                                    <div class="option" onClick="chooseDeparture(this, {{ $code }}, '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'vip', '{{ $data[$date]['partner'] }}');">
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