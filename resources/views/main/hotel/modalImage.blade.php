<div id="modalImage" class="modalImage">
    {{-- <input type="hidden" id="image_total" name="image_total" value="{{ $images->count() }}" />
    <input type="hidden" id="image_loaded" name="image_loaded" value="0" /> --}}
    
    <div js="js_loadHotelImage_scrollBox" class="modalImage_box customScrollBar-y">
        <div class="hotelImageTab"><!-- icon close -->
            <div class="hotelImageTab_close" onClick="openCloseModalImage('modalImage');">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="hotelImageTab_head">
                <div class="hotelImageTab_head_item selected">
                    Ảnh khách sạn
                </div>
            </div>
            <div class="hotelImageTab_body">
                <div id="js_loadHotelImage" class="hotelImageTab_body_tab">
                    <!-- load ajax -->
                    <div style="display: flex; justify-content: center; width: 100%; align-items: center; flex-direction: column; margin-bottom: 2rem;">
                        <img src="/storage/images/svg/loading_plane_transparent.svg" style="width:200px;">
                        <div style="margin-top:-40px;">đang tải thêm ảnh...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modalImage_bg" onClick="openCloseModalImage('modalImage');"></div>
</div>
@push('scripts-custom')
    <script type="text/javascript">

        function loadHotelImage() {
            const idHotel = $('#hotel_info_id').val();
            $.ajax({
                url: '{{ route("main.hotel.loadHotelImage") }}',
                type: 'get',
                dataType: 'html',
                data: {
                    hotel_info_id: idHotel
                },
                success: function(response) {
                    $('#js_loadHotelImage').html(response);

                    // Chọn tất cả các hình ảnh có thuộc tính data-google-cloud
                    $('#js_loadHotelImage img[data-google-cloud]').each(function() {
                        var image = $(this);
                        if (!image.hasClass('loaded') && image.is(":visible")) {
                            loadImageFromGoogleCloud(image);
                            image.addClass('loaded');
                        }
                    });
                }
            });
        }

    </script>
@endpush