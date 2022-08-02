<input type="hidden" name="vat_info_id" value="{{ $item->vat->id ?? null }}" />

<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="vat_name">Tên công ty</label>
            <input type="text" class="form-control" id="vat_name" name="vat_name" value="{{ old('vat_name') ?? $item->vat->vat_name ?? null }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="vat_code">Mã số thuế</label>
            <input type="text" class="form-control" id="vat_code" name="vat_code" value="{{ old('vat_code') ?? $item->vat->vat_code ?? null }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="vat_address">Địa chỉ</label>
            <input type="text" class="form-control" id="vat_address" name="vat_address" value="{{ old('vat_address') ?? $item->vat->vat_address ?? null }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="vat_note">Ghi chú</label>
            <input type="text" class="form-control" id="vat_note" name="vat_note" value="{{ old('vat_note') ?? $item->vat->vat_note ?? null }}">
        </div>
    </div>
</div>