<!-- One Row -->
@if(!empty($data))
    <input id="js_chooseDeparture_dp{{ $code }}" type="hidden" name="dp{{ $code }}" value="" required />
    <input type="hidden" name="ship_info_id_{{ $code }}" value="{{ $data['ship_info_id'] ?? null }}" />
    @php
        $nameDeparture      = null;
        if(!empty($data['departure']&&!empty($data['location']))){
            $nameDeparture  = $data['departure'].' - '.$data['location'];
        }
    @endphp
    <input type="hidden" name="name_dp{{ $code }}" value="{{ $nameDeparture }}" />
    <div class="formBox_full_item">
        <label class="form-label" for="quantity_adult">Giờ tàu và loại vé&nbsp;&nbsp;<span class="messageValidate_error" data-validate="dp{{ $code }}" style="font-weight:normal;margin-top:0;">Vui lòng chọn giờ khởi hành và loại vé!</span></label>
        <div class="chooseDepartureShipBox">
            <div class="chooseDepartureShipBox_body">
                @foreach($data['times'] as $time)
                <div class="chooseDepartureShipBox_body_row">
                    <div class="chooseDepartureShipBox_body_row_item">
                        @php
                            $timeMove   = \App\Helpers\Time::convertMkToTimeMove($time['time_move']);
                        @endphp
                        <div><span class="highLight">{{ $time['time_departure'] }}</span> - <span class="highLight">{{ $time['time_arrive'] }}</span> ({{ $timeMove }})</div>
                        <div style="font-weight:500;">{{ $data['partner'] }}</div>
                    </div>
                    <div class="chooseDepartureShipBox_body_row_item">
                        <div class="option" onClick="chooseDeparture(this, {{ $code }}, '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'eco', '{{ $data['partner'] }}');">
                            <div>ECO</div>
                            <div class="price">{{ number_format($data['price_adult']) }}<sup>đ</sup></div>
                        </div>
                    </div>
                    <div class="chooseDepartureShipBox_body_row_item">
                        @if(!empty($data['price_vip']))
                        <div class="option" onClick="chooseDeparture(this, {{ $code }}, '{{ $time['ship_price_id'] }}', '{{ $time['time_departure'] }}', '{{ $time['time_arrive'] }}', 'vip', '{{ $data['partner'] }}');">
                            <div>VIP</div>
                            <div class="price">{{ number_format($data['price_vip']) }}<sup>đ</sup></div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endif