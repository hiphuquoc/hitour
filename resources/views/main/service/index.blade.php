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

<!-- STRAT:: Article Schema -->
@php
    $highPrice  = $item->price_show ?? 1000000;
    $lowPrice   = $highPrice/2;
@endphp
@include('main.schema.product', ['data' => $dataSchema, 'files' => $item->files, 'lowPrice' => $lowPrice, 'highPrice' => $highPrice])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- STRAT:: FAQ Schema -->
@include('main.schema.faq', ['data' => $item->questions])
<!-- END:: FAQ Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @include('main.form.sortBooking', [
        'item'      => $item,
        'active'    => 'service'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <div class="pageContent_body">
                    <div id="js_buildTocContentSidebar_element" class="pageContent_body_content">
                        <!-- title -->
                        <h1 class="titlePage">{{ $item->name ?? null }}</h1>
                        <!-- rating -->
                        @include('main.template.rating', compact('item'))
                        <!-- video -->
                        @if(!empty($item->seo->video))
                            <div class="videoYoutubeBox">
                                <div class="videoYoutubeBox_video">
                                    {!! $item->seo->video !!}
                                </div>
                            </div>
                        @endif
                        <!-- tocContent main -->
                        <div id="tocContentMain" style="margin-top:1rem;"></div>
                        <!-- bảng giá -->
                        @include('main.service.prices', compact('item'))
                        <!-- content -->
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
                        @php
                            $linkBooking = route('main.serviceBooking.form', [
                                'service_location_id'   => $item->serviceLocation->id ?? 0,
                                'service_info_id'       => $item->id ?? 0
                            ]);
                        @endphp 
                        @include('main.service.sidebar', compact('linkBooking'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
    <!-- button book tour mobile -->
    @if(!empty($item->price_show))
        <div class="show-990">
            <div class="callBookTourMobile">
                <div class="callBookTourMobile_price">
                    {{ number_format($item->price_show).config('main.unit_currency') }}
                </div>
                <a href="{{ $linkBooking }}" class="callBookTourMobile_button"><h2>Đặt vé</h2></a>
            </div>
        </div>
    @endif
@endpush
@push('scripts-custom')
    <script type="text/javascript">
        buildTocContentMain('js_buildTocContentSidebar_element');
    </script>
@endpush