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
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- STRAT:: FAQ Schema -->
@include('main.schema.faq', ['data' => $item->questions])
<!-- END:: FAQ Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    {{-- @php
        $active = 'ship';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active')) --}}

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
                        <!-- title -->
                        <h1 class="titlePage">{{ $item->name }}{{ !empty($item->district->district_name) ? ' - Vé tàu '.$item->district->district_name : null}}</h1>
                        <!-- rating -->
                        @include('main.template.rating', compact('item'))
                        <!-- content -->
                        <div id="js_buildTocContentSidebar_element" class="contentShip">
                            <!-- tocContent main -->
                            <div id="tocContentMain" style="margin-top:1rem;"></div>
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
                        @include('main.shipPartner.sidebar')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        buildTocContentMain('js_buildTocContentSidebar_element');
    </script>
@endpush