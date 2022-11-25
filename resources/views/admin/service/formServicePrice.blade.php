<input type="hidden" id="service_option_id" name="service_option_id" value="{{ !empty($item->id)&&$type=='update' ? $item->id : null }}" />
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="promotion">Khuyến mãi (Nếu có)</label>
            <textarea class="form-control" id="promotion" name="promotion" rows="5">{{ $item->prices[0]->promotion ?? null }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            @php
                $valueDateRange         = null;
                if(!empty($item->prices[0]->date_start)&&!empty($item->prices[0]->date_end)){
                    if($item->prices[0]->date_start!=$item->prices[0]->date_end){
                        $valueDateRange = $item->prices[0]->date_start.' to '.$item->prices[0]->date_end;
                    }else {
                        $valueDateRange = $item->prices[0]->date_start;
                    }
                }
            @endphp
            <label class="form-label inputRequired" for="date_range">Khoảng ngày</label>
            <input type="text" class="form-control flatpickr-disabled-range flatpickr-input active" id="date_range" name="date_range" value="{{ $valueDateRange }}" placeholder="YYYY-MM-DD" required>
        </div>
        <!-- One Row -->
        @if(!empty($item->prices)&&$item->prices->isNotEmpty())
            <div class="formBox_full_item" data-repeater-list="list">
                @foreach($item->prices as $price)
                
                    <div class="flexBox" data-repeater-item>
                        <div class="flexBox_item">
                            <label class="form-label inputRequired">Tuổi áp dụng</label>
                            <input type="text" class="form-control" name="apply_age" value="{{ $price->apply_age }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label inputRequired">Giá</label>
                            <input type="text" class="form-control" name="name_fix_bug" value="{{ $price->price }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label inputRequired">Hoa hồng (số tiền)</label>
                            <input type="text" class="form-control" name="profit" value="{{ $price->profit }}" required>
                        </div>
                        <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="formBox_full_item" data-repeater-list="list">
                <div class="flexBox" data-repeater-item>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired">Tuổi áp dụng</label>
                        <input type="text" class="form-control" name="apply_age" value="" required>
                    </div>
                    <div class="flexBox_item" style="margin-left:1rem;">
                        <label class="form-label inputRequired">Giá</label>
                        <input type="text" class="form-control" name="name_fix_bug" value="" required>
                    </div>
                    <div class="flexBox_item" style="margin-left:1rem;">
                        <label class="form-label inputRequired">Hoa hồng (số tiền)</label>
                        <input type="text" class="form-control" name="profit" value="" required>
                    </div>
                    <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            </div>
        @endif
        <!-- One Row -->
        <div class="formBox_full_item" style="text-align:right;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.flatpickr-input').flatpickr({
        mode: 'range'
    });
    $('.formBox_full').repeater();
    // setInterval(() => {
    //     $(document).find('.flatpickr-input').each(function(){
    //         if($(this).hasClass('added')){
    //             /* đã addListener thì thôi */
    //         }else {
    //             $('.flatpickr-input').addClass('added').flatpickr({
    //                 mode: 'range'
    //             });
    //         }
    //     })
    // }, 100);
</script>