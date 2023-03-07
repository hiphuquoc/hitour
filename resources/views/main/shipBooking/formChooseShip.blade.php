@php
    $flagHaveShip = false;
    foreach($data as $item){
        if(!empty($item['times'])) {
            $flagHaveShip = true;
            break;
        }
    }
@endphp
<!-- One Row -->
@if(!empty($data)&&$flagHaveShip==true)
    @php
        $nameDeparture  = $portShipDeparture->district->district_name.' - '.$portShipLocation->district->district_name;
        $requiredDp1    = null;
        if(!empty($code)&&$code==1) $requiredDp1    = 'required';
    @endphp
    <input id="js_chooseDeparture_dp{{ $code }}" type="hidden" name="dp{{ $code }}" value="" {{ $requiredDp1 }} />
    <input type="hidden" name="ship_info_id_{{ $code }}" value="{{ $data[0]['ship_info_id'] ?? null }}" />
    <input type="hidden" name="name_dp{{ $code }}" value="{{ $nameDeparture }}" />
    <div class="formBox_full_item">
        <label class="form-label" for="quantity_adult">
            Giờ tàu và loại vé&nbsp;&nbsp;
            {{-- <span class="messageValidate_error" data-validate="dp{{ $code }}" style="font-weight:normal;margin-top:0;">Vui lòng chọn giờ khởi hành và loại vé!</span> --}}
        </label>
        <div class="chooseDepartureShipBox">
            <div class="chooseDepartureShipBox_body">
                @foreach($data as $item)
                    @foreach($item['times'] as $time)
                        <div class="chooseDepartureShipBox_body_row">
                            <div class="chooseDepartureShipBox_body_row_item">
                                @php
                                    $timeMove   = \App\Helpers\Time::convertMkToTimeMove($time['time_move']);
                                @endphp
                                <div><span class="highLight">{{ $time['time_departure'] }}</span> - <span class="highLight">{{ $time['time_arrive'] }}</span> ({{ $timeMove }})</div>
                                <div style="font-weight:500;">{{ $item['partner']['name'] }}</div>
                            </div>
                            <div class="chooseDepartureShipBox_body_row_item width50">
                                <div class="option" onClick="chooseDeparture(this, {{ $code }}, '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'eco', '{{ $item['partner']['name'] }}');">
                                    <div>ECO</div>
                                    <div class="price">{{ number_format($item['price_adult']).config('main.unit_currency') }}</div>
                                </div>
                            </div>
                            <div class="chooseDepartureShipBox_body_row_item width50">
                                @if(!empty($item['price_vip']))
                                    <div class="option" onClick="chooseDeparture(this, {{ $code }}, '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'vip', '{{ $item['partner']['name'] }}');">
                                        <div>VIP</div>
                                        <div class="price">{{ number_format($item['price_vip']).config('main.unit_currency') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@else 
    <div class="formBox_full_item">
        <label class="form-label" for="quantity_adult">Giờ tàu và loại vé&nbsp;&nbsp;<span class="messageValidate_error" data-validate="dp{{ $code }}" style="font-weight:normal;margin-top:0;">Vui lòng chọn giờ khởi hành và loại vé!</span></label>
        <div style="color:Red;">Hiện không có lịch tàu vào ngày bạn chọn! Vui lòng chọn ngày khác</div>
    </div>
@endif