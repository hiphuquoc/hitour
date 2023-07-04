<!-- One Row -->
@if(!empty($data))
    <input type="hidden" id="combo_option_id" name="combo_option_id" value="{{ $data[0]->id ?? 0 }}" /> <!-- active option đầu tiên -->
    <div class="chooseOptionTourBox customScrollBar-x">
        <div class="chooseOptionTourBox_body">
            @foreach($data as $price)
                @php
                    $active     = ($loop->index==0) ? 'active' : null;
                @endphp
                <div class="chooseOptionTourBox_body_item {{ $active }}" onClick="highLightChoose(this, '{{ $price->id ?? null }}');">
                    <div class="chooseOptionTourBox_body_item_icon"></div>
                    <div>
                        <div style="font-weight:bold;">{{ $price['name'] ?? null }}</div>
                        @if(!empty($price->prices[0]->departure->display_name))
                            <div style="font-size:0.9rem;">Khởi hành từ {{ $price->prices[0]->departure->display_name }}</div>
                        @endif
                        {{-- @if(!empty($price['prices'][0]['promotion']))
                            <div style="font-size:0.95rem;">
                                Ghi chú: {{ $price['prices'][0]['promotion'] }}
                            </div>
                        @endif --}}
                    </div>
                    <div>
                        @foreach($price['prices'] as $price)
                            <div style="font-size:0.95rem;"><span class="highLight">{{ number_format($price['price']).config('main.unit_currency') }}</span> /{{ $price['apply_age'] }}</div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else 
    <div style="color:red;">Hiện dịch vụ này không có vào ngày bạn chọn! Vui lòng chọn ngày khác</div>
@endif