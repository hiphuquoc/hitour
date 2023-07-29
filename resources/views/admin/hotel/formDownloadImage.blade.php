@if(!empty($idHotel))
    <div class="modal-header bg-transparent">
        <h4>Tải tự động ảnh khách sạn</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <label class="form-label" for="content_image">Thẻ html chứa đường dẫn ảnh (Mytour)</label>
        <textarea class="form-control" id="content_image"  name="content_image" rows="2">{{ $item['content_image'] ?? null }}</textarea>
    </div>
    <div class="modal-footer">
        <div id="js_validateFormModalHotelContact_message" class="error" style="display:none;"><!-- Load Ajax --></div>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
        <button type="button" class="btn btn-primary" aria-label="Xác nhận" onClick="downloadImageHotelInfo();">Xác nhận</button>
    </div>
@else 
    <div class="modal-header bg-transparent">
        <h4>Tải tự động ảnh khách sạn</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        Vui lòng lưu khách sạn trước khi sử dụng tính năng này!
    </div>
@endif