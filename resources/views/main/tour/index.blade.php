@extends('main.layouts.main')
@section('content')

    @include('main.snippets.breadcrumb')

    @php
        // dd($item);
    @endphp

    <div class="pageContent">
        <div class="container">
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
            <!-- content box -->
            <div class="contentBox">
                {!! $item->content !!}
            </div>
            <!-- tour box -->
            @include('main.tourLocation.tourGrid')
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
                dots: true,
                arrows: true,
                autoplay: true,
                infinite: true,
                autoplaySpeed: 5000,
                lazyLoad: 'ondemand',
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
    </script>
@endpush