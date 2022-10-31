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

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

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
                        <div class="contentShip">
                            <!-- Nội dung tùy biến -->
                            {!! $content ?? null !!}
                        </div>
                        <!-- related box -->
                        @include('main.blog.related', compact('blogRelates'))
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.blog.sidebar')
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