@extends('main.layouts.main')
@section('content')

    @include('main.snippets.breadcrumb')

    @php
        // dd($item);
    @endphp

    <div class="pageContent">
        <div class="container">
            <div class="pageBox">
                
                <div class="pageBox_head">
                    <!-- title -->
                    <h1 class="titlePage">{{ $item->name }}</h1>
                    <!-- rating -->
                    <div class="ratingBox" style="margin-bottom:1rem;">
                        <div class="ratingBox_star">
                            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        </div>
                        <div class="ratingBox_text maxLine_1" style="margin-left:2px;font-size:14px;">
                            {{ $item->seo->rating_aggregate_star }} sao / <a href="/sp/dau-rua-mat-3s#product-reviews">{{  $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch</a>
                        </div>
                    </div>
                </div>
                <div class="pageBox_body">
                    <div class="pageBox_body_content">

                        <!-- content box -->
                        @include('main.tour.gallery', compact('item'))

                        <!-- content box -->
                        @include('main.tour.content', compact('item'))

                    </div>
                    <div class="pageBox_body_sidebar">

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
        $(window).scroll(function(){
            const positionScrollbar     = $(window).scrollTop();
            if(positionScrollbar>positionTopElemt){
                elemt.addClass('scrollFixedSidebar').css('width', widthElemt);
            }else {
                elemt.removeClass('scrollFixedSidebar').css('width', 'unset');
            }
        });
    </script>
@endpush