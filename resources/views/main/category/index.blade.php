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
                        @if(!empty($item->seo->rating_aggregate_star)&&!empty($item->seo->rating_aggregate_count))
                            <div class="ratingBox">
                                <div class="ratingBox_star">
                                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                </div>
                                <div class="ratingBox_text maxLine_1">
                                    {{ $item->seo->rating_aggregate_star }} sao / {{ $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch
                                </div>
                            </div>
                        @endif
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
                        @include('main.category.sidebar')
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

            /* fixed sidebar khi scroll */
            const elemt                 = $('.js_scrollFixed');
            const widthElemt            = elemt.parent().width();
            const positionTopElemt      = elemt.offset().top;
            const heightFooter          = 500;
            $(window).scroll(function(){
                const positionScrollbar = $(window).scrollTop();
                const scrollHeight      = $('body').prop('scrollHeight');
                const heightLimit       = parseInt(scrollHeight - heightFooter - elemt.outerHeight());
                if(positionScrollbar>positionTopElemt&&positionScrollbar<heightLimit){
                    elemt.addClass('scrollFixedSidebar').css({
                        'width'         : widthElemt,
                        'margin-top'    : '1.5rem'
                    });
                }else {
                    elemt.removeClass('scrollFixedSidebar').css({
                        'width'         : 'unset',
                        'margin-top'    : 0
                    });
                }
            });

        });
    </script>
@endpush