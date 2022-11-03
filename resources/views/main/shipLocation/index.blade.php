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
    if(!empty($item->ships)&&$item->ships->isNotEmpty()){
        foreach($item->ships as $ship){
            if(!empty($ship->prices)&&$ship->prices->isNotEmpty()){
                foreach($ship->prices as $price){
                    if(!empty($price->price_adult)) $arrayPrice[] = $price->price_adult;
                    if(!empty($price->price_child)) $arrayPrice[] = $price->price_child;
                    if(!empty($price->price_old)) $arrayPrice[] = $price->price_old;
                    if(!empty($price->price_vip)) $arrayPrice[] = $price->price_vip;
                }
            }
        }
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
    if(!empty($item->ships)&&$item->ships->isNotEmpty()) $dataList = $item->ships;
@endphp
<!-- STRAT:: Article Schema -->
@include('main.schema.itemlist', ['data' => $dataList])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @php
        $active = 'ship';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active'))

    @include('main.snippets.breadcrumb')

    <div class="pageContent">

            <div class="sectionBox backgroundPrimaryGradiend">
                <div class="container">
                    <!-- title -->
                    <h1 class="titlePage">{{ $item->name }}{{ !empty($item->district->district_name) ? ' - Vé tàu '.$item->district->district_name : null}}</h1>
                    <!-- rating -->
                    @include('main.template.rating', compact('item'))
                    <!-- content -->
                    @if(!empty($item->description))
                        <div class="contentBox">
                            <p>{!! $item->description !!}</p>
                        </div>
                    @endif
                    <!-- ship box -->
                    @include('main.shipLocation.shipGridMerge', ['list' => $item->ships])
                </div>
            </div>

            <!-- START:: Video -->
            @if(!empty($item->seo->video))
                <div class="sectionBox withBorder">
                    <div class="container">
                        <h2 class="sectionBox_title" style="text-align:center;">Video Tàu cao tốc {{ $item->display_name ?? null }}</h2>
                        <div class="videoYoutubeBox">
                            <div class="videoYoutubeBox_video">
                                {!! $item->seo->video !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- END:: Video -->
            
            <div class="sectionBox noBackground">
                <div class="container">
                    <div class="pageContent_body">
                        <div class="pageContent_body_content">
                            <div id="js_autoLoadTocContentWithIcon_element" class="contentShip">
                                <!-- Lịch tàu và Hãng tàu -->
                                @include('main.shipLocation.headContent', ['keyWord' => $item->name])
                                <!-- Nội dung tùy biến -->
                                {!! $content ?? null !!}
                                <!-- Câu hỏi thường gặp -->
                                @if(!empty($item->questions)&&$item->questions->isNotEmpty())
                                    <div id="cau-hoi-thuong-gap" class="contentShip_item">
                                        <div class="contentShip_item_title">
                                            <i class="fa-solid fa-circle-question"></i>
                                            <h2>Câu hỏi thường gặp về {{ $item->name ?? null }}</h2>
                                        </div>
                                        <div class="contentShip_item_text">
                                            @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="pageContent_body_sidebar">
                            @include('main.shipLocation.sidebar')
                        </div>
                    </div>
                </div>
            </div>
            
    </div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function () {
            
            autoLoadTocContentWithIcon('js_autoLoadTocContentWithIcon_element');
            
        });
    </script>
@endpush