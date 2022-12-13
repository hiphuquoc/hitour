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

    @include('main.form.sortBooking', [
        'item'      => $item,
        'active'    => 'ship'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <div class="pageContent_body">
                    <div id="js_buildTocContentSidebar_element" class="pageContent_body_content">
                        <!-- title -->
                        <h1 class="titlePage">Tàu cao tốc {{ $item->name ?? null }}</h1>
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
                        <!-- content -->
                        <div class="contentShip">
                            <!-- Lịch tàu và Hãng tàu -->
                            @include('main.ship.headContent', ['keyWord' => $item->name])
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
                        @php
                            $linkFull = route('main.shipBooking.form', [
                                'ship_port_departure_id'    => $item->ship_port_departure_id,
                                'ship_port_location_id'     => $item->ship_port_location_id
                            ]);
                        @endphp
                        @include('main.ship.sidebar', compact('item', 'linkFull'))
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
    <!-- button book vé mobile -->
    <div class="show-990">
        <div class="callBookTourMobile">
            <a href="tel:0868684868" class="callBookTourMobile_phone maxLine_1">08 6868 4868</a>
            <a href="{{ $linkFull ?? '/' }}" class="callBookTourMobile_button"><h2 style="margin:0;">Đặt vé</h2></a>
        </div>
    </div>
@endpush
@push('scripts-custom')
    <script type="text/javascript">
        buildTocContentMain('js_buildTocContentSidebar_element');
    </script>
@endpush