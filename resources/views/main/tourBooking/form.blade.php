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
            <h1 class="titlePage" style="margin-bottom:1.5rem;text-align:center;">Đặt tour du lịch</h1>
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
                        <div id="js_filterForm_dp" class="bookingForm_item">
                            <div class="bookingForm_item_head">
                                Thông tin tour
                            </div>
                            <div class="bookingForm_item_body">
                                <div class="formBox">
                                    <div class="formBox_full">
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div class="flexBox">
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon adult">
                                                        <label class="form-label" for="quantity_adult">Người lớn</label>
                                                        <input type="text" class="form-control" name="quantity_adult" value="{{ !empty(request('adult_tour')) ? request('adult_tour') : null }}">
                                                    </div>
                                                </div>
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon child">
                                                        <label class="form-label" for="quantity_child">Trẻ em (6 - 11 tuổi)</label>
                                                        <input type="text" class="form-control" name="quantity_child" value="{{ !empty(request('child_tour')) ?  request('child_tour') : null }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="messageValidate_error" data-validate="quantity">Tổng số lượng phải lớn hơn 0!</div>
                                        </div>
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <label class="form-label" for="date">Ngày khởi hành</label>
                                            <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" placeholder="YYYY-MM-DD" value="{{ request('date') ?? null }}" readonly="readonly" onChange="loadOptionTour();" />
                                            <div class="messageValidate_error" data-validate="date">{{ config('main.message_validate.not_empty') }}</div>
                                        </div>
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div class="flexBox">
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon location">
                                                        <label class="form-label" for="tour_location_id">Điểm đến</label>
                                                        <select id="js_loadTourByTourLocation_element" class="select2 form-select select2-hidden-accessible" name="tour_location_id" onChange="loadTourByTourLocation(this, 'js_loadTourByTourLocation_idWrite');">
                                                            {{-- <option value="">- Lựa chọn -</option> --}}
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
                                                                        @endphp
                                                                        <option value="{{ $tourLocation->id }}"{{ $selected }}>
                                                                            {{ $tourLocation->display_name }}
                                                                        </option>
                                                                    @endforeach
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="messageValidate_error" data-validate="tour_location_id">{{ config('main.message_validate.not_empty') }}</div>
                                                </div>
                                                <div class="flexBox_item">
                                                    <div class="inputWithIcon location">
                                                        <label class="form-label" for="tour_info_id">Chương trình tour</label>
                                                        <select id="js_loadTourByTourLocation_idWrite" class="select2 form-select select2-hidden-accessible" name="tour_info_id" onChange="loadOptionTour();">
                                                            <!-- loadAjax : loadTourByTourLocation -->
                                                        </select>
                                                    </div>
                                                    <div class="messageValidate_error" data-validate="tour_info_id">{{ config('main.message_validate.not_empty') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div id="js_loadOptionTour_idWrite">
                                                <!-- AJAX: loadDeparture -->
                                            </div>
                                        </div>
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

            loadTourByTourLocation($('#js_loadTourByTourLocation_element'), 'js_loadTourByTourLocation_idWrite');
        });

        // function submitForm(idForm){
        //     event.preventDefault();
        //     const error     = validateForm();
        //     if(error==''){
        //         $('#'+idForm).submit(); 
        //     }else {
        //         /* xuất thông báo */
        //         error.map(function(nameInput){
        //             showHideMessageValidate(nameInput, 'show');
        //         });
        //     }
        // }

        function loadTourByTourLocation(element, idWrite){
            const idTourLocation = $(element).val();
            $.ajax({
                url         : '{{ route("main.tourBooking.loadTour") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    '_token'        	: '{{ csrf_token() }}',
                    tour_location_id    : idTourLocation
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
                    url         : '{{ route("admin.tourBooking.loadOptionTourList") }}',
                    type        : 'get',
                    dataType    : 'json',
                    data        : {
                        '_token'                : '{{ csrf_token() }}',
                        tour_info_id            : idTourInfo,
                        date                    : date
                    },
                    success     : function(data){
                        $('#js_loadOptionTour_idWrite').html(data.content);
                        // loadBookingSummary();
                    }
                });
            }
        }

        // function validateForm(){
        //     let error       = [];
        //     /* input required không được bỏ trống */
        //     $('#formBooking').find('input[required], select[name="*_1"]').each(function(){
        //         /* đưa vào mảng */
        //         if($(this).val()==''){
        //             error.push($(this).attr('name'));
        //         }
        //     })
        //     /* validate riêng cho số lượng */
        //     const valueQuantityAdult    = $('#formBooking').find('[name=quantity_adult_1]').val();
        //     const valueQuantityChild    = $('#formBooking').find('[name=quantity_child]_1').val();
        //     const valueQuantityOld      = $('#formBooking').find('[name=quantity_old]_1').val();
        //     if(valueQuantityAdult==''&&valueQuantityAdult==''&&valueQuantityAdult==''){
        //         error.push('quantity_1');
        //     }
        //     if(valueQuantityAdult==0&&valueQuantityAdult==0&&valueQuantityAdult==0){
        //         error.push('quantity_1');
        //     }
        //     console.log(error);
        //     return error;
        // }

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

        function highLightChoose(element){
            console.log(123);
            $(element).parent().children().each(function(){
                $(this).removeClass('active');
            });
            $(element).addClass('active');
        }

    </script>
@endpush