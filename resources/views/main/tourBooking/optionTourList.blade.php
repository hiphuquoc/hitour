<label class="form-label" style="margin-bottom:0.25rem;">Tùy chọn giá</label>
<div class="chooseOptionTour">
    @foreach($options as $option)
        <label class="chooseOptionTour_item" for="tour_info_id_{{ $option->id }}" onClick="highLightChoose(this);">
            <div class="chooseOptionTour_item_radio">
                <input id="tour_info_id_{{ $option->id }}" type="radio" value="{{ $option->id }}" name="tour_option_id" />
            </div>
            <div class="chooseOptionTour_item_option">
                <div class="highLight">{{ $option->option }}</div>
                <div>{{ $option->apply_day }}</div>
            </div>
            <div class="chooseOptionTour_item_price">
                @foreach($option->prices as $price)
                    <div>
                        <span class="highLight">{{ number_format($price->price) }}<sup>đ</sup></span> /{{ $price->apply_age }}
                    </div>
                @endforeach
            </div>
        </label>
    @endforeach
</div>