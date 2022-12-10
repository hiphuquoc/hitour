<!-- One Row -->
{{-- @php
    dd($data);
@endphp --}}
@if(!empty($data))
    <input type="hidden" id="service_option_id" name="service_option_id" value="{{ $data[0]['id'] ?? 0 }}">
    <div class="chooseOptionTourBox customScrollBar-x">
        <div class="chooseOptionTourBox_body">
            @foreach($data as $option)
                @php
                    $active     = ($loop->index==0) ? 'active' : null;
                @endphp
                <div class="chooseOptionTourBox_body_item {{ $active }}" onClick="highLightChoose(this, '{{ $option['id'] ?? null }}');">
                    <div class="chooseOptionTourBox_body_item_icon"></div>
                    <div>
                        <div style="font-weight:bold;">{{ $option['name'] ?? null }}</div>
                        @if(!empty($option['prices'][0]['promotion']))
                            <div style="font-size:0.95rem;">
                                Ghi chú: {{ $option['prices'][0]['promotion'] }}
                            </div>
                        @endif
                    </div>
                    <div>
                        @foreach($option['prices'] as $price)
                            <div style="font-size:0.95rem;"><span class="highLight">{{ number_format($price['price']).config('main.unit_currency') }}</span> /{{ $price['apply_age'] }}</div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else 
    <div style="color:red;">Hiện dịch vụ này chưa có lịch mở cửa vào ngày bạn chọn! Vui lòng chọn ngày khác</div>
@endif