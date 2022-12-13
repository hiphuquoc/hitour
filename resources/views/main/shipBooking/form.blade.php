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
    <div class="pageContent background">
        <div class="sectionBox">
            <div class="container">
                <!-- title -->
                <h1 class="titlePage" style="margin-bottom:0.5rem;">Đặt vé tàu cao tốc</h1>
                <div style="margin-bottom:1rem;">Quý khách vui lòng điền thông tin liên hệ, chọn giờ khởi hành và xem lại đặt chỗ.</div>
                <!-- ship box -->
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
                        
                        <div class="bookingForm">
                             <!-- chứng nhận -->
                             <div class="bookingForm_item">
                                @include('main.serviceBooking.certifiedService')
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
                                                <div>
                                                    <label class="form-label inputRequired" for="name">Họ và Tên</label>
                                                    <input type="text" class="form-control" name="name" value="" required>
                                                </div>
                                                <div class="messageValidate_error" data-validate="name">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                <div>
                                                    <label class="form-label" for="email">Email (nếu có)</label>
                                                    <input type="text" class="form-control" name="email" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                <div>
                                                    <label class="form-label inputRequired" for="phone">Điện thoại</label>
                                                    <input type="text" class="form-control" name="phone" value="" required>
                                                </div>
                                                <div class="messageValidate_error" data-validate="phone">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                <div>
                                                    <label class="form-label" for="zalo">Zalo (nếu có)</label>
                                                    <input type="text" class="form-control" name="zalo" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <label></label>
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
                                </div>
                            </div>
                            <!-- Departture 1 & 2 -->
                            @php
                                /* value mặc định ngày khởi hành */
                                $valueDate_1        = date('Y-m-d', time() + 86400);
                                $valueDate_2        = date('Y-m-d', time() + 172800);
                                if(!empty(request('date_1'))) {
                                    $valueDate_1    = date('Y-m-d', strtotime(request('date_1')));
                                    $valueDate_2    = date('Y-m-d', strtotime(request('date_1')) + 86400);
                                }
                                /* lấy tên của cảng khởi hành - cảng đến để tải Ajax */
                                $namePortDeparture  = null;
                                $namePortLocation   = null;
                                foreach($ports as $port){
                                    if(!empty(request('ship_port_departure_id'))&&request('ship_port_departure_id')==$port->id) $namePortDeparture  = $port->name;
                                    if(!empty(request('ship_port_location_id'))&&request('ship_port_location_id')==$port->id) $namePortLocation     = $port->name;
                                }
                            @endphp
                            @for($i=1;$i<=2;++$i)
                            @php
                                $required       = $i==1 ? 'required' : null;
                                $requiredClass  = $i==1 ? 'inputRequired' : null;
                            @endphp
                            <div id="js_filterForm_dp{{ $i }}" class="bookingForm_item">
                                <div class="bookingForm_item_head">
                                    {{ $i==1 ? 'Chuyến đi' : 'Chuyến về' }}
                                </div>
                                <div class="bookingForm_item_body">
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                <div>
                                                    <label class="form-label {{ $requiredClass }}" for="date_{{ $i }}">Ngày khởi hành</label>
                                                    <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_{{ $i }}" placeholder="YYYY-MM-DD" value="{{ $i==1 ? $valueDate_1 : $valueDate_2 }}" readonly="readonly" onChange="loadDeparture({{ $i }});" {{ $required }} />
                                                </div>
                                                <div class="messageValidate_error" data-validate="date_{{ $i }}">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                <div class="inputWithIcon location">
                                                    <label class="form-label {{ $requiredClass }}" for="ship_port_departure_id_{{ $i }}">Điểm khởi hành</label>
                                                    <select id="js_loadShipLocationByShipDeparture_element_{{ $i }}" class="select2 form-select select2-hidden-accessible" name="ship_port_departure_id_{{ $i }}" onChange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite_{{ $i }}', {{ $i }});">
                                                        <option value="">- Lựa chọn -</option>
                                                        @if(!empty($ports))
                                                            @foreach($ports as $port)
                                                                @php
                                                                    $selected   = null;
                                                                    if(!empty(request('ship_port_departure_id'))&&$i==1&&request('ship_port_departure_id')==$port->id) $selected = 'selected';
                                                                    if(!empty(request('ship_port_location_id'))&&$i==2&&request('ship_port_location_id')==$port->id) $selected = 'selected';
                                                                    $portFull   = \App\Helpers\Build::buildFullShipPort($port);
                                                                @endphp
                                                                <option value="{{ $port->id }}"{{ $selected }}>
                                                                    {!! $portFull !!}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="messageValidate_error" data-validate="ship_port_departure_id_{{ $i }}">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                <div class="inputWithIcon location">
                                                    <label class="form-label {{ $requiredClass }}" for="ship_port_location_id_{{ $i }}">Điểm đến</label>
                                                    <select id="js_loadShipLocationByShipDeparture_idWrite_{{ $i }}" class="select2 form-select select2-hidden-accessible" name="ship_port_location_id_{{ $i }}" onChange="loadDeparture({{ $i }});">
                                                        <option value="">- Lựa chọn -</option>
                                                    </select>
                                                </div>
                                                <div class="messageValidate_error" data-validate="ship_port_location_id_{{ $i }}">{{ config('main.message_validate.not_empty') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                <div class="inputWithIcon adult">
                                                    <label class="form-label" for="quantity_adult_{{ $i }}">Người lớn</label>
                                                    <input type="text" class="form-control" name="quantity_adult_{{ $i }}" placeholder="0" value="{{ !empty(request('adult_ship')) ? request('adult_ship') : null }}" onInput="loadBookingSummary();">
                                                </div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                <div class="inputWithIcon child">
                                                    <label class="form-label" for="quantity_child_{{ $i }}">Trẻ em (6 - 11 tuổi)</label>
                                                    <input type="text" class="form-control" name="quantity_child_{{ $i }}" placeholder="0" value="{{ !empty(request('child_ship')) ?  request('child_ship') : null }}" onInput="loadBookingSummary();">
                                                </div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                <div class="inputWithIcon old">
                                                    <label class="form-label" for="quantity_old_{{ $i }}">Cao tuổi (trên 60 tuổi)</label>
                                                    <input type="text" class="form-control" name="quantity_old_{{ $i }}" placeholder="0" value="{{ !empty(request('old_ship')) ? request('old_ship') : null }}" onInput="loadBookingSummary();">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="messageValidate_error" data-validate="quantity_{{ $i }}">Tổng số lượng vé phải lớn hơn 0!</div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div id="js_loadDeparture_dp{{ $i }}">
                                            <!-- AJAX: loadDeparture -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
    
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.shipBooking.sidebar')
                    </div>
                </div>
    
            </div>

        </div>
    </div>
    </form>
@endsection
@push('bottom')
    <!-- button book tour mobile -->
    <div class="show-990">
        <div class="callBookTourMobile">
            <div class="callBookTourMobile_textNormal maxLine_1" onClick="showHideBox();">
                <i class="fa-solid fa-eye"></i>Tóm tắt booking
            </div>
            <div class="callBookTourMobile_button"><h2 onclick="submitForm('formBooking');">Xác nhận</h2></div>
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
        $(window).on('load', function () {
            /* tải selectbox điểm đến (nếu có điểm khởi hành) */
            loadShipLocationByShipDeparture($('#js_loadShipLocationByShipDeparture_element_1'), 'js_loadShipLocationByShipDeparture_idWrite_1', 1, '{{ $namePortLocation }}');
            loadShipLocationByShipDeparture($('#js_loadShipLocationByShipDeparture_element_2'), 'js_loadShipLocationByShipDeparture_idWrite_2', 2, '{{ $namePortDeparture }}');
        });

        $('#formBooking').find('input, select').each(function(){
            $(this).on('input', () => {
                loadBookingSummary();
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
                /* scroll đến thông báo đầu tiên */
                $('.messageValidate_error:visible').each(function(){
                    $('html, body').animate({
                        scrollTop: $(this).offset().top - 90
                    }, 300); 
                    return false;
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
                $('#js_filterForm_dp2').hide();
            }else {
                $('#js_filterForm_dp2').show();
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
            const idPortShipDeparture   = $(document).find('[name=ship_port_departure_id_'+code+']').val();
            const idPortShipLocation    = $(document).find('[name=ship_port_location_id_'+code+']').val();
            const date                  = $(document).find('[name=date_'+code+']').val();
            if(date!=''&&idPortShipDeparture!=0&&idPortShipLocation!=0){
                $.ajax({
                    url         : '{{ route("main.shipBooking.loadDeparture") }}',
                    type        : 'get',
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

        function loadShipLocationByShipDeparture(element, idWrite, code, namePortActive = null){
            const idShipPort = $(element).val();
            $.ajax({
                url         : '{{ route("main.shipBooking.loadShipLocation") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    '_token'            : '{{ csrf_token() }}',
                    ship_port_id        : idShipPort,
                    name_port_active    : namePortActive
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                    loadDeparture(code);
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
            const valueQuantityAdult    = $('#formBooking').find('[name=quantity_adult_1]').val();
            const valueQuantityChild    = $('#formBooking').find('[name=quantity_child]_1').val();
            const valueQuantityOld      = $('#formBooking').find('[name=quantity_old]_1').val();
            if(valueQuantityAdult==''&&valueQuantityAdult==''&&valueQuantityAdult==''){
                error.push('quantity_1');
            }
            if(valueQuantityAdult==0&&valueQuantityAdult==0&&valueQuantityAdult==0){
                error.push('quantity_1');
            }
            // /* validate riêng cho giờ tàu */
            // const valueTimeShip         = $(document).find('[name^="dp1"]').val();
            // if(valueTimeShip==''||typeof valueTimeShip=='undefined'){
            //     error.push('dp1');
            // }
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
                type        : 'get',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    dataForm    : dataForm
                },
                success     : function(data){
                    $('#js_loadBookingSummary_idWrite').html(data);
                    $('#js_loadBookingSummaryMobile_idWrite').html(data);
                }
            });
        }
    </script>
@endpush