<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="code">Mã Combo</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') ?? $item->code ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="departure_schedule">Lịch khởi hành</label>
            <input type="text" class="form-control" id="departure_schedule" name="departure_schedule" value="{{ old('departure_schedule') ?? $item->departure_schedule ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        {{-- <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Nhập số ngày của Combo. Nhập 0 nếu là Combo trong ngày
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label inputRequired" for="days">Số ngày</label>
                    </span>
                    <input type="number" min="0" id="days" class="form-control" name="days" value="{{ old('days') ?? $item->days ?? 0 }}" required>
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Nhập số đêm của Combo. Nhập 0 nếu là Combo trong ngày
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label inputRequired" for="nights">Số đêm</label>
                    </span>
                    <input type="number" min="0" id="nights" class="form-control" name="nights" value="{{ old('nights') ?? $item->nights ?? 0 }}" required>
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Dùng cho Combo trong ngày
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label" for="time_start">Thời gian bắt đầu</label>
                    </span>
                    <input type="text" id="time_start" name="time_start" class="form-control flatpickr-time text-start flatpickr-input active" placeholder="HH:MM" value="{{ old('time_start') ?? $item->time_start ?? null }}" readonly="readonly">
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Dùng cho Combo trong ngày
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label" for="time_end">Thời gian kết thúc</label>
                    </span>
                    <input type="text" id="time_end" name="time_end" class="form-control flatpickr-time text-start flatpickr-input active" placeholder="HH:MM" value="{{ old('time_end') ?? $item->time_end ?? null }}" readonly="readonly">
                </div>
            </div>
        </div> --}}
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Giá này sẽ được hiển thị đại diện cho chương trình Combo
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label inputRequired" for="price_show">Giá đại diện</label>
                    </span>
                    <input type="number" min="0" id="price_show" class="form-control" name="price_show" value="{{ old('price_show') ?? $item->price_show ?? 0 }}" required>
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Giá này là giá cũ được hiển thị kiểu gạch bỏ
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label" for="price_del">Giá cũ</label>
                    </span>
                    <input type="number" min="0" id="price_del" class="form-control" name="price_del" value="{{ old('price_del') ?? $item->price_del ?? 0 }}">
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="form-check form-check-success">
                <input type="checkbox" class="form-check-input" id="status_show" name="status_show" {{ !empty($item->status_show)&&($item->status_show==1) ? 'checked' : null }}>
                <label class="form-check-label" for="status_show">Cho phép hiển thị trong danh sách của khu vực Combo</label>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="form-check form-check-success">
                <input type="checkbox" class="form-check-input" id="status_sidebar" name="status_sidebar" {{ !empty($item->status_sidebar)&&($item->status_sidebar==1) ? 'checked' : null }}>
                <label class="form-check-label" for="status_sidebar">Cho phép hiển thị trong sidebar</label>
            </div>
        </div>
    </div>
</div>