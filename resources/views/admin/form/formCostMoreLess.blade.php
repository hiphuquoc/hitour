<div id="js_validateFormModal_message" class="error" style="margin-bottom:1rem;display:none;">
    <!-- Load Ajax -->
</div>
<!-- Input hidden -->
<input type="hidden" id="cost_more_less_id" name="cost_more_less_id" value="{{ $item->id ?? null }}" />
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="cost_name">Dịch vụ</label>
            <input type="text" class="form-control" id="cost_name"  name="name" value="{{ $item->name ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="cost_quantity">Số lượng</label>
            <input type="number" class="form-control" id="cost_quantity"  name="quantity" value="{{ $item->quantity ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="cost_unit_price">Đơn giá</label>
            <input type="number" class="form-control" id="cost_unit_price"  name="unit_price" value="{{ $item->unit_price ?? null }}" required>
        </div>
    </div>
</div>