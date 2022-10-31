<div class="formBox">
    <div class="formBox_full">
        @if(!empty($item->customer_list))
            @foreach($item->customer_list as $infoCustomer)
                <div class="formBox_full_item" data-repeater-list="customer_list">
                    <div class="flexBox" data-repeater-item>
                        <div class="flexBox_item">
                            <label class="form-label" for="customer_name">Họ tên</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $infoCustomer->customer_name ?? null }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label" for="customer_year_of_birth">Năm sinh</label>
                            <input type="text" class="form-control" id="customer_year_of_birth" name="customer_year_of_birth" value="{{ $infoCustomer->customer_year_of_birth ?? null }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label" for="customer_identity">CMND /CCCD</label>
                            <input type="text" class="form-control" id="customer_identity" name="customer_identity" value="{{ $infoCustomer->customer_identity ?? null }}">
                        </div>
                        <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        <div class="formBox_full_item" data-repeater-list="customer_list">
            <div class="flexBox" data-repeater-item>
                <div class="flexBox_item">
                    <label class="form-label" for="customer_name">Họ tên</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="" required>
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <label class="form-label" for="customer_year_of_birth">Năm sinh</label>
                    <input type="text" class="form-control" id="customer_year_of_birth" name="customer_year_of_birth" value="">
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <label class="form-label" for="customer_identity">CMND /CCCD</label>
                    <input type="text" class="form-control" id="customer_identity" name="customer_identity" value="" required>
                </div>
                <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
        </div>
        <div class="formBox_full_item" style="text-align:right;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</div>