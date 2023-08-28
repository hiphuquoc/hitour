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
        'active'    => 'service'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox backgroundPrimaryGradiend">
            <div class="container">
                <!-- title -->
                <h1 class="titlePage">{{ $item->name }}{{ !empty($item->district->district_name) ? ' - Đặt vé vui chơi '.$item->district->district_name : null}}</h1>
                <!-- rating -->
                @include('main.template.rating', compact('item'))
                <!-- content -->
                @if(!empty($item->description))
                    <div class="contentBox">
                        <p>{!! $item->description !!}</p>
                    </div>
                @endif
                <!-- ship box -->
                @include('main.serviceLocation.serviceItem', ['list' => $item->services])
            </div>
        </div>

        <!-- Hướng dẫn đặt Vé -->
        @include('main.serviceLocation.guideBook', ['title' => 'Hướng dẫn đặt Vé vui chơi '.$item->display_name])

        <!-- START:: Video -->
        @include('main.tourLocation.videoBox', [
            'item'  => $item,
            'title' => 'Video Vé vui chơi '.$item->display_name
        ])
        <!-- END:: Video -->
        
        {{-- <div class="sectionBox noBackground">
            <div class="container">
                <div class="pageContent_body">
                    <div id="js_buildTocContentSidebar_element" class="pageContent_body_content">
                        <!-- tocContent main -->
                        <div id="tocContentMain"></div>
                        <!-- Nội dung tùy biến -->
                        {!! $content ?? null !!}
                        <!-- Câu hỏi thường gặp -->
                        @if(!empty($item->questions)&&$item->questions->isNotEmpty())
                            <div id="cau-hoi-thuong-gap" class="contentTour_item">
                                <div class="contentTour_item_title">
                                    <i class="fa-solid fa-circle-question"></i>
                                    <h2>Câu hỏi thường gặp về {{ $item->name ?? null }}</h2>
                                </div>
                                <div class="contentTour_item_text">
                                    @include('main.snippets.faq', [
                                        'list' => $item->questions, 
                                        'title' => $item->name,
                                        'hiddenTitle'   => true
                                    ])
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.serviceLocation.sidebar')
                    </div>
                </div>
            </div>
        </div> --}}

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
            <a href="tel:0868684868" class="callBookTourMobile_phone maxLine_1">{{ config('company.hotline') }}</a>
            <a href="{{ $linkBooking ?? '/' }}" class="callBookTourMobile_button"><h2 style="margin:0;">Đặt vé</h2></a>
        </div>
    </div>
@endpush
@push('scripts-custom')
    <script type="text/javascript">
        buildTocContentMain('js_buildTocContentSidebar_element');
    </script>
@endpush