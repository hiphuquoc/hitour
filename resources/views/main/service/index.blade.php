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
    $lowPrice   = $highPrice - 200000;
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

    @php
        $active = 'tour';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active'))

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <div class="pageContent_body">
                    <div id="js_autoLoadTocContentWithIcon_element" class="pageContent_body_content">
                        <!-- title -->
                        <h1 class="titlePage">{{ $item->name ?? null }}</h1>
                        <!-- rating -->
                        @include('main.template.rating', compact('item'))
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
                                    @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.service.sidebar')
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