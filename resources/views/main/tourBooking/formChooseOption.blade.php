@if(!empty($data))
    <input type="hidden" id="tour_option_id" name="tour_option_id" value="{{ $data[0]['id'] }}">
    <div class="chooseOptionTourBox">
        <div class="chooseOptionTourBox_body">
            @foreach($data as $option)
                @php
                    $active     = ($loop->index==0) ? 'active' : null;
                @endphp
                <div class="chooseOptionTourBox_body_item {{ $active }}" onClick="highLightChoose(this, '{{ $option['id'] ?? null }}');">
                    <div class="chooseOptionTourBox_body_item_icon">
                        
                    </div>
                    <div>{{ $option['option'] ?? null }}</div>
                    <div style="font-size:0.95rem;">
                        @foreach($option['prices'] as $price)
                            <div><span class="highLight">{{ number_format($price['price']).config('main.unit_currency') }}</span> /{{ $price['apply_age'] }}</div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else 
    <div style="color:red;">Hiện tour này chưa có lịch khởi hành vào ngày bạn chọn! Vui lòng chọn ngày khác</div>
@endif