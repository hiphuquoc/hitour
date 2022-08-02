<div class="card-header border-bottom">
    <h4 class="card-title">Thông tin Đối tác</h4>
</div>
<div class="card-body">
    @if(!empty($item->id))
    <input type="hidden" name="tourLocation_id" value="{{ $item->id }}" />
    @endif
    @if(!empty($item->seo->id))
        <input type="hidden" name="seo_id" value="{{ $item->seo->id }}" />
    @endif
    
    <div class="formBox">
        <div class="formBox_full">
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label inputRequired" for="name">Tên hiển thị</label>
                </div>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $item->name ?? '' }}" required>
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label" for="company_name">Tên công ty</label>
                </div>
                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') ?? $item->company_name ?? '' }}">
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label" for="company_code">Mã số thuế</label>
                </div>
                <input type="text" class="form-control" id="company_code" name="company_code" value="{{ old('company_code') ?? $item->company_code ?? '' }}">
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label" for="company_address">Địa chỉ</label>
                </div>
                <input type="text" class="form-control" id="company_address" name="company_address" value="{{ old('company_address') ?? $item->company_address ?? '' }}">
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label" for="company_website">Website</label>
                <input type="text" class="form-control" id="company_website" name="company_website" value="{{ old('company_website') ?? $item->company_website ?? '' }}">
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="company_hotline">Hotline</label>
                <input type="text" class="form-control" id="company_hotline" name="company_hotline" value="{{ old('company_hotline') ?? $item->company_hotline ?? '' }}" required>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="company_email">Email</label>
                <input type="text" class="form-control" id="company_email" name="company_email" value="{{ old('company_email') ?? $item->company_email ?? '' }}" required>
            </div>
        </div>
    </div>
    
</div>