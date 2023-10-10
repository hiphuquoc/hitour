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

    <form id="formBooking" action="{{ route('main.tourBooking.create') }}" method="POST">
    @csrf
    {{-- <input type="hidden" name="ship_booking_status_id" value="1" /> --}}
    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <!-- title -->
                <h1 class="titlePage" style="margin-bottom:0.5rem;">Đặt Tour du lịch</h1>
                {{-- <div style="margin-bottom:1rem;">Quý khách vui lòng điền thông tin liên hệ và xem lại đặt chỗ.</div> --}}
                <!-- ship box -->
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
                        
                        <div class="bookingForm">
                            <!-- chứng nhận -->
                            <div class="bookingForm_item">
                                @include('main.tourBooking.certifiedTour')
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
                                                {{-- <div>
                                                    <label class="form-label inputRequired" for="name">Họ và Tên</label>
                                                    <input type="text" class="form-control" name="name" value="" required>
                                                </div>
                                                <div class="messageValidate_error" data-validate="name">{{ config('main.message_validate.not_empty') }}</div> --}}
                                                <div class="inputWithLabelInside">
                                                    <label class="inputRequired" for="name">Họ tên</label>
                                                    <input type="text" id="name" name="name" value="" onkeyup="validateWhenType(this)" required />
                                                </div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                {{-- <div>
                                                    <label class="form-label" for="email">Email (nếu có)</label>
                                                    <input type="text" class="form-control" name="email" value="">
                                                </div> --}}
                                                <div class="inputWithLabelInside email">
                                                    <label for="email">Email (nếu có)</label>
                                                    <input type="text" id="email" name="email" value="" onkeyup="validateWhenType(this, 'email')" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                {{-- <div>
                                                    <label class="form-label inputRequired" for="phone">Điện thoại</label>
                                                    <input type="text" class="form-control" name="phone" value="" required>
                                                </div>
                                                <div class="messageValidate_error" data-validate="phone">{{ config('main.message_validate.not_empty') }}</div> --}}
                                                <div class="inputWithLabelInside phone">
                                                    <label class="inputRequired" for="phone">Điện thoại</label>
                                                    <input type="text" id="phone" name="phone" value="" onkeyup="validateWhenType(this, 'phone')" required />
                                                </div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                {{-- <div>
                                                    <label class="form-label" for="zalo">Zalo (nếu có)</label>
                                                    <input type="text" class="form-control" name="zalo" value="">
                                                </div> --}}
                                                <div class="inputWithLabelInside message">
                                                    <label for="zalo">Zalo (nếu có)</label>
                                                    <input type="text" id="zalo" name="zalo" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bookingForm_item_footer">
                                    *Đây là thông tin của Người Đặt để nhân viên Hitour có thể liên hệ và hỗ trợ bạn hoàn tất booking này!
                                </div>
                            </div>
                            <!-- thông tin dịch vụ -->
                            <div class="bookingForm_item">
                                <div class="bookingForm_item_head">
                                    Thông tin tour
                                </div>
                                <div class="bookingForm_item_body" style="border-radius:inherit;">
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                {{-- <div class="inputWithIcon location">
                                                    <label class="form-label" for="tour_location_id">Điểm đến</label>
                                                    <select id="js_loadTourByTourLocation_element" class="select2 form-select select2-hidden-accessible" name="tour_location_id" onChange="loadTourByTourLocation(this, 'js_loadTourByTourLocation_idWrite');">
                                                        @if(!empty($tourLocations))
                                                            @php
                                                                $dataTourLocation   = [];
                                                                foreach($tourLocations as $tourLocation){
                                                                    $dataTourLocation[$tourLocation->region->name][] = $tourLocation;
                                                                }
                                                            @endphp         
                                                            @foreach($dataTourLocation as $region => $tourLocationsByRegion)
                                                                <optgroup label="{{ $region }}, Việt Nam">
                                                                @foreach($tourLocationsByRegion as $tourLocation)
                                                                    @php
                                                                        $selected   = null;
                                                                        if(!empty(request('tour_location_id'))&&request('tour_location_id')==$tourLocation->id) $selected = 'selected';
                                                                    @endphp
                                                                    <option value="{{ $tourLocation->id }}" {{ $selected }}>
                                                                        {{ $tourLocation->display_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="messageValidate_error" data-validate="tour_location_id">{{ config('main.message_validate.not_empty') }}</div> --}}
                                                <div class="inputWithLabelInside location">
                                                    <label class="form-label" for="tour_location_id">Điểm đến</label>
                                                    <select id="js_loadTourByTourLocation_element" class="select2 form-select select2-hidden-accessible" name="tour_location_id" onChange="loadTourByTourLocation(this, 'js_loadTourByTourLocation_idWrite');">
                                                        @if(!empty($tourLocations))
                                                            @php
                                                                $dataTourLocation   = [];
                                                                foreach($tourLocations as $tourLocation){
                                                                    $dataTourLocation[$tourLocation->region->name][] = $tourLocation;
                                                                }
                                                            @endphp         
                                                            @foreach($dataTourLocation as $region => $tourLocationsByRegion)
                                                                <optgroup label="{{ $region }}, Việt Nam">
                                                                @foreach($tourLocationsByRegion as $tourLocation)
                                                                    @php
                                                                        $selected   = null;
                                                                        if(!empty(request('tour_location_id'))&&request('tour_location_id')==$tourLocation->id) $selected = 'selected';
                                                                    @endphp
                                                                    <option value="{{ $tourLocation->id }}" {{ $selected }}>
                                                                        {{ $tourLocation->display_name }}
                                                                    </option>
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="formColumnCustom_item">
                                                <div class="inputWithLabelInside">
                                                    <label class="form-label" for="tour_info_id">Chương trình tour</label>
                                                    <select id="js_loadTourByTourLocation_idWrite" class="select2 form-select select2-hidden-accessible" name="tour_info_id" onChange="loadOptionTour();">
                                                        <!-- loadAjax : loadTourByTourLocation -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div class="formColumnCustom">
                                            <div class="formColumnCustom_item">
                                                {{-- <div>
                                                    <label class="form-label" for="date">Ngày khởi hành</label>
                                                    <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" placeholder="YYYY-MM-DD" value="{{ request('date') ?? null }}" readonly="readonly" onChange="loadOptionTour();" />
                                                </div>
                                                <div class="messageValidate_error" data-validate="date">{{ config('main.message_validate.not_empty') }}</div> --}}
                                                <div class="inputWithLabelInside date">
                                                    <label class="form-label" for="date">Ngày khởi hành</label>
                                                    <input type="text" class="form-control flatpickr-basic flatpickr-input active" id="date" name="date" placeholder="YYYY-MM-DD" value="{{ request('date') ?? null }}" readonly="readonly" onChange="loadOptionTour();" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div>
                                            <label class="form-label" for="quantity_adult">Chọn tiêu chuẩn Tour</label>
                                        </div>
                                        <div id="js_loadOptionTour_idWrite"> 
                                            <!-- AJAX: loadDeparture -->
                                            <div style="color:rgb(0,123,255);">Vui lòng chọn Ngày khởi hành và Chương trình Tour trước!</div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="bookingForm_item_body_item">
                                        <div id="js_loadFormQuantityByOption_idWrite">
                                            <!-- AJAX: loadDeparture -->
                                        </div>
                                    </div>
                                     <!-- One Row -->
                                     <div class="bookingForm_item_body_item">
                                        <div class="textareaWithLabelInside">
                                            <label class="form-label" for="note_customer">Ghi chú của bạn</label>
                                            <textarea name="note_customer" rows="3" placeholder="Nếu có ghi chú đặc biệt cho booking của bạn, hãy điền ở đây!"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
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
            loadTourByTourLocation($('#js_loadTourByTourLocation_element'), 'js_loadTourByTourLocation_idWrite', "{{ request('tour_info_id') ?? 0  }}");
        });

        $('#formBooking').find('input, select, textarea').each(function(){
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

        function loadTourByTourLocation(element, idWrite, idTourDefault = 0){
            const idTourLocation    = $(element).val();
            $.ajax({
                url         : '{{ route("main.tourBooking.loadTour") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    '_token'        	: '{{ csrf_token() }}',
                    tour_location_id    : idTourLocation,
                    tour_info_id        : idTourDefault
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                    loadOptionTour();
                }
            });
        }

        function loadOptionTour(){
            const date                  = $(document).find('[name=date]').val();
            const idTourInfo            = $(document).find('[name=tour_info_id]').val();
            if(date!=''&&idTourInfo!=''){
                $.ajax({
                    url         : '{{ route("main.tourBooking.loadOptionTour") }}',
                    type        : 'get',
                    dataType    : 'html',
                    data        : {
                        tour_info_id            : idTourInfo,
                        date                    : date
                    },
                    success     : function(data){
                        $('#js_loadOptionTour_idWrite').html(data);
                        loadFormQuantityByOption();
                        loadBookingSummary();
                    }
                });
            }
        }

        function loadFormQuantityByOption(){
            const idOption = $('#tour_option_id').val();
            $.ajax({
                url         : '{{ route("main.tourBooking.loadFormQuantityByOption") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    tour_option_id  : idOption
                },
                success     : function(data){
                    $('#js_loadFormQuantityByOption_idWrite').html(data);
                    loadBookingSummary()
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

        function loadBookingSummary(){
            var dataForm = $("#formBooking").serializeArray();
            $.ajax({
                url         : '{{ route("main.tourBooking.loadBookingSummary") }}',
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

        function highLightChoose(element, valueChange){
            $(element).parent().children().each(function(){
                $(this).removeClass('active');
            });
            $(element).addClass('active');
            $('#tour_option_id').val(valueChange);
            loadFormQuantityByOption();
        }

    </script>
@endpush