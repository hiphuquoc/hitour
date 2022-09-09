@extends('main.layouts.main')
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

    <form id="formBooking" action="{{ route('main.shipBooking.create') }}" method="POST">
    @csrf
    <input type="hidden" name="ship_booking_status_id" value="1" />
    <div class="pageContent">
        <div class="container">
            <!-- title -->
            <h1 class="titlePage" style="margin-bottom:1.5rem;text-align:center;">Đặt vé tàu cao tốc</h1>
            <!-- ship box -->
            <div class="pageContent_body">
                <div class="pageContent_body_content">
                    
                    <div class="bookingForm">

                        <div class="bookingForm_item">
                            <div class="bookingForm_item_head">
                                Thông tin liên hệ
                            </div>
                            <div class="bookingForm_item_body">
                                <div class="formBox">
                                    <div class="formBox_full">
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div class="flexBox">
                                                <div class="flexBox_item">
                                                    <div>
                                                        <label class="form-label inputRequired" for="name">Họ và Tên</label>
                                                        <input type="text" class="form-control" name="name" value="" required>
                                                    </div>
                                                    <div class="messageValidate_error" data-validate="name">{{ config('main.message_validate.not_empty') }}</div>
                                                </div>
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon email">
                                                        <label class="form-label" for="email">Email (nếu có)</label>
                                                        <input type="text" class="form-control" name="email" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div class="flexBox">
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon phone">
                                                        <label class="form-label inputRequired" for="phone">Điện thoại</label>
                                                        <input type="text" class="form-control" name="phone" value="" required>
                                                    </div>
                                                    <div class="messageValidate_error" data-validate="phone">{{ config('main.message_validate.not_empty') }}</div>
                                                </div>
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon message">
                                                        <label class="form-label" for="zalo">Zalo (nếu có)</label>
                                                        <input type="text" class="form-control" name="zalo" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bookingForm_item">
                            <div class="bookingForm_item_head">
                                Thông tin chuyến tàu & Số lượng
                            </div>
                            <div class="bookingForm_item_body">

                                <div class="formBox">
                                    <!-- One Row -->
                                    <div class="formBox_full_item">
                                        <div class="flexBox">
                                            <div class="flexBox_item">
                                                <div class="inputWithIcon location">
                                                    <label class="form-label inputRequired" for="ship_port_departure_id">Điểm khởi hành</label>
                                                    <select class="select2 form-select select2-hidden-accessible" name="ship_port_departure_id" onChange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite');">
                                                        <option value="">- Lựa chọn -</option>
                                                        @if(!empty($ports))
                                                            @foreach($ports as $port)
                                                                @php
                                                                    $selected   = null;
                                                                    $portFull   = \App\Helpers\Build::buildFullShipPort($port);
                                                                @endphp
                                                                <option value="{{ $port->id }}"{{ $selected }}>
                                                                    {!! $portFull !!}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="messageValidate_error" data-validate="ship_port_departure_id">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                            <div class="flexBox_item">
                                                <div class="inputWithIcon location">
                                                    <label class="form-label inputRequired" for="ship_port_location_id">Điểm đến</label>
                                                    <select id="js_loadShipLocationByShipDeparture_idWrite" class="select2 form-select select2-hidden-accessible" name="ship_port_location_id">
                                                        <option value="">- Lựa chọn -</option>
                                                    </select>
                                                </div>
                                                <div class="messageValidate_error" data-validate="ship_port_location_id">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="formBox_full_item">
                                        <input type="hidden" id="type_booking" name="type_booking" value="2">
                                        <div class="chooseTripBox">
                                            <div class="chooseTripBox_item active" onClick="changeValueTypeBooking(this, 2);">
                                                Khứ hồi
                                            </div>
                                            <div class="chooseTripBox_item" onClick="changeValueTypeBooking(this, 1);">
                                                Một chiều
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="formBox_full_item">
                                        <div class="flexBox">
                                            <div class="flexBox_item">
                                                <div class="inputWithIcon date">
                                                    <label class="form-label inputRequired" for="date">Ngày đi</label>
                                                    <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" placeholder="YYYY-MM-DD" readonly="readonly" onChange="loadDeparture(1);" required>
                                                </div>
                                                <div class="messageValidate_error" data-validate="date">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                            <div class="flexBox_item">
                                                <div id="js_filterForm_dateRound">
                                                    <div class="inputWithIcon date">
                                                        <label class="form-label" for="date_round">Ngày về</label>
                                                        <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_round" placeholder="YYYY-MM-DD" readonly="readonly" onChange="loadDeparture(2);">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="formBox_full_item">
                                        <div class="flexBox">
                                            <div class="flexBox_item">
                                                <div class="inputWithIcon adult">
                                                    <label class="form-label" for="quantity_adult">Người lớn</label>
                                                    <input type="text" class="form-control" name="quantity_adult" value="">
                                                </div>
                                            </div>
                                            <div class="flexBox_item">
                                                <div class="inputWithIcon child">
                                                    <label class="form-label" for="quantity_child">Trẻ em (6 - 11 tuổi)</label>
                                                    <input type="text" class="form-control" name="quantity_child" value="">
                                                </div>
                                            </div>
                                            <div class="flexBox_item">
                                                <div class="inputWithIcon old">
                                                    <label class="form-label" for="quantity_old">Cao tuổi (trên 60 tuổi)</label>
                                                    <input type="text" class="form-control" name="quantity_old" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="messageValidate_error" data-validate="quantity">Tổng số lượng vé phải lớn hơn 0!</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Departture 1 -->
                        <div id="js_loadDeparture_dp1" class="bookingForm_item">

                        </div>
                        <!-- Departture 2 -->
                        <div id="js_loadDeparture_dp2" class="bookingForm_item">
                            
                        </div>
                    </div>

                </div>
                <div class="pageContent_body_sidebar">
                    @include('main.shipBooking.sidebar')
                </div>
            </div>

        </div>
    </div>
    </form>
@endsection
@push('scripts-custom')
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
    <script type="text/javascript">
        $(window).on('load', function () {
            /* fixed sidebar khi scroll */
            const elemt                 = $('.js_scrollFixed');
            const widthElemt            = elemt.parent().width();
            const positionTopElemt      = elemt.offset().top;
            const heightFooter          = 500;
            $(window).scroll(function(){
                const positionScrollbar = $(window).scrollTop();
                const scrollHeight      = $('body').prop('scrollHeight');
                const heightLimit       = parseInt(scrollHeight - heightFooter - elemt.outerHeight());
                if(positionScrollbar>positionTopElemt&&positionScrollbar<heightLimit){
                    elemt.addClass('scrollFixedSidebar').css({
                        'width'         : widthElemt,
                        'margin-top'    : '1.5rem'
                    });
                }else {
                    elemt.removeClass('scrollFixedSidebar').css({
                        'width'         : 'unset',
                        'margin-top'    : 0
                    });
                }
            });
        });

        $('#formBooking').find('input, select').each(function(){
            $(this).on('change', () => {
                loadBookingSummary();
                const nameInput   = $(this).attr('name');
                showHideMessageValidate(nameInput, 'hide');
                if(nameInput=='quantity_adult'||nameInput=='quantity_child'||nameInput=='quantity_old'){
                    showHideMessageValidate('quantity', 'hide');
                }
            })
            $(this).on('click', () => {
                const nameInput   = $(this).attr('name');
                showHideMessageValidate(nameInput, 'hide');
                if(nameInput=='quantity_adult'||nameInput=='quantity_child'||nameInput=='quantity_old'){
                    showHideMessageValidate('quantity', 'hide');
                }
            })
        })

        function submitForm(idForm){
            event.preventDefault();
            const error     = validateForm();
            if(error==''){
                $('#'+idForm).submit(); 
            }else {
                /* xuất thông báo */
                error.map(function(nameInput){
                    showHideMessageValidate(nameInput, 'show');
                });
            }
            
        }

        function chooseDeparture(elemt, code, idShipPrice, timeDeparture, timeArrive, typeTicket, partner){
            $('#js_chooseDeparture_dp'+code).val(idShipPrice+'|'+timeDeparture+'|'+timeArrive+'|'+typeTicket+'|'+partner);
            $(elemt).parent().parent().parent().find('.option').removeClass('active');
            $(elemt).addClass('active');
            loadBookingSummary();
        }

        function checkedInput(idSearch, elemt){
            $('#'+idSearch).find('input[type=radio]').each(function(){
                $(this).prop('checked', false);
                $(this).parent().removeClass('active');
            });
            $(elemt).find('input[type=radio]').prop('checked', true);
            $(elemt).addClass('active');
        }

        function filterForm(typeBooking){
            if(typeBooking=='oneTrip'){
                $('#js_filterForm_dateRound').hide();
                $('#js_loadDeparture_dp2').hide();
            }else {
                $('#js_filterForm_dateRound').show();
                $('#js_loadDeparture_dp2').show();
            }
        }

        function changeValueTypeBooking(elemtBtn, valueNew){
            const parent = $(elemtBtn).parent();
            /* bỏ checked và class active tất cả */
            parent.children().each(function(){
                $(this).removeClass('active');
            })
            /* thêm lại checked và class active cho button được chọn */
            $(elemtBtn).addClass('active');
            $('#type_booking').val(valueNew);
            
            if(valueNew==1){
                /* filter form */
                filterForm('oneTrip');
                loadDeparture(1);
                /* loadDeparture */
            }else {
                /* filter form */
                filterForm('roundTrip');
                loadTwoDeparture();
            }
        }

        function loadTwoDeparture(){
            loadDeparture(1);
            loadDeparture(2);
        }

        function resetDeparture(code, date){
            if(code==1){
                $('#formBooking').find('[name=date]').val(date);
            }else {
                $('#formBooking').find('[name=date_round]').val(date);
            }
            loadDeparture(code);
        }

        function loadDeparture(code){
            const typeBooking           = $(document).find('[name=type_booking]').val();
            const idPortShipDeparture   = $(document).find('[name=ship_port_departure_id]').val();
            const idPortShipLocation    = $(document).find('[name=ship_port_location_id]').val();
            var date                    = '';
            if(code==1){
                date                    = $(document).find('[name=date]').val();
            }else {
                date                    = $(document).find('[name=date_round]').val();
            }
            if(date!=''&&idPortShipDeparture!=0&&idPortShipLocation!=0){
                $.ajax({
                    url         : '{{ route("main.shipBooking.loadDeparture") }}',
                    type        : 'post',
                    dataType    : 'json',
                    data        : {
                        '_token'                : '{{ csrf_token() }}',
                        code                    : code,
                        ship_port_departure_id  : idPortShipDeparture,
                        ship_port_location_id   : idPortShipLocation,
                        date                    : date
                    },
                    success     : function(data){
                        $('#js_loadDeparture_dp'+code).html(data);
                        loadBookingSummary();
                    }
                });
            }
        }

        function loadShipLocationByShipDeparture(element, idWrite){
            const idShipPort = $(element).val();
            $.ajax({
                url         : '{{ route("main.shipBooking.loadShipLocation") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    ship_port_id    : idShipPort
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                    loadTwoDeparture();
                }
            });
        }

        function validateForm(){
            let error       = [];
            /* input required không được bỏ trống */
            $('#formBooking').find('input[required], select').each(function(){
                /* đưa vào mảng */
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            /* validate riêng cho số lượng */
            const valueQuantityAdult    = $('#formBooking').find('[name=quantity_adult]').val();
            const valueQuantityChild    = $('#formBooking').find('[name=quantity_child]').val();
            const valueQuantityOld      = $('#formBooking').find('[name=quantity_old]').val();
            if(valueQuantityAdult==''&&valueQuantityAdult==''&&valueQuantityAdult==''){
                error.push('quantity');
            }
            if(valueQuantityAdult==0&&valueQuantityAdult==0&&valueQuantityAdult==0){
                error.push('quantity');
            }
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

        function loadBookingSummary(){
            const dataForm = $("#formBooking").serializeArray();
            $.ajax({
                url         : '{{ route("main.shipBooking.loadBookingSummary") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    dataForm    : dataForm
                },
                success     : function(data){
                    $('#js_loadBookingSummary_idWrite').html(data);
                }
            });
        }
    </script>
@endpush