@extends('main.layouts.booking')
@push('head-custom')
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

    @php
        if(!empty($dataForm['range_time'])){
            $tmp        = explode(' to ', $dataForm['range_time']);
            $checkIn    = !empty($tmp[0]) ? strtotime($tmp[0]) : null;
            $checkOut   = !empty($tmp[1]) ? strtotime($tmp[1]) : null;
            /* xử lý trường hợp không range_time cùng một ngày (không có trường hợp này nên phải là ngày hôm sau) */
            if(empty($checkOut)) $checkOut   = $checkIn + 86400;
        }else {
            /* xử lý trường hợp chưa có time */
            $checkIn    = time() + 86400;
            $checkOut   = $checkIn + 86400;
        }
        $price              = $room->prices[0];
        $numberNight        = ($checkOut - $checkIn)/86400;
        $dayOfWeekCheckIn   = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek($checkIn);
        $xhtmlCheckIn       = '14:00, '.$dayOfWeekCheckIn.', '.date('d \t\hm', $checkIn);
        $dayOfWeekCheckOut  = \App\Helpers\DateAndTime::convertMktimeToDayOfWeek($checkOut);
        $xhtmlCheckOut      = '12:00, '.$dayOfWeekCheckOut.', '.date('d \t\hm', $checkOut);

        $quantity           = $dataForm['quantity'] ?? 1;
    @endphp
    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <!-- title -->
                {{-- <h1 class="titlePage titlePageBooking">Đặt phòng Khách Sạn</h1> --}}
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
                        <!-- ===== BOOKING HIỆN SẴN -->
                        <form id="formBooking" action="{{ route('main.hotelBooking.create') }}" method="GET">
                            @csrf
                            <input type="hidden" name="hotel_name" value="{{ $hotel->name }}" /> 
                            <input type="hidden" name="hotel_info_id" value="{{ $hotel->id }}" />
                            <input type="hidden" name="hotel_price_id" value="{{ $price->id }}" />
                            <input type="hidden" name="range_time" value="{{ $dataForm['range_time'] ?? null }}" />
                            <input type="hidden" name="quantity" value="{{ $quantity }}" />
                            <input type="hidden" name="number_night" value="{{ $numberNight }}" /> 
                            <div class="bookingForm">
                                <div class="bookingForm_item">
                                    
                                    <div class="hotelBookingInfoBox">
                                        <div class="hotelBookingInfoBox_hotel">
                                            <div class="hotelBookingInfoBox_hotel_image">
                                                <img class="" src="{{ config('main.svg.loading_main') }}"  data-google-cloud="{{ $hotel->images[0]->image }}" data-size="200" />
                                            </div>
                                            <div class="hotelBookingInfoBox_hotel_info">
                                                <div class="hotelBookingInfoBox_hotel_info_title">{{ $hotel->name }}</div>
                                                <div class="hotelBookingInfoBox_hotel_info_type">
                                                    <div class="hotelBookingInfoBox_hotel_info_type_name">
                                                        {{ $hotel->type_name }}
                                                    </div>
                                                    <div class="hotelBookingInfoBox_hotel_info_type_rating">
                                                        @for($i=0;$i<$hotel->type_rating;++$i)
                                                            <i class="fa-solid fa-star"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="hotelBookingInfoBox_hotel_info_address maxLine_1">
                                                    {{ $hotel->address }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hotelBookingInfoBox_booking">
                                            <div class="hotelBookingInfoBox_booking_room">
                                                <div class="hotelBookingInfoBox_booking_room_info">
                                                    <div class="hotelBookingInfoBox_booking_room_info_title">
                                                        {{ $room->name }}
                                                        @if($price->breakfast==1||$price->given==1)
                                                            @php
                                                                $tmp            = [];
                                                                if($price->breakfast==1) $tmp[] = 'Bữa sáng';
                                                                if($price->given==1) $tmp[] = 'Đưa đón';
                                                                $xhtmlInclude   = implode(' + ', $tmp);
                                                            @endphp
                                                                ({{ $xhtmlInclude }})
                                                        @endif
                                                    </div>
                                                    @if(!empty($price->breakfast)||!empty($price->given))
                                                        @php
                                                            $tmp = [];
                                                            if($price->breakfast==1) $tmp[] = '<i class="fa-solid fa-check"></i>Bữa sáng ngon';
                                                            if($price->given==1) $tmp[] = '<i class="fa-solid fa-check"></i>Đưa - đón khách sạn';
                                                            $xhtmlInclude = implode(' ', $tmp);
                                                        @endphp
                                                        <div class="hotelBookingInfoBox_booking_room_info_include">
                                                            Bao gồm: {!! $xhtmlInclude !!}
                                                        </div>
                                                    @endif
                                                    <div class="hotelBookingInfoBox_booking_room_info_sub">
                                                        <div> 
                                                            <svg class="bk-icon -streamline-room_size" fill="#678" size="medium" width="16" height="16" viewBox="0 0 24 24"><path d="M3.75 23.25V7.5a.75.75 0 0 0-1.5 0v15.75a.75.75 0 0 0 1.5 0zM.22 21.53l2.25 2.25a.75.75 0 0 0 1.06 0l2.25-2.25a.75.75 0 1 0-1.06-1.06l-2.25 2.25h1.06l-2.25-2.25a.75.75 0 0 0-1.06 1.06zM5.78 9.22L3.53 6.97a.75.75 0 0 0-1.06 0L.22 9.22a.75.75 0 1 0 1.06 1.06l2.25-2.25H2.47l2.25 2.25a.75.75 0 1 0 1.06-1.06zM7.5 3.75h15.75a.75.75 0 0 0 0-1.5H7.5a.75.75 0 0 0 0 1.5zM9.22.22L6.97 2.47a.75.75 0 0 0 0 1.06l2.25 2.25a.75.75 0 1 0 1.06-1.06L8.03 2.47v1.06l2.25-2.25A.75.75 0 1 0 9.22.22zm12.31 5.56l2.25-2.25a.75.75 0 0 0 0-1.06L21.53.22a.75.75 0 1 0-1.06 1.06l2.25 2.25V2.47l-2.25 2.25a.75.75 0 0 0 1.06 1.06zM10.5 13.05v7.2a2.25 2.25 0 0 0 2.25 2.25h6A2.25 2.25 0 0 0 21 20.25v-7.2a.75.75 0 0 0-1.5 0v7.2a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1-.75-.75v-7.2a.75.75 0 0 0-1.5 0zm13.252 2.143l-6.497-5.85a2.25 2.25 0 0 0-3.01 0l-6.497 5.85a.75.75 0 0 0 1.004 1.114l6.497-5.85a.75.75 0 0 1 1.002 0l6.497 5.85a.75.75 0 0 0 1.004-1.114z"></path></svg> 
                                                            <span>Diện tích: {{ $price->room->size }} m2</span>
                                                        </div>
                                                    </div>
                                                    @if(!empty($price->beds)&&$price->beds->isNotEmpty())
                                                        <div class="hotelBookingInfoBox_booking_room_info_bed">
                                                            <i class="fa-solid fa-bed"></i>Loại giường:
                                                            @foreach($price->beds as $bed)
                                                                <span>{{ $bed->quantity }}</span> {{ $bed->infoHotelBed->name }}
                                                                @if($loop->index!=($price->beds->count()-1))
                                                                    +
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                    <div class="hotelBookingInfoBox_booking_room_info_bed">
                                                        <i class="fa-solid fa-user-check"></i>Đủ chỗ ngủ cho: {{ $price->number_people }} người lớn
                                                    </div>
                                                    @if(!empty($price->description))
                                                        <div class="hotelBookingInfoBox_booking_room_info_condition">
                                                            {!! $price->description !!}
                                                        </div>
                                                    @endif

                                                </div>
                                                <div class="hotelBookingInfoBox_booking_room_image">
                                                    @foreach($room->images as $image)
                                                        <div class="hotelBookingInfoBox_booking_room_image_item">
                                                            <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $image->image }}" data-size="400" />
                                                        </div>
                                                        @php
                                                            if($loop->index==2) break;
                                                        @endphp
                                                    @endforeach
                                                </div>
                                                <div class="iconAction" onclick="openCloseModal('bookingModal');">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </div>
                                            </div>
                                            <div class="hotelBookingInfoBox_booking_time">
                                                <div class="hotelBookingInfoBox_booking_time_item">
                                                    <div class="hotelBookingInfoBox_booking_time_item_label">
                                                        Nhận phòng
                                                    </div>
                                                    <div class="hotelBookingInfoBox_booking_time_item_info highLight">
                                                        {{ $xhtmlCheckIn }}
                                                    </div>
                                                </div>
                                                <div class="hotelBookingInfoBox_booking_time_item">
                                                    <div class="hotelBookingInfoBox_booking_time_item_label">
                                                        Trả phòng
                                                    </div>
                                                    <div class="hotelBookingInfoBox_booking_time_item_info highLight">
                                                        {{ $xhtmlCheckOut }}
                                                    </div>
                                                </div>
                                                <div class="hotelBookingInfoBox_booking_time_item">
                                                    <div class="hotelBookingInfoBox_booking_time_item_label">
                                                        Số đêm
                                                    </div>
                                                    <div class="hotelBookingInfoBox_booking_time_item_info highLight">
                                                        {{ $numberNight }}
                                                    </div>
                                                </div>
                                                <div class="iconAction" onclick="openCloseModal('bookingModal');">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </div>
                                            </div>
                                            <div class="hotelBookingInfoBox_booking_quantity">
                                                <div class="hotelBookingInfoBox_booking_quantity_label">
                                                    Số phòng
                                                </div>
                                                <div class="hotelBookingInfoBox_booking_quantity_info">
                                                    <span class="highLight">{{ $quantity }} x</span> 1-Bedroom Wellness Pool Villa
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- thông tin liên hệ -->
                                <div class="bookingForm_item">
                                    <div class="bookingForm_item_head">
                                        Thông tin liên hệ
                                    </div>
                                    <div class="bookingForm_item_body">
                                        <!-- One Row -->
                                        <div class="bookingForm_item_body_item">
                                            <div class="formColumnCustom">
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside">
                                                        <label class="inputRequired" for="name">Họ tên</label>
                                                        <input type="text" name="name" value="{{ $dataForm['name'] ?? null }}" onkeyup="validateWhenType(this)" required />
                                                    </div>
                                                </div>
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside email">
                                                        <label for="email">Email (nếu có)</label>
                                                        <input type="text" name="email" value="{{ $dataForm['email'] ?? null }}" onkeyup="validateWhenType(this, 'email')" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bookingForm_item_body_item">
                                            <div class="formColumnCustom">
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside phone">
                                                        <label class="inputRequired" for="phone">Điện thoại</label>
                                                        <input type="text" name="phone" value="{{ $dataForm['phone'] ?? null }}" onkeyup="validateWhenType(this, 'phone')" required />
                                                    </div>
                                                </div>
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside message">
                                                        <label for="zalo">Zalo (nếu có)</label>
                                                        <input type="text" name="zalo" value="{{ $dataForm['zalo'] ?? null }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bookingForm_item_footer">
                                        *Đây là thông tin của Người Đặt để nhân viên Hitour có thể liên hệ và hỗ trợ bạn hoàn tất booking này!
                                    </div>
                                </div>
                                <!-- Yêu cầu đặc biệt -->
                                <div class="bookingForm_item">
                                    <div class="bookingForm_item_head">
                                        Yêu cầu đặc biệt
                                    </div>
                                    <div class="bookingForm_item_body">
                                        <!-- One Row -->
                                        <div class="bookingForm_item_body_item">
                                            <div class="textareaWithLabelInside">
                                                <label class="form-label" for="note_customer">Thời gian nhận phòng dự kiến (không bắt buộc)</label>
                                                <select class="select2 form-select select2-hidden-accessible" name="receive_time" tabindex="-1" aria-hidden="true">
                                                    @foreach(config('main.hotel_time_receive') as $timeReceive)
                                                        @php
                                                            $selected = null;
                                                            if(!empty($dataForm['receive_time'])&&$dataForm['receive_time']==$timeReceive) $selected = 'selected';
                                                        @endphp
                                                        <option value="{{ $timeReceive }}" {{ $selected }}>{{ $timeReceive }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- One Row -->
                                        <div class="bookingForm_item_body_item">
                                            <div class="textareaWithLabelInside">
                                                <label class="form-label" for="note_customer">Ghi chú của bạn</label>
                                                <textarea name="note_customer" rows="3" placeholder="Nếu có ghi chú đặc biệt cho booking của bạn, hãy điền ở đây!">{{ $dataForm['note_customer'] ?? null }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bookingForm_item_footer">
                                        *Yêu câu này sẽ được Chỗ Nghỉ tiếp nhận để hỗ trợ Quý khách tốt nhất trong quá trình nhận phòng!
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.hotelBooking.sidebar')
                    </div>
                </div>
    
            </div>

        </div>
    </div>

    <input type="text" class="form-control flatpickr-range flatpickr-input active" id="modal_range_time" name="modal_range_time" value="2023-05-29 to 2023-06-01" />
@endsection
@push('modal')
    <!-- ===== BOOKING MODAL -->
    <div id="bookingModal" class="bookingModal">
        <div class="bookingModal_box">
            <div class="bookingModal_box_body customScrollBar-y">
                @include('main.hotelBooking.formModal')
            </div>
            <div class="bookingModal_box_footer">
                <div class="button buttonCancel" onclick="openCloseModal('bookingModal');">Hủy thay đổi</div>
                <div class="button buttonPrimary" onclick="submitModalChangeHotelRoom();">Lưu thay đổi</div>
            </div>
        </div>
        <div class="bookingModal_bg"></div>
    </div>
@endpush
@push('bottom')
    <!-- button book tour mobile -->
    <div class="show-990">
        <div class="callBookTourMobile">
            <div class="callBookTourMobile_textNormal maxLine_1" onClick="showHideBox();">
                <i class="fa-solid fa-eye"></i>Tóm tắt booking
            </div>
            <div class="callBookTourMobile_button"><h2 onclick="">Xác nhận</h2></div>
        </div>
        <!-- Summary mobile -->
        @include('main.shipBooking.summaryMobile')
    </div>
@endpush
@push('scripts-custom')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
	<!-- ===== -->
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
    <script type="text/javascript">
        $(window).ready(function(){

            loadBookingSummary();

            // $('.flatpickr-input').flatpickr({
            //     mode: 'range'
            // });

        })

        // @foreach ($hotel->rooms as $room)
        //         @foreach ($room->prices as $price)
        //             loadHotelPrice('{{ $price->id }}');
        //         @endforeach
        //     @endforeach

        // function loadHotelPrice(idHotelPrice){
        //     $.ajax({
        //         url         : "{{ route('main.hotel.loadHotelPrice') }}",
        //         type        : "GET",
        //         dataType    : "json",
        //         data        : { hotel_price_id : idHotelPrice }
        //     }).done(function(data){
        //         /* ghi nội dung room vào bảng */
        //         if(data.row!='') {
        //             $('#js_loadHotelPrice_'+idHotelPrice).html(data.row);
        //         }else {
        //             $('#js_loadHotelPrice_'+idHotelPrice).hidden();
        //         }
        //         /* ghi nội dung modal room */
        //         if(data.row!='') {
        //             $('#js_loadHotelPrice_modal_'+idHotelPrice).html(data.modal);
        //         }else {
        //             $('#js_loadHotelPrice_modal_'+idHotelPrice).hidden();
        //         }
        //     });
        // }

        $('#formBooking').find('input, select, textarea').each(function(){
            $(this).on('input', () => {
                loadBookingSummary();
                const nameInput   = $(this).attr('name');
                showHideMessageValidate(nameInput, 'hide');
                // if(nameInput=='quantity_adult'||nameInput=='quantity_child'||nameInput=='quantity_old'){
                //     showHideMessageValidate('quantity', 'hide');
                // }
            })
        })

        function chooseHotelPrice(idHotelPrice){
            const boxChoose = $('#js_chooseHotelPrice_'+idHotelPrice);
            /* remove class selected tất cả child */
            boxChoose.parent().children().each(function(){
                $(this).removeClass('selected');
            })
            /* selected lại box được chọn */ 
            boxChoose.addClass('selected');
            /* ghi giá trị hotel_price_id được chọn vào input */
            $('#modal_hotel_price_id').val(idHotelPrice);
        }

        function submitModalChangeHotelRoom(){
            var form = $('#formBooking');
            /* lấy giá trị từ input của modal */
            const quantity  = $('#modal_quantity').val();
            const timeRange = $('#modal_range_time').val();
            const idHotelPrice = $('#modal_hotel_price_id').val();
            /* điền giá trị qua form chính */
            form.find('[name="quantity"]').val(quantity);
            form.find('[name="range_time"]').val(timeRange);
            form.find('[name="hotel_price_id"]').val(idHotelPrice);
            /* submit */
            form.attr('action', '{{ route("main.hotelBooking.form") }}');
            form.submit();
        }

        function submitForm(idForm){
            event.preventDefault();
            const error     = validateForm();
            if(error==''){
                $('#'+idForm).submit(); 
            }else {
                /* xuất thông báo */
                error.map(function(nameInput){
                    /* thông báo lỗi riêng => cho số lượng */
                    showHideMessageValidate(nameInput, 'show');
                    /* thông báo lỗi chung empty */
                    if(nameInput!='quantity') $('input[name*='+nameInput+']').parent().addClass('validateErrorEmpty');
                });
                /* scroll đến thông báo đầu tiên */
                $('[class*=validateErrorEmpty]').each(function(){
                    $('html, body').animate({
                        scrollTop: $(this).offset().top - 90
                    }, 300);
                    // $(window).scrollTop(parseInt($(this).offset().top - 90));
                    return false;
                });
            }
        }

        function loadBookingSummary(){
            const dataForm = $("#formBooking").serializeArray();
            $.ajax({
                url         : '{{ route("main.hotelBooking.loadBookingSummary") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    dataForm    : dataForm
                },
                success     : function(data){
                    $('#js_loadBookingSummary_idWrite').html(data);
                    $('#js_loadBookingSummaryMobile_idWrite').html(data);
                }
            });
        }

        function validateForm(){
            let error       = [];
            /* input required không được bỏ trống */
            $('#formBooking').find('input[required], select[name="*_1"]').each(function(){
                /* đưa vào mảng */
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            /* validate riêng cho số lượng */
            var quantity        = 0;
            $('#formBooking').find('[name^="quantity"]').each(function(){
                let valInput    = parseInt($(this).val()) || 0;
                quantity        += parseInt(valInput) + parseInt(quantity);
            })
            if(quantity<=0) error.push('quantity');
            return error;
        }

        function showHideMessageValidate(nameInput, action = 'show'){
            var element   = $(document).find('[name='+nameInput+']');
            if(action=='show'){
                $(document).find('[data-validate='+nameInput+']').css('display', 'block');
            }else {
                $(document).find('[data-validate='+nameInput+']').css('display', 'none');
            }
        }

    </script>
@endpush