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

<!-- STRAT:: Article Schema -->
@include('main.schema.itemlist', ['data' => $list])
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
                        <!-- Blog List -->
                        @include('main.category.blogList', compact('blogs'))
                        <!-- START:: Pagination -->
                        {{-- <div class="pull-right pagination">
                            <ul class="pagination">
                            <li class="disabled">
                                <span><i class="fa-solid fa-arrow-left-long"></i></span>
                            </li>
                            <li class="active"><span>1</span></li>
                            <li><a href="https://chuyentauvanhoc.edu.vn/nghi-luan-van-hoc?page=2">2</a></li>
                            <li>
                                <a href="https://chuyentauvanhoc.edu.vn/nghi-luan-van-hoc?page=2">
                                <span><i class="fa-solid fa-arrow-right-long"></i></span>
                                </a>
                            </li>
                            </ul>
                        </div> --}}
                        <!-- END:: Pagination -->
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.category.sidebar', compact('listCategoryLv1'))
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