<div class="formBox_full_item">
    <div id="js_loadDeparture_dp1">
    <!-- One Row -->
    <input type="hidden" id="tour_option_id" name="tour_option_id" value="{{ $data[0]['id'] ?? null }}" />
    <div class="formBox_full_item">
        <label class="form-label" for="quantity_adult">Tiêu chuẩn Tour</label>
        <div class="chooseOptionTourBox">
            <div class="chooseOptionTourBox_body">
                @if(!empty($data))
                    @foreach($data as $option)
                        @php
                            $active = ($loop->index==0) ? 'active' : null;
                        @endphp
                        <div class="chooseOptionTourBox_body_item {{ $active }}" onClick="highLightChoose(this, '{{ $option['id'] ?? null }}');">
                            <div>{{ $option['option'] ?? null }}</div>
                            <div>
                                @foreach($option['prices'] as $price)
                                    <div><span class="highLight">{{ number_format($price['price']).config('main.unit_currency') }}</span> /{{ $price['apply_age'] }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    </div>
</div>