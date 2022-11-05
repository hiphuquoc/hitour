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

<!-- STRAT:: Product Schema -->
@php
    $arrayPrice = [];
    foreach($item->tours as $tour) if(!empty($tour->infoTourForeign->price_show)) $arrayPrice[] = $tour->infoTourForeign->price_show;
    $highPrice  = !empty($arrayPrice) ? max($arrayPrice) : 5000000;
    $lowPrice   = !empty($arrayPrice) ? min($arrayPrice) : 3000000;
@endphp
@include('main.schema.product', ['data' => $dataSchema, 'files' => $item->files, 'lowPrice' => $lowPrice, 'highPrice' => $highPrice])
<!-- END:: Product Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- STRAT:: FAQ Schema -->
@include('main.schema.faq', ['data' => $item->questions])
<!-- END:: FAQ Schema -->

@php
    $dataList       = new \Illuminate\Support\Collection();
    if(!empty($item->tours)&&$item->tours->isNotEmpty()){
        foreach($item->tours as $tour){
            if(!empty($tour->infoTourForeign)) $dataList[] = $tour->infoTourForeign;
        }
    }
@endphp
<!-- STRAT:: Article Schema -->
@include('main.schema.itemlist', ['data' => $dataList])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @php
        $active = 'tour';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active'))

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
            <!-- Giới thiệu Tour du lịch -->
            <div class="sectionBox">
                <div class="container">
                    <!-- title -->
                    <h1 class="titlePage">Tour {{ $item->display_name }} - Giới thiệu Tour du lịch {{ $item->display_name }}</h1>
                    <!-- rating -->
                    @include('main.template.rating', compact('item'))
                    <!-- content -->
                    @if(!empty($content))
                        <div id="js_showHideFullContent_content" class="contentBox maxLine_4">
                            {!! $content !!}
                        </div>
                        <div class="viewMore" style="margin-top:1.5rem;">
                            <div onClick="showHideFullContent(this, 'maxLine_4');">
                                <i class="fa-solid fa-arrow-down-long"></i>Đọc thêm
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tour box -->
            <div class="sectionBox backgroundPrimaryGradiend">
                <div class="container">
                    <h2 class="sectionBox_title">Tour {{ $item->display_name ?? null }} - Danh sách Tour du lịch {{ $item->display_name ?? null }} chất lượng</h2>
                    <p>Tổng hợp các chương trình <strong>Tour {{ $item->display_name ?? null }}</strong> đa dạng, chất lượng hàng đầu được cung cấp và đảm bảo bởi Hitour cùng hệ thống đối tác.</p>
                    @include('main.tourLocation.filterBox')
                    @php
                        $dataTours              = new \Illuminate\Support\Collection();
                        foreach($item->tours as $tour) if(!empty($tour->infoTourForeign)) $dataTours[] = $tour->infoTourForeign;
                    @endphp
                    @if(!empty($item->tours)&&$item->tours->isNotEmpty())
                        @include('main.tourLocation.tourGrid', ['list' => $dataTours])
                    @else 
                        <div style="color:rgb(0,123,255);">Các chương trình <strong>Tour {{ $item->display_name ?? null }}</strong> đang được Hitour cập nhật và sẽ sớm giới thiệu đến Quý khách trong thời gian tới!</div>
                    @endif
                </div>
            </div>

            <!-- START:: Video -->
            @if(!empty($item->seo->video))
                <div class="sectionBox withBorder">
                    <div class="container">
                        <div style="text-align:center;">
                            <h2 class="sectionBox_title" style="text-align:center;">Video Tour du lịch {{ $item->display_name ?? null }}</h2>
                        </div>
                        <div class="videoYoutubeBox">
                            <div class="videoYoutubeBox_video">
                                {!! $item->seo->video !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- END:: Video -->

            <!-- Hướng dẫn đặt Tour -->
            @include('main.tourLocation.guideBookTour', ['title' => 'Quy trình đặt Tour '.$item->display_name.' và Sử dụng dịch vụ'])

            <!-- Vé máy bay -->
            @php
                $dataAirs               = new \Illuminate\Support\Collection();
                foreach($item->airLocations as $airLocation){
                    foreach($airLocation->infoAirLocation->airs as $air){
                        $dataAirs[]     = $air;
                    }
                }
            @endphp
            @if(!empty($dataAirs)&&$dataAirs->isNotEmpty())
                <div class="sectionBox">
                    <div class="container">
                        @if(!empty($item->airLocations[0]->infoAirLocation->seo->slug_full))
                            <a href="/{{ $item->airLocations[0]->infoAirLocation->seo->slug_full }}" title="Vé máy bay đi {{ $item->display_name ?? null }}">
                                <h2 class="sectionBox_title">Vé máy bay đi {{ $item->display_name ?? null }}</h2>
                            </a>
                        @else 
                            <h2 class="sectionBox_title">Vé máy bay đi {{ $item->display_name ?? null }}</h2>
                        @endif
                        <p>Để đến được {{ $item->display_name ?? null }} nhanh chóng, an toàn và tiện lợi tốt nhất bạn nên di chuyển bằng máy bay. Thông tin chi tiết các <strong>chuyến bay đến {{ $item->display_name ?? null }}</strong> bạn có thể tham khảo bên dưới</p>
                        @include('main.tourLocation.airGrid', [
                            'list'          => $dataAirs, 
                            'limit'         => 3, 
                            'link'          => $item->airLocations[0]->infoAirLocation->seo->slug_full,
                            'itemHeading'   => 'h3'
                        ])
                    </div>
                </div>
            @endif

            <!-- Vé vui chơi & giải trí -->
            @if(!empty($item->serviceLocations[0]->infoServiceLocation))
                <div class="sectionBox">
                    <div class="container">
                        @if(!empty($item->serviceLocations[0]->infoServiceLocation->seo->slug_full))
                            <a href="/{{ $item->serviceLocations[0]->infoServiceLocation->seo->slug_full }}" title="Vé vui chơi tại {{ $item->display_name ?? null }}">
                                <h2 class="sectionBox_title">Vé vui chơi tại {{ $item->display_name ?? null }}</h2>
                            </a>
                        @else 
                            <h2 class="sectionBox_title">Vé vui chơi tại {{ $item->display_name ?? null }}</h2>
                        @endif
                        <p>Ngoài các <strong>chương trình Tour {{ $item->display_name ?? null }}</strong> bạn cũng có thể tham khảo thêm các <strong>hoạt động vui chơi, giải trí khác tại {{ $item->display_name ?? null }}</strong>. Đây là các chương trình đặc biệt bạn có thể tham gia để bù đắp thời gian tự túc trong <strong>chương trình Tour</strong> và chắc chắn sẽ mang đến cho bạn nhiều trải nghiệm thú vị.</p>
                        @include('main.tourLocation.serviceGrid', [
                            'list' => $item->serviceLocations,
                            'itemHeading'   => 'h3'
                        ])
                    </div>
                </div>
            @endif

            <!-- Cẩm nang du lịch -->
            @if(!empty($item->guides[0]->infoGuide))
                <div class="sectionBox withBorder">
                    <div class="container">
                        <h2 class="sectionBox_title">Cẩm nang du lịch {{ $item->display_name ?? null }}</h2>
                        <p>Nếu các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> của Hitour không đáp ứng được nhu cầu của bạn hoặc là người ưu thích du lịch tự túc,... Bạn có thể tham khảo <strong>Cẩm nang du lịch</strong> bên dưới để có đầy đủ thông tin, tự do lên kế hoạch, sắp xếp lịch trình cho chuyến đi của mình được chu đáo nhất.</p>
                        <div class="guideList">
                            @foreach($item->guides as $guide)
                                <div class="guideList_item">
                                    <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="/{{ $guide->infoGuide->seo->slug_full }}" title="{{ $guide->infoGuide->name }}<">{{ $guide->infoGuide->name }}</a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- faq -->
            @if(!empty($item->questions)&&$item->questions->isNotEmpty())
                <div class="sectionBox withBorder">
                    <div class="container">
                        <h2 class="sectionBox_title">Câu hỏi thường gặp về Tour {{ $item->display_name ?? null }}</h2>
                        @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                    </div>
                </div>
            @endif
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