<form id="formCost" method="get" action="{{ route('admin.cost.create') }}">
    <input type="hidden" name="booking_info_id" value="{{ $idBooking }}" />
    <input type="hidden" name="type" value="{{ $type }}" />
    <div id="formCostMoreLess">
        <div class="formCostMoreLess" data-repeater-list="cost">
            @foreach($data as $cost)
                <div class="formCostMoreLess_item" data-repeater-item>
                    <div style="width:100%;">
                        <label style="margin-bottom:0.5rem;font-size:1.1rem;font-weight:bold;float:left;">Mô tả ngắn</label>
                        <input name="name" type="text" class="form-control flatpickr-basic flatpickr-input active" value="{{ $cost->name }}" required />
                    </div>
                    <div style="width:100%;margin-left:1rem;">
                        <label style="margin-bottom:0.5rem;font-size:1.1rem;font-weight:bold;float:left;">Chi phí</label>
                        <input name="value" type="number" class="form-control flatpickr-basic flatpickr-input active" value="{{ $cost->value }}" required />
                    </div>
                    <div class="btnRemoveRepeater" data-repeater-delete><i class="fa-solid fa-xmark"></i></div>
                </div>
            @endforeach
            <div class="formCostMoreLess_item" data-repeater-item>
                <div style="width:100%;">
                    <label style="margin-bottom:0.5rem;font-size:1.1rem;font-weight:bold;float:left;">Mô tả ngắn</label>
                    <input name="name" type="text" class="form-control flatpickr-basic flatpickr-input active" value="" required />
                </div>
                <div style="width:100%;margin-left:1rem;">
                    <label style="margin-bottom:0.5rem;font-size:1.1rem;font-weight:bold;float:left;">Chi phí</label>
                    <input name="value" type="number" class="form-control flatpickr-basic flatpickr-input active" value="" required />
                </div>
                <div class="btnRemoveRepeater" data-repeater-delete><i class="fa-solid fa-xmark"></i></div>
            </div>
        </div>
        <!-- button thêm -->
        <div style="text-align:right;margin-top:1.5rem;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</form>