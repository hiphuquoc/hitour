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

@php
    $dataList           = null;  
    if(!empty($item->tours)&&$item->tours->isNotEmpty()){
        $dataList       = new \Illuminate\Support\Collection();
        foreach($item->tours as $tour){
            $dataList[] = $tour->infoTour;
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
        <div class="container">

            <!-- Mô tả Tour du lịch -->
            <div class="sectionBox">
                <!-- title -->
                <h1 class="titlePage">Du lịch {{ $item->display_name }} - Tour du lịch {{ $item->display_name }}</h1>
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
                            {{ $item->seo->rating_aggregate_star }} sao / {{ $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch
                        </div>
                    </div>
                @endif
                @if(!empty($item->description))
                    <div class="contentBox">
                        <p>{!! $item->description !!}</p>
                    </div>
                @endif
                <!-- Tour box -->
                @if(!empty($item->tours)&&$item->tours->isNotEmpty())
                    @php
                        $dataTours              = new \Illuminate\Support\Collection();
                        foreach($item->tours as $tour) $dataTours[] = $tour->infoTour;
                    @endphp
                    @include('main.tourLocation.tourGrid', ['list' => $dataTours])
                @endif
            </div>

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
                    <h2 class="titlePage">Vé máy bay đi {{ $item->display_name ?? null }}</h2>
                    <p>Để đến được {{ $item->display_name ?? null }} nhanh chóng, an toàn và tiện lợi nhất bạn có thể di chuyển bằng máy bay. Chi tiết các <strong>chuyến bay đến {{ $item->display_name ?? null }}</strong> bạn có thể tham khảo thông tin bên dưới</p>
                    @include('main.tourLocation.airGrid', ['list' => $dataAirs])
                </div>
            @endif

            <!-- Vé tàu cao tốc -->
            @if(!empty($item->shipLocations)&&$item->shipLocations->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Vé tàu cao tốc {{ $item->display_name ?? null }}</h2>
                    <p>Để đến được {{ $item->display_name ?? null }} bạn có thể di chuyển bằng tàu cao tốc để tiết kiệm chi phí, đa dạng lịch trình và được trải nghiệm khung cảnh biển đúng nghĩa. Bên dưới là tất cả các <strong>chuyến tàu {{ $item->display_name ?? null }}</strong> đang hoạt động năm {{ date('Y', time() )}}, thông tin về giá, lịch trình và chính sách mới nhất sẽ được cập nhật mỗi ngày tại <a href="/">Hitour</a>.</p>
                    @php
                        $dataShips      = new \Illuminate\Support\Collection();
                        foreach($item->shipLocations as $shipLocation){
                            $dataShips  = $dataShips->merge($shipLocation->infoShipLocation->ships);
                        }
                    @endphp
                    @include('main.shipLocation.shipGridMerge', ['list' => $dataShips])
                </div>
            @endif

            <!-- Vé vui chơi & giải trí -->
            @if(!empty($item->serviceLocations)&&$item->serviceLocations->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Vé vui chơi tại {{ $item->display_name ?? null }}</h2>
                    <p>Ngoài các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> bạn cũng có thể tham khảo thêm các <strong>hoạt động vui chơi giải trí khác tại {{ $item->display_name ?? null }}</strong>. Đây là các chương trình đặc biệt có thể bù dắp khoảng trống thời gian tự túc trong <strong>chương trình Tour</strong> của bạn và chắc chắn sẽ mang đến cho bạn nhiều trải nghiệm thú vị.</p>
                    @include('main.tourLocation.serviceGrid', ['list' => $item->serviceLocations])
                </div>
            @endif

            <!-- Cẩm nang du lịch -->
            @if(!empty($item->guides)&&$item->guides->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Cẩm nang du lịch {{ $item->display_name ?? null }}</h2>
                    <p>Nếu các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> của Hitour không đáp ứng được nhu cầu của bạn, hoặc bạn là người ưu thích du lịch tự túc,... Hitour cung cấp thêm cho bạn <strong>Cẩm nang du lịch {{ $item->display_name ?? null }} từ A-Z</strong> để bạn có thể tự do tham khảo thông tin chi tiết về <strong>du lịch {{ $item->display_name ?? null }}</strong>, lên kế hoạch, sắp xếp cho chuyến đi du lịch của mình được chu đáo nhất.</p>
                    <div class="guideList">
                        @foreach($item->guides as $guide)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="/{{ $guide->infoGuide->seo->slug_full }}">{{ $guide->infoGuide->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cho thuê xe -->
            @if(!empty($item->carrentalLocations)&&$item->carrentalLocations->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Cho thuê xe {{ $item->display_name ?? null }}</h2>
                    <p>Nếu cần phương tiện di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ <strong>Cho thuê xe tại {{ $item->display_name ?? null }}</strong> của Hitour với đầy đủ lựa chọn (tự lái hoặc có tài xế), xe mới, nhiều loại phù hợp yêu cầu và mức giá hợp lí.</p>
                    <div class="guideList">
                        @foreach($item->carrentalLocations as $carrentalLocation)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full }}">{{ $carrentalLocation->infoCarrentalLocation->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- faq -->
            @if(!empty($item->questions)&&$item->questions->isNotEmpty())
                <div class="sectionBox">
                    @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
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