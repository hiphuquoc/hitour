<!-- One Row -->
<div class="bookingForm_item_body_item">
    <div class="formColumnCustom">
        @if(!empty($prices)&&$prices->isNotEmpty())
            @foreach($prices as $price)
                <div class="formColumnCustom_item">
                    <div>
                        <label class="form-label">{{ $price->apply_age }}</label>
                        <input type="number" class="form-control" name="quantity[{{ $price->id }}]" placeholder="0" value="" min="0" onInput="loadBookingSummary();">
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="messageValidate_error" data-validate="quantity">Tổng số lượng phải lớn hơn 0!</div>
</div>