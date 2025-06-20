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
        'active'    => 'combo'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">

        <!-- Combo box -->
        <div class="sectionBox backgroundPrimaryGradiend">
            <div class="container">
                <h1 class="titlePage">Combo {{ $item->display_name ?? null }} - Danh sách Combo du lịch {{ $item->display_name ?? null }} chất lượng</h1>
                <p class="sectionBox_desc">Tổng hợp các chương trình <strong>Combo {{ $item->display_name ?? null }} trọn gói</strong> và <strong>Combo {{ $item->display_name ?? null }} trong ngày</strong> đa dạng, chất lượng hàng đầu được cung cấp và đảm bảo bởi {{ config('main.name') }} cùng hệ thống đối tác du lịch trên toàn quốc.</p>
                {{-- @include('main.tourLocation.filterBox') --}}
                @php
                    $dataCombos             = new \Illuminate\Support\Collection();
                    $i                      = 0;
                    foreach($item->combos as $combo){
                        $dataCombos[$i]               = $combo->infoCombo;
                        $dataCombos[$i]->seo          = $combo->infoCombo->seo;
                        ++$i;
                    }
                @endphp
                @if(!empty($dataCombos)&&$dataCombos->isNotEmpty())
                    @include('main.comboLocation.comboItem', ['list' => $dataCombos])
                @else 
                    <div style="color:#007bff;">Các chương trình <strong>Combo {{ $item->display_name ?? null }}</strong> đang được {{ config('main.name') }} cập nhật và sẽ sớm giới thiệu đến Quý khách trong thời gian tới!</div>
                @endif
            </div>
        </div>

        <!-- Giới thiệu Combo du lịch -->
        <div class="sectionBox" style="padding-top:0;">
            <div class="container">
                <!-- title -->
                <h2 class="sectionBox_title">Combo {{ $item->display_name ?? null }} - Giới thiệu thêm về Combo {{ $item->display_name ?? null }}</h2>
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

        

        {{-- <!-- Hướng dẫn đặt Vé -->
        @include('main.comboLocation.guideBook', ['title' => 'Hướng dẫn đặt Combo '.$item->display_name]) --}}

        <!-- START:: Video -->
        @include('main.tourLocation.videoBox', [
            'item'  => $item,
            'title' => 'Video Combo '.$item->display_name
        ])
        <!-- END:: Video -->

        <!-- Câu hỏi thường gặp -->
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
            <a href="{{ $linkBooking ?? '/' }}" class="callBookTourMobile_button"><h2 style="margin:0;">Đặt combo</h2></a>
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