@extends('main.layouts.main')
@section('content')

    @include('main.snippets.breadcrumb')

    @php
        // dd($item);
    @endphp

    <div class="pageContent">
        <div class="container">

            <div class="pageContent_head">
                <!-- title -->
                <h1 class="titlePage">{{ $item->name }}</h1>
                <!-- rating -->
                <div class="ratingBox">
                    <div class="ratingBox_star">
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_off"><i class="fas fa-star"></i></span>
                    </div>
                    <div class="ratingBox_text maxLine_1" style="margin-left:2px;font-size:14px;">
                        {{ $item->seo->rating_aggregate_star }} sao / <a href="/sp/dau-rua-mat-3s#product-reviews">{{  $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch</a>
                    </div>
                </div>
            </div>

            <div class="pageContent_body">
                <div class="pageContent_body_content">

                    <!-- content box -->
                    @include('main.tour.gallery', compact('item'))

                    <!-- content box -->
                    @include('main.tour.content', compact('item'))

                    <!-- faq -->
                    @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])

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
            const scrollHeight          = $('body').prop('scrollHeight');
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
    </script>
@endpush