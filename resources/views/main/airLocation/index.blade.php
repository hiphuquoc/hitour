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

@php
    $dataList       = null;
    if(!empty($item->airs)&&$item->airs->isNotEmpty()) $dataList = $item->airs;
@endphp
<!-- STRAT:: Article Schema -->
@include('main.schema.itemlist', ['data' => $dataList])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    {{-- @php
        $active = 'ship';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active')) --}}

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
            <div class="sectionBox backgroundPrimaryGradiend">
                <div class="container">
                    <!-- title -->
                    <h1 class="titlePage">{{ $item->name }}{{ !empty($item->display_name) ? ' - Đặt vé máy bay '.$item->display_name : null}}</h1>
                    <!-- rating -->
                    @include('main.template.rating', compact('item'))
                    <!-- description -->
                    @if(!empty($item->description))
                        <div class="contentBox">
                            <p>{!! $item->description !!}</p>
                        </div>
                    @endif
                    <!-- air box -->
                    @include('main.airLocation.airGrid', ['list' => $item->airs])
                </div>
            </div>

            <!-- START:: Video -->
            @if(!empty($item->seo->video))
                <div class="sectionBox withBorder">
                    <div class="container">
                        <h2 class="sectionBox_title" style="text-align:center;">Video Vé máy bay {{ $item->display_name ?? null }}</h2>
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
                            <div id="js_autoLoadTocContentWithIcon_element">
                                <!-- Lịch tàu và Hãng tàu -->
                                {{-- @include('main.airLocation.headContent', ['keyWord' => $item->name]) --}}
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
                                            @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="pageContent_body_sidebar">
                            @include('main.airLocation.sidebar')
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