@extends('main.layouts.main')
@push('head-custom')
<!-- ===== START:: SCHEMA ===== -->
@php
    $dataSchema = $item->seo ?? null;
@endphp
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
    $highPrice  = $item->price_show ?? 5000000;
    $lowPrice   = round($highPrice/2, 0);
@endphp
@include('main.schema.product', ['data' => $dataSchema, 'files' => $item->files, 'lowPrice' => $lowPrice, 'highPrice' => $highPrice])
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

                <div class="pageContent_head">
                    <!-- title -->
                    <h1 class="titlePage">{{ $item->name }}</h1>
                    <!-- rating -->
                    @if(!empty($item->seo->rating_aggregate_star)&&!empty($item->seo->rating_aggregate_count))
                        <div class="ratingBox">
                            <div class="ratingBox_star">
                                <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                                <span class="ratingBox_star_off"><i class="fas fa-star"></i></span>
                            </div>
                            <div class="ratingBox_text maxLine_1">
                                {{ $item->seo->rating_aggregate_star }} sao / {{  $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch
                            </div>
                        </div>
                    @endif
                </div>
    
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
    
                        <!-- content box -->
                        @include('main.tour.gallery', compact('item'))
    
                        <!-- content box -->
                        @include('main.tour.content', compact('item'))
    
                    </div>
                    <div class="pageContent_body_sidebar">
    
                        @include('main.tour.detailTour', compact('item'))
    
                        <div class="js_scrollFixed">
                            @include('main.tour.callBookTour', compact('item'))
    
                            @include('main.tour.tocContentTour', compact('item'))
                        </div>
                    </div>
                </div>
    
            </div>
        </div>
    </div>

    
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function () {
            setupSlick();
            $(window).resize(function(){
                setupSlick();
            })

            $('.sliderHome').slick({
                infinite: true,
                autoplaySpeed: 5000,
                lazyLoad: 'ondemand',
                dots: true,
                arrows: true,
                autoplay: true,
                responsive: [
                    {
                        breakpoint: 567,
                        settings: {
                            arrows: false,
                        }
                    }
                ]
            });

            function setupSlick(){
                setTimeout(function(){
                    $('.sliderHome .slick-prev').html('<i class="fa-solid fa-arrow-left-long"></i>');
                    $('.sliderHome .slick-next').html('<i class="fa-solid fa-arrow-right-long"></i>');
                    $('.sliderHome .slick-dots button').html('');
                }, 0);
            }
        });

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
                    'margin-top'    : 0
                });
            }else {
                elemt.removeClass('scrollFixedSidebar').css({
                    'width'         : 'unset',
                    'margin-top'    : 0
                });
            }
        });
    </script>
@endpush