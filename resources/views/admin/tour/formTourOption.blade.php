<!-- Input hidden -->
<input type="hidden" id="tour_option_id" name="tour_option_id" value="{{ $option->id ?? null }}" />
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="title">Option</label>
            <input type="text" class="form-control" id="tour_option" name="option" value="{{ $option->option ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="tour_apply_day">Loại ngày áp dụng</label>
            <select class="form-select" id="tour_apply_day" name="apply_day">
                @foreach(config('admin.tour_option_apply_day') as $typeDay)
                    @php
                        $selected   = null;
                        if(!empty($option->apply_day)&&$typeDay['name']===$option->apply_day) $selected   = 'selected';
                    @endphp
                    <option value="{{ $typeDay['name'] }}" {{ $selected }}>{{ $typeDay['name'] }}</option>
                @endforeach
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" data-repeater-list="repeater">
            @if(!empty($option->prices)&&!$option->prices->isEmpty())
                @foreach($option->prices as $price)
                    <div class="flexBox" data-repeater-item>
                        <div class="flexBox_item">
                            <label class="form-label inputRequired">Tuổi áp dụng</label>
                            <input type="text" class="form-control" name="repeater[{{ $loop->index }}][apply_age]" value="{{ $price['apply_age'] ?? 0 }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label inputRequired">Giá</label>
                            <input type="number" class="form-control" name="repeater[{{ $loop->index }}][price]" value="{{ $price['price'] ?? 0 }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label">Hoa hồng</label>
                            <input type="number" class="form-control" name="repeater[{{ $loop->index }}][profit]" value="{{ $price['profit'] ?? 0 }}">
                        </div>
                        <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flexBox" data-repeater-item>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired">Tuổi áp dụng</label>
                        <input type="text" class="form-control" name="apply_age" value="" required>
                    </div>
                    <div class="flexBox_item" style="margin-left:1rem;">
                        <label class="form-label inputRequired">Giá</label>
                        <input type="text" class="form-control" name="price" value="" required>
                    </div>
                    <div class="flexBox_item" style="margin-left:1rem;">
                        <label class="form-label">Hoa hồng</label>
                        <input type="text" class="form-control" name="profit" value="">
                    </div>
                    <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" style="text-align:right;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.formBox_full').repeater();
</script>