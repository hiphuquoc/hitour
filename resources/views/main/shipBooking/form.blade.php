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

    <form id="formBooking" action="{{ route('main.shipBooking.handle') }}" method="POST">
    @csrf
    <div class="pageContent">
        <div class="container">
            <!-- title -->
            {{-- <h1 class="titlePage">Đặt vé tàu cao tốc</h1> --}}
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
                                                    <label class="form-label inputRequired" for="customer_name">Họ và Tên</label>
                                                    <input type="text" class="form-control" name="customer_name" value="" required>
                                                </div>
                                                <div class="flexBox_item inputWithIcon email">
                                                    <label class="form-label" for="customer_email">Email (nếu có)</label>
                                                    <input type="text" class="form-control" name="customer_email" value="">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div class="flexBox">
                                                <div class="flexBox_item inputWithIcon phone">
                                                    <label class="form-label inputRequired" for="customer_phone">Điện thoại</label>
                                                    <input type="text" class="form-control" name="customer_phone" value="" required>
                                                </div>
                                                <div class="flexBox_item inputWithIcon message">
                                                    <label class="form-label" for="customer_zalo">Zalo (nếu có)</label>
                                                    <input type="text" class="form-control" name="customer_zalo" value="">
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
                                            <div class="flexBox_item inputWithIcon location">
                                                <label class="form-label inputRequired" for="ship_port_departure_id">Điểm khởi hành</label>
                                                <select class="select2 form-select select2-hidden-accessible" name="ship_port_departure_id" onChange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite');">
                                                    <option value="0">- Lựa chọn -</option>
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
                                            <div class="flexBox_item inputWithIcon location">
                                                <label class="form-label inputRequired" for="ship_port_location_id">Điểm đến</label>
                                                <select id="js_loadShipLocationByShipDeparture_idWrite" class="select2 form-select select2-hidden-accessible" name="ship_port_location_id">
                                                    <option value="0">- Lựa chọn -</option>
                                                </select>
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
                                            <div class="flexBox_item inputWithIcon date">
                                                <label class="form-label inputRequired" for="date">Ngày đi</label>
                                                <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" placeholder="YYYY-MM-DD" readonly="readonly">
                                            </div>
                                            <div class="flexBox_item">
                                                <div id="js_filterForm_dateRound">
                                                    <div class="inputWithIcon date">
                                                        <label class="form-label inputRequired" for="date_round">Ngày về</label>
                                                        <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_round" placeholder="YYYY-MM-DD" readonly="readonly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="formBox_full_item">
                                        <div class="flexBox">
                                            <div class="flexBox_item inputWithIcon adult">
                                                <label class="form-label" for="quantity_adult">Người lớn</label>
                                                <input type="text" class="form-control" name="quantity_adult" value="">
                                            </div>
                                            <div class="flexBox_item inputWithIcon child">
                                                <label class="form-label" for="quantity_child">Trẻ em (6 - 11 tuổi)</label>
                                                <input type="text" class="form-control" name="quantity_child" value="">
                                            </div>
                                            <div class="flexBox_item inputWithIcon old">
                                                <label class="form-label" for="quantity_old">Cao tuổi (trên 60 tuổi)</label>
                                                <input type="text" class="form-control" name="quantity_old" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- One Row -->
                                    <div class="formBox_full_item">
                                        <div onClick="loadDeparture();">text function</div>
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

        function submitForm(idForm){
            $('#'+idForm).submit();
        }

        function chooseDeparture(elemt, code, time, typeTicket){
            $('#js_chooseDeparture_dp'+code).val(time+'-'+typeTicket);
            $(document).find('.option').removeClass('active');
            $(elemt).addClass('active');
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
            }else {
                $('#js_filterForm_dateRound').show();
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
                /* loadDeparture */
            }else {
                /* filter form */
                filterForm('roundTrip');
            }
        }

        function loadDeparture(){
            const typeBooking           = $(document).find('[name=type_booking]').val();
            const idPortShipDeparture   = $(document).find('[name=ship_port_departure_id]').val();
            const idPortShipLocation    = $(document).find('[name=ship_port_location_id]').val();
            const date                  = $(document).find('[name=date]').val();
            const dateRound             = $(document).find('[name=date_round]').val();
            if(date!=''&&idPortShipDeparture!=0&&idPortShipLocation!=0){
                $.ajax({
                    url         : '{{ route("main.shipBooking.loadDeparture") }}',
                    type        : 'post',
                    dataType    : 'json',
                    data        : {
                        '_token'        : '{{ csrf_token() }}',
                        type_booking            : typeBooking,
                        ship_port_departure_id  : idPortShipDeparture,
                        ship_port_location_id   : idPortShipLocation,
                        date                    : date,
                        date_round              : dateRound
                    },
                    success     : function(data){
                        const elemtDp1  = $('#js_loadDeparture_dp1');
                        // console.log(elemtDp1);
                        $('#js_loadDeparture_dp1').html(data.dp1);
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
                }
            });
        }
    </script>
@endpush