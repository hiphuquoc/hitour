@extends('main.layouts.main')
@section('content')

    @php
        $active = 'tour';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active'))

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="container">
            <!-- title -->
            <h1 class="titlePage">Du lịch Côn Đảo - Tour du lịch Côn Đảo</h1>
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
                    <div class="ratingBox_text maxLine_1" style="margin-left:2px;font-size:14px;">
                            {{ $item->seo->rating_aggregate_star }} sao / <a href="/sp/dau-rua-mat-3s#product-reviews">{{ $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch</a>
                    </div>
                </div>
            @endif

            <!-- content box -->
            @if(!empty($item->description))
                <div class="contentBox">
                    <p>{{ $item->description }}</p>
                </div>
            @endif
            <!-- tour box -->
            @include('main.tourLocation.tourGrid')
            <!-- content box -->
            <div class="pageContent_head">
                <h2>Cẩm nang du lịch Phú Quốc từ A-Z</h2>
            </div>
            <div class="pageContent_body">
                <div class="pageContent_body_content">
                    {{-- @include('main.tourLocation.content') --}}
                    {!! $content ?? null !!}
                </div>
                <div class="pageContent_body_sidebar">
                    @include('main.tourLocation.sidebar')
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