@extends('main.layouts.main')
@push('head-custom')
<!-- ===== START:: SCHEMA ===== -->
@php
    $dataSchema = $item->seo ?? null;
@endphp

<!-- STRAT:: Title - Description - Social -->
@include('main.schema.social', ['data' => $dataSchema])
<!-- END:: Title - Description - Social -->

<!-- STRAT:: Organization Schema -->
@include('main.schema.organization')
<!-- END:: Organization Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.article', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.creativeworkseries', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Product Schema -->
@php
    $arrayPrice = [];
    if(!empty($item->services)&&$item->services->isNotEmpty()){
        foreach($item->services as $service) if(!empty($service->price_show)) $arrayPrice[] = $service->price_show;
    }
    $highPrice  = !empty($arrayPrice) ? max($arrayPrice) : 5000000;
    $lowPrice   = !empty($arrayPrice) ? min($arrayPrice) : 3000000;
@endphp
@include('main.schema.product', ['data' => $dataSchema, 'files' => $item->files, 'lowPrice' => $lowPrice, 'highPrice' => $highPrice])
<!-- END:: Product Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- STRAT:: FAQ Schema -->
@include('main.schema.faq', ['data' => $item->questions])
<!-- END:: FAQ Schema -->

@php
    $dataList       = null;
    if(!empty($item->services)&&$item->services->isNotEmpty()) $dataList = $item->services;
@endphp
<!-- STRAT:: Article Schema -->
@include('main.schema.itemlist', ['data' => $dataList])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @include('main.form.sortBooking', [
        'item'      => $item,
        'active'    => 'hotel'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">

        <!-- Giới thiệu Hotel du lịch -->
        <div class="sectionBox">
            <div class="container">
                <!-- title -->
                <h1 class="titlePage">Khách sạn {{ $item->display_name ?? null }} - Giới thiệu chung về khách sạn {{ $item->display_name ?? null }}</h1>
                <!-- rating -->
                @include('main.template.rating', compact('item'))
                <!-- content -->
                @if(!empty($content))
                    <div id="js_showHideFullContent_content" class="contentBox maxLine_4">
                        {!! $content !!}
                    </div>
                    <div class="viewMore">
                        <div onClick="showHideFullContent(this, 'maxLine_4');">
                            <i class="fa-solid fa-arrow-down-long"></i>Đọc thêm
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Hotel box -->
        <div class="sectionBox backgroundPrimaryGradiend">
            <div class="container">
                <h2 class="sectionBox_title">Khách sạn {{ $item->display_name ?? null }} - Hiện đang có <span class="highLight">{{ $item->hotels->count() }}</span> chỗ nghỉ tại {{ $item->display_name ?? null }}</h2>
                <p class="sectionBox_desc">Tổng hợp các <strong>Khách sạn {{ $item->display_name ?? null }}</strong>, <strong>Resort {{ $item->display_name ?? null }}</strong>, <strong>Homestay {{ $item->display_name ?? null }}</strong> và <strong>Nhà nghỉ {{ $item->display_name ?? null }}</strong> đang được ưa chuộng và là lựa chọn hàng đầu của khách du lịch.</p>
                @include('main.hotelLocation.filterBox')
                @if(!empty($item->hotels)&&$item->hotels->isNotEmpty())
                    @include('main.hotelLocation.hotelGrid', ['list' => $item->hotels])
                    @if($item->hotels->count()>10)
                        <div class="viewMore">
                            <div id="js_loadMoreHotels_button" style="margin-top:0.5rem;" onClick="loadMoreHotels(10);">
                                <i class="fa-solid fa-arrow-down-long"></i>Xem thêm <span>{{ $item->hotels->count() - 10 }}</span> chỗ nghỉ
                            </div>
                        </div>
                    @endif
                @else 
                    <div style="color:#069a8e;">Các <strong>Hotel {{ $item->display_name ?? null }}</strong> đang được {{ config('company.sortname') }} cập nhật và sẽ sớm giới thiệu đến Quý khách trong thời gian tới!</div>
                @endif
            </div>
        </div>

        {{-- <!-- Hướng dẫn đặt Vé -->
        @include('main.hotelLocation.guideBook', ['title' => 'Hướng dẫn đặt Khách sạn '.$item->display_name]) --}}

        <!-- START:: Video -->
        @include('main.tourLocation.videoBox', [
            'item'  => $item,
            'title' => 'Video Hotel '.$item->display_name
        ])
        <!-- END:: Video -->

        <!-- faq -->
        @if(!empty($item->questions)&&$item->questions->isNotEmpty())
            <div class="sectionBox withBorder">
                <div class="container" style="border-bottom:none !important;">
                    @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                </div>
            </div>
        @endif
    </div>
@endsection
@push('bottom')
    <!-- button book tour mobile -->
    @php
        $linkBooking = route('main.serviceBooking.form', [
            'service_location_id'   => $item->id ?? 0
        ]);
    @endphp 
    <div class="show-990">
        <div class="callBookTourMobile">
            <a href="tel:{{ \App\Helpers\Charactor::removeSpecialCharacters(config('main.company.hotline')) }}" class="callBookTourMobile_phone maxLine_1">{{ config('main.company.hotline') }}</a>
            <a href="{{ $linkBooking ?? '/' }}" class="callBookTourMobile_button"><h2 style="margin:0;">Đặt Hotel</h2></a>
        </div>
    </div>
@endpush
@push('scripts-custom')
    <script type="text/javascript">
        $('window').ready(function(){
            @foreach ($item->hotels as $hotel)
                loadHotelInfo('{{ $hotel->id }}');
            @endforeach
        })

        buildTocContentMain('js_buildTocContentSidebar_element');

        function showHideFullContent(elementButton, classCheck){
            const contentBox = $('#js_showHideFullContent_content');
            if(contentBox.hasClass(classCheck)){
                contentBox.removeClass(classCheck);
                $(elementButton).html('<i class="fa-solid fa-arrow-up-long"></i>Thu gọn');
            }else {
                contentBox.addClass(classCheck);
                $(elementButton).html('<i class="fa-solid fa-arrow-down-long"></i>Đọc thêm');
            }
        }

        function loadMoreHotels(showEveryTime){
            var count = 1;
            /* hiển thị thêm */
            $('#js_filter_parent').children().each(function(){
                const display = $(this).css('display');
                if(display=='none'){
                    $(this).css('display', 'flex');
                    if(count==showEveryTime) return false;
                    ++count;
                }
            });
            /* đếm lại phần tử còn lại chưa hiển thị */
            var hidden = 0;
            $('#js_filter_parent').children().each(function(){
                const display = $(this).css('display');
                if(display=='none'){
                    ++hidden;
                }
            });
            /* hiển thị số lượng vào button */
            if(hidden==0){
                $('#js_loadMoreHotels_button').parent().hide();
            }else {
                $('#js_loadMoreHotels_button span').html(hidden);
            }
        }

        function loadHotelInfo(idHotelInfo){
            $.ajax({
                url         : "{{ route('main.hotel.loadHotelInfo') }}",
                type        : "GET",
                dataType    : "json",
                data        : { hotel_info_id : idHotelInfo }
            }).done(function(data){
                /* ghi nội dung room vào bảng */
                if(data!='') {
                    $('#js_loadHotelInfo_'+idHotelInfo).html(data);
                }else {
                    $('#js_loadHotelInfo_'+idHotelInfo).hidden();
                }
            });
        }

    </script>
@endpush