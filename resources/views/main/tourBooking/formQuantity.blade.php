<!-- One Row -->
<div class="formBox_full_item">
    <div class="flexBox">
        @if(!empty($prices)&&$prices->isNotEmpty())
            @foreach($prices as $price)
                <div class="flexBox_item">
                    <div class="inputWithIcon adult">
                        <label class="form-label">{{ $price->apply_age }}</label>
                        <input type="text" class="form-control" name="quantity[{{ $price->id }}]" value="" onChange="loadBookingSummary();">
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="messageValidate_error" data-validate="quantity">Tổng số lượng phải lớn hơn 0!</div>
</div>