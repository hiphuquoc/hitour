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