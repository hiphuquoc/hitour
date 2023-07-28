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
        'active'    => 'Hotel'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <!-- Giới thiệu Hotel du lịch -->
        <div class="sectionBox">
            <div class="container">
                <!-- title -->
                <h1 class="titlePage">Hotel {{ $item->display_name ?? null }} - Giới thiệu Hotel {{ $item->display_name ?? null }}</h1>
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
                <h2 class="sectionBox_title">Hotel {{ $item->display_name ?? null }} - Danh sách Hotel du lịch {{ $item->display_name ?? null }}</h2>
                <p class="sectionBox_desc">Tổng hợp các chương trình <strong>Hotel {{ $item->display_name ?? null }} trọn gói</strong> và <strong>Hotel {{ $item->display_name ?? null }} trong ngày</strong> đa dạng, chất lượng hàng đầu được cung cấp và đảm bảo bởi {{ config('company.sortname') }} cùng hệ thống đối tác du lịch trên toàn quốc.</p>
                {{-- @include('main.tourLocation.filterBox') --}}
                @if(!empty($item->hotels)&&$item->hotels->isNotEmpty())
                    @include('main.hotelLocation.hotelList', ['list' => $item->hotels])
                @else 
                    <div style="color:#069a8e;">Các chương trình <strong>Hotel {{ $item->display_name ?? null }}</strong> đang được {{ config('company.sortname') }} cập nhật và sẽ sớm giới thiệu đến Quý khách trong thời gian tới!</div>
                @endif
            </div>
        </div>

        <!-- Hướng dẫn đặt Vé -->
        @include('main.hotelLocation.guideBook', ['title' => 'Hướng dẫn đặt Hotel '.$item->display_name])

        <!-- START:: Video -->
        @include('main.tourLocation.videoBox', [
            'item'  => $item,
            'title' => 'Video Hotel '.$item->display_name
        ])
        <!-- END:: Video -->

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
    </script>
@endpush