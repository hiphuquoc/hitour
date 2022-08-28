<div id="js_validateFormModal_message" class="error" style="margin-bottom:1rem;display:none;">
    <!-- Load Ajax -->
</div>
<!-- Input hidden -->
<input type="hidden" id="partner_contact_id" name="partner_contact_id" value="{{ $item->id ?? null }}" />
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="name">Tên liên hệ</label>
            <input type="text" class="form-control" id="contact_name" name="name" value="{{ $item->name ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="address">Địa chỉ</label>
            <input type="text" class="form-control" id="contact_address" name="address" value="{{ $item->address ?? null }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="phone">Điện thoại</label>
            <input type="text" class="form-control" id="contact_phone" name="phone" value="{{ $item->phone ?? null }}" required>
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="zalo">Zalo</label>
            <input type="text" class="form-control" id="contact_zalo" name="zalo" value="{{ $item->zalo ?? null }}" required>
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="email">Email</label>
            <input type="text" class="form-control" id="contact_email" name="email" value="{{ $item->email ?? null }}" required>
        </div>
    </div>
</div>