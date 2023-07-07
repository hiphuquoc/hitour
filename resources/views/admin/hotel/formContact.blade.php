<div id="js_validateFormModal_message" class="error" style="margin-bottom:1rem;display:none;">
    <!-- Load Ajax -->
</div>
<!-- Input hidden -->
<input type="hidden" id="hotel_contact_id" name="hotel_contact_id" value="{{ $item->id ?? null }}" />
<div class="formBox">
    <div class="formBox_full" style="min-width:480px;">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="name">Tên liên hệ</label>
            <input type="text" class="form-control" name="name" value="{{ $item->name ?? null }}" required />
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="address">Địa chỉ</label>
            <input type="text" class="form-control" name="address" value="{{ $item->address ?? null }}" />
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="phone">Điện thoại</label>
            <input type="text" class="form-control" name="phone" value="{{ $item->phone ?? null }}" required />
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="zalo">Zalo</label>
            <input type="text" class="form-control" name="zalo" value="{{ $item->zalo ?? null }}" required />
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="email">Email</label>
            <input type="text" class="form-control" name="email" value="{{ $item->email ?? null }}" />
        </div>
    </div>
</div>