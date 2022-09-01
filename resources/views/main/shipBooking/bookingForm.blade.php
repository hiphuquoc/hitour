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

    <form id="formBooking" action="{{ route('main.booking.handleShipBookingForm') }}" method="POST">
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
                                                <label class="form-label inputRequired" for="ship_departure_id">Điểm đi</label>
                                                <select class="select2 form-select select2-hidden-accessible" name="ship_departure_id">
                                                    <option value="0">- Lựa chọn -</option>
                                                    @if(!empty($locations))
                                                        @foreach($locations as $location)
                                                            @php
                                                                $selected   = null;
                                                                // if(!empty($item->ship_port_departure_id)&&$item->ship_port_departure_id==$shipDeparture->id) $selected = 'selected';
                                                            @endphp
                                                            <option value="{{ $location->id }}"{{ $selected }}>
                                                                {{ $location->district->district_name ?? '-' }}, {{ $location->province->province_name ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="flexBox_item inputWithIcon location">
                                                <label class="form-label inputRequired" for="ship_location_id">Điểm đến</label>
                                                <select class="select2 form-select select2-hidden-accessible" name="ship_location_id">
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
                                            <div class="flexBox_item inputWithIcon date">
                                                <label class="form-label inputRequired" for="date_round">Ngày về</label>
                                                <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_round" placeholder="YYYY-MM-DD" readonly="readonly">
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
                                </div>

                            </div>
                        </div>

                        @for($k=0;$k<2;++$k)
                        <div class="bookingForm_item">
                            <div class="bookingForm_item_head">
                                Chuyến Rạch Giá - Phú Quốc
                            </div>
                            <div class="bookingForm_item_body">
                                <div class="formBox">
                                    <div class="formBox_full">
                                        <!-- One Row -->
                                        <div class="formBox_full_item">
                                            <div class="chooseDepartureShipBox">
                                                <div class="chooseDepartureShipBox_head">
                                                    <div class="chooseDepartureShipBox_head_month">
                                                        <span>Tháng 9/2022</span>
                                                    </div>
                                                    <div class="chooseDepartureShipBox_head_listDay">
                                                        @for($i=0;$i<7;++$i)
                                                            @php
                                                                $active = $i==3 ? ' active' : '';
                                                            @endphp
                                                            <div class="chooseDepartureShipBox_head_listDay_item{{ $active }}">
                                                                <div class="chooseDepartureShipBox_head_listDay_item_day">Thứ Ba, 20/09</div>
                                                                <div class="chooseDepartureShipBox_head_listDay_item_price">340,000<sup>đ</sup></div>
                                                            </div>
                                                        @endfor
                                                    </div>
                                                    <div class="chooseDepartureShipBox_head_detailDay">
                                                        Thứ 3, ngày 20/09/2022
                                                    </div>
                                                </div>
                                                <div id="js_checkedInput_idSearch" class="chooseDepartureShipBox_body">
                                                    @for($i=0;$i<4;++$i)
                                                    <div class="chooseDepartureShipBox_body_item">
                                                        <div class="chooseDepartureShipBox_body_item_departure">
                                                            <div class="chooseDepartureShipBox_body_item_departure_time">
                                                                <div class="highLight">17:30</div>
                                                                <div>RG</div>
                                                            </div>
                                                            <div class="chooseDepartureShipBox_body_item_departure_brand">
                                                                <div class="highLight">Phú Quốc Express</div>
                                                                <div><i class="fa-solid fa-arrow-right-long"></i> 2h 30m</div>
                                                            </div>
                                                            <div class="chooseDepartureShipBox_body_item_departure_time">
                                                                <div class="highLight">19:30</div>
                                                                <div>PQ</div>
                                                            </div>
                                                        </div>
                                                        <div class="chooseDepartureShipBox_body_item_typeEco" onClick="checkedInput('js_checkedInput_idSearch', this);">
                                                            <input type="radio" name="test" />
                                                            <div>
                                                                <div><span class="highLight">340,000<sup>đ</sup></span> /Người lớn</div>
                                                                <div><span class="highLight">270,000<sup>đ</sup></span> /Trẻ em</div>
                                                                <div><span class="highLight">270,000<sup>đ</sup></span> /Cao tuổi</div>
                                                            </div>
                                                        </div>
                                                        <div class="chooseDepartureShipBox_body_item_typeVip" onClick="checkedInput('js_checkedInput_idSearch', this);">
                                                            <input type="radio" name="test" />
                                                            <div><span class="highLight">540,000<sup>đ</sup></span> /Vip</div>
                                                        </div>
                                                    </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
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

        function checkedInput(idSearch, elemt){
            $('#'+idSearch).find('input[type=radio]').each(function(){
                $(this).prop('checked', false);
                $(this).parent().removeClass('active');
            });
            $(elemt).find('input[type=radio]').prop('checked', true);
            $(elemt).addClass('active');
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
        }
    </script>
@endpush