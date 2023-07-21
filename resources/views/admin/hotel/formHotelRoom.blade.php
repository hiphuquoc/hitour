<!-- Input hidden -->
<input type="hidden" id="hotel_room_id" name="hotel_room_id" value="{{ $data->id ?? null }}" />
@if(empty($data->id))
    <div class="formBox" style="margin-bottom:1rem;">
        <div class="formBox_full">
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label labelWithIcon" for="js_downloadHotelRoom_input">
                    <div>Dán html (Booking.com) để dùng tính năng tải tự động</div>
                    <i class="fa-solid fa-download" onClick="downloadHotelRoom();"></i>
                </label>
                <textarea class="form-control" id="js_downloadHotelRoom_input" rows="2"></textarea>
            </div>
        </div> 
    </div>
@endif


<div id="js_downloadHotelRoom_idWrite" class="formBox">
    @include('admin.hotel.formHotelRoomPart2', compact('roomFacilities'))
</div>

<script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script type="text/javascript">
    $('.formBox_full').repeater();
    // setInterval(() => {
    //     $(document).find('.date_range').each(function(){
    //         if($(this).hasClass('added')){
    //             /* đã addListener thì thôi */
    //         }else {
    //             $('.date_range').addClass('added').flatpickr({
    //                 mode: 'range'
    //             });
    //         }
    //     })
    // }, 100);

    function downloadHotelRoom(){
        const dataHtml = $('#js_downloadHotelRoom_input').val();
        addLoading('js_downloadHotelRoom_idWrite', 60);
        $.ajax({
            url         : '{{ route("admin.hotelRoom.downloadHotelRoom") }}',
            type        : 'post',
            dataType    : 'html',
            data        : {
                '_token'    : '{{ csrf_token() }}',
                data        : dataHtml
            },
            success     : function(response){
                $('#js_downloadHotelRoom_idWrite').html(response);
            }
        });
    }
</script>