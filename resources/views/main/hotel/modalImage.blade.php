<div id="modalImage" class="modalImage">
    <input type="hidden" id="image_total" name="image_total" value="{{ $images->count() }}" />
    <input type="hidden" id="image_loaded" name="image_loaded" value="0" />
    <!-- icon close -->
    <div class="modalImage_close" onClick="openCloseModalImage('modalImage');">
        <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="modalImage_box">
        <div class="hotelImageTab">
            <div class="hotelImageTab_head">
                <div class="hotelImageTab_head_item selected">
                    Ảnh khách sạn
                </div>
            </div>
            <div class="hotelImageTab_body">
                <div id="js_loadHotelImage" class="hotelImageTab_body_tab">
                    <!-- load Ajax -->
                </div>
                <div id="js_loadHotelImage_iconLoading" style="display:none;justify-content:center;width:100%;align-items:center;flex-direction:column;margin-bottom:2rem;">
                    <img src="{{ config("main.svg.loading_main_nobg") }}" style="width:200px;" />
                    <div style="margin-top:-40px;">đang tải thêm ảnh...</div>
                </div>
            </div>
        </div>
    </div>
    <div class="modalImage_bg" onClick="openCloseModalImage('modalImage');"></div>
</div>
@push('scripts-custom')
    <script type="text/javascript">
        $('windown').ready(function(){
            loadHotelImage();
        })

        $(window).on('scroll', function() {
            const flag = $('#modalImage').css('display');
            if(flag!='none') loadHotelImage();
        });

        function loadHotelImage(){
            var boxLoad             = $('#js_loadHotelImage');
            $('#js_loadHotelImage_iconLoading').css('display', 'flex');
            if(boxLoad.length&&!boxLoad.hasClass('loading')){
                const distanceLoad  = boxLoad.outerHeight() + boxLoad.offset().top;
                if($(window).scrollTop() + 2500 > boxLoad.outerHeight() + boxLoad.offset().top) {
                    /* thêm class để đánh dấu đăng load => không load nữa */
                    boxLoad.addClass('loading');

                    const idHotel   = $('#hotel_info_id').val();
                    const total     = parseInt($('#image_total').val());
                    const loaded    = parseInt($('#image_loaded').val());
                    if(loaded<total){
                        $.ajax({
                            url         : '{{ route("main.hotel.loadHotelImage") }}',
                            type        : 'get',
                            dataType    : 'json',
                            data        : {
                                hotel_info_id : idHotel,
                                total, 
                                loaded
                            },
                            success     : function(response){
                                $('#js_loadHotelImage').append(response.content);
                                $('#image_loaded').val(response.loaded);
                                boxLoad.removeClass('loading');
                                $('#js_loadHotelImage_iconLoading').css('display', 'none');
                            }
                        });
                    }
                }
            }
        }
    </script>
@endpush