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
        $checkIn            = strtotime($dataForm['check_in']);
        $checkOut           = $checkIn + ($dataForm['number_night']*86400);
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
                        <form id="formBooking" action="{{ route('main.hotelBooking.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="hotel_name" value="{{ $hotel->name }}" /> 
                            <input type="hidden" name="hotel_info_id" value="{{ $hotel->id }}" />
                            <input type="hidden" name="hotel_price_id" value="{{ $price->id }}" />
                            <input type="hidden" name="quantity" value="{{ $quantity }}" />
                            <div class="bookingForm">
                                <div class="bookingForm_item">

                                    @include('main.hotelBooking.hotelInfo', [
                                        'hotel' => $hotel,
                                        'room'  => $room,
                                        'price' => $price
                                    ])

                                </div>

                                <!-- thông tin nhận phòng -->
                                <div class="bookingForm_item">
                                    <div class="bookingForm_item_head">
                                        Thông tin nhận phòng
                                    </div>
                                    <div class="bookingForm_item_body" style="border-radius:inherit;">
                                        <!-- One Row -->
                                        <div class="bookingForm_item_body_item">
                                            <div class="formColumnCustom">
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside date">
                                                        <label for="check_in">Ngày Check-In</label>
                                                        <input type="text" class="form-control flatpickr-basic flatpickr-input active" id="check_in" name="check_in" placeholder="YYYY-MM-DD" value="{{ $dataForm['check_in'] ?? null }}" readonly="readonly" onchange="updateCheckOutDate();" />
                                                    </div>
                                                </div>
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside night">
                                                        <label for="number_night">Số đêm</label>
                                                        <select class="select2 form-select select2-hidden-accessible" id="number_night" name="number_night" onchange="updateCheckOutDate();">
                                                            @for($i=1;$i<31;++$i)
                                                                @php
                                                                    $selected = null;
                                                                    if(!empty($dataForm['number_night'])&&$dataForm['number_night']==$i) $selected = 'selected';
                                                                @endphp
                                                                <option value="{{ $i }}" {{ $selected }}>{{ $i }} đêm</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="formColumnCustom_item">
                                                    <div class="inputWithLabelInside date disabled">
                                                        <label for="check_out">Ngày Check-Out</label>
                                                        <input type="text" class="form-control flatpickr-basic flatpickr-input active" id="check_out" name="check_out" placeholder="YYYY-MM-DD" value="" readonly="readonly" disabled />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bookingForm_item_body_item">
                                            @include('main.hotelBooking.inputQuantityAndRoom')
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
                                        *Yêu cầu này sẽ được Chỗ Nghỉ tiếp nhận để hỗ trợ Quý khách tốt nhất trong quá trình nhận phòng!
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
@endsection
@push('modal')
    <!-- ===== BOOKING MODAL -->
    <div id="bookingModal" class="bookingModal">
        <div class="bookingModal_box">
            <div class="bookingModal_box_body customScrollBar-y">
                @include('main.hotelBooking.formModal')
            </div>
            <div class="bookingModal_box_footer">
                <div class="button buttonCancel" onclick="openCloseModalEditHotelPrice('bookingModal');">Hủy thay đổi</div>
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
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-repeater.min.js') }}"></script>
    <script type="text/javascript">
        $(window).ready(function(){

            loadBookingSummary();

            updateCheckOutDate();

        })

        // Function để tính và cập nhật ngày Check-Out
        function updateCheckOutDate() {
            // Lấy giá trị của ngày Check-In và số đêm từ các input
            const checkInDate = new Date($("#check_in").val());
            const numberOfNights = parseInt($("#number_night").val());
            if (!isNaN(numberOfNights) && checkInDate instanceof Date && !isNaN(checkInDate)) {
                // Tính ngày Check-Out bằng cách thêm số đêm vào ngày Check-In
                const checkOutDate = new Date(checkInDate);
                checkOutDate.setDate(checkOutDate.getDate() + numberOfNights);

                // Định dạng ngày Check-Out thành "YYYY-MM-DD"
                const checkOutDateString = checkOutDate.toISOString().split('T')[0];

                // Cập nhật giá trị của input Ngày Check-Out
                $('#check_out').val(checkOutDateString);
            }
        }

        function openCloseModalEditHotelPrice(idModal){
            const elementModal  = $('#'+idModal);
            const flag          = elementModal.css('display');
            /* tooggle */
            if(flag=='none'){
                elementModal.css('display', 'flex');
                $('#js_openCloseModal_blur').addClass('blurBackground');
                $('body').css('overflow', 'hidden');
            }else {
                elementModal.css('display', 'none');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
                $('body').css('overflow', 'unset');
            }
            lazyLoadImagesGoogleCloud();
            // $(window).scroll(function() {
            //     lazyLoadImagesGoogleCloud();
            // });
        }

        // $('.flatpickr-input').flatpickr({
        //     mode: 'range'
        // });

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
            const idHotelPrice  = $(document).find('[name=modal_hotel_price_id]').val();
            /* điền giá trị qua form chính */
            form.find('[name="hotel_price_id"]').val(idHotelPrice);
            /* submit */
            form.attr('action', '{{ route("main.hotelBooking.form") }}');
            form.attr('method', 'GET');
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
                    $('input[name='+nameInput+']').parent().addClass('validateErrorEmpty');
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
            $('#formBooking').find('input[required]').each(function(){
                /* đưa vào mảng */
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
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