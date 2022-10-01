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
            @if(!empty($item->guides->isNotEmpty()))
                <div class="contentBox">
                    <h2>Cẩm nang du lịch {{ $item->display_name ?? null }}</h2>
                    <p>Nếu các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> của Hitour không đáp ứng được nhu cầu của bạn, hoặc là người ưu thích du lịch tự túc,... Hitour cung cấp thêm cho bạn <strong>Cẩm nang du lịch từ A-Z</strong> để bạn có thể tự do tham khảo thông tin chi tiết về <strong>du lịch {{ $item->display_name ?? null }}</strong> để có thể lên kế hoạch, sắp xếp cho chuyến đi du lịch của mình được chu đáo nhất</p>
                    <div class="guideList">
                        @foreach($item->guides as $guide)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="{{ $guide->infoGuide->seo->slug_full }}">{{ $guide->infoGuide->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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