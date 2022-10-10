@extends('main.layouts.main')
@section('content')

    @php
        $active = 'tour';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active'))

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="container">
            <!-- Vé vui chơi giải trí -->
            <div class="sectionBox">
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
                @include('main.serviceLocation.serviceGrid', ['list' => $item->services])
            </div>

            <!-- Tour du lịch -->
            @php
                $dataTours              = new \Illuminate\Support\Collection();
                foreach($item->tourLocations as $tourLocation){
                    foreach($tourLocation->infoTourLocation->tours as $tour){
                        $dataTours[]    = $tour->infoTour;
                    }
                }
            @endphp
            @if(!empty($dataTours)&&$dataTours->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Tour du lịch {{ $item->display_name ?? null }}</h2>
                    <p>Bạn có thể tham khảo thêm các <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> đã bao gồm các <strong>hoạt động vui chơi giải trí trên</strong>. Đây là các chương trình tour được thiết kế - điều hành bởi Hitour và hệ thống đối tác uy tín, chất lượng sẽ giúp bạn thoải mái trải nghiệm, tận hưởng những dịch vụ vui chơi giải trí tuyệt vời tại {{ $item->display_name ?? null }}.</p>
                    @include('main.serviceLocation.tourGrid', ['list' => $dataTours])
                </div>
            @endif

            <!-- Vé tàu cao tốc -->
            @php
                $dataShips              = new \Illuminate\Support\Collection();
                foreach($item->tourLocations as $tourLocation){
                    foreach($tourLocation->infoTourLocation->shipLocations as $shipLocation){
                        $dataShips  = $dataShips->merge($shipLocation->infoShipLocation->ships);
                    }
                }
            @endphp
            @if(!empty($dataShips)&&$dataShips->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Vé tàu cao tốc {{ $item->display_name ?? null }}</h2>
                    <p>Để đến được {{ $item->display_name ?? null }} bạn có thể di chuyển bằng tàu cao tốc để tiết kiệm chi phí, đa dạng lịch trình và được trải nghiệm khung cảnh biển đúng nghĩa. Bên dưới là tất cả các <strong>chuyến tàu {{ $item->display_name ?? null }}</strong> đang hoạt động năm {{ date('Y', time() )}}, thông tin về giá, lịch trình và chính sách mới nhất sẽ được cập nhật mỗi ngày tại <a href="/">Hitour</a>.</p>
                    @include('main.shipLocation.shipGridMerge', ['list' => $dataShips])
                </div>
            @endif

            <!-- Cẩm nang du lịch -->
            @php
                $dataGuide                          = new \Illuminate\Support\Collection();
                if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty()){
                    foreach($item->tourLocations as $tourLocation){
                        if(!empty($tourLocation->infoTourLocation->guides)&&$tourLocation->infoTourLocation->guides->isNotEmpty()){
                            foreach($tourLocation->infoTourLocation->guides as $guide){
                                $dataGuide[]        = $guide->infoGuide;
                            }
                        }
                    }
                }
            @endphp
            @if(!empty($dataGuide)&&$dataGuide->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Cẩm nang du lịch {{ $item->display_name ?? null }}</h2>
                    <p>Hitour gợi ý thêm cho bạn <strong>Cẩm nang du lịch {{ $item->display_name ?? null }} từ A-Z</strong> để bạn có thể tham khảo thông tin chi tiết về <strong>du lịch {{ $item->display_name ?? null }}</strong> và lên kế hoạch kết hợp cùng các <strong>vé vui chơi giải trí {{ $item->display_name ?? null }}</strong> để có lịch trình du lịch hợp lí nhất.</p>
                    <div class="guideList">
                        @foreach($dataGuide as $guide)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="{{ $guide->seo->slug_full }}">{{ $guide->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Cho thuê xe -->
            @php
                $dataCarrental                      = new \Illuminate\Support\Collection();
                if(!empty($item->tourLocations)&&$item->tourLocations->isNotEmpty()){
                    foreach($item->tourLocations as $tourLocation){
                        if(!empty($tourLocation->infoTourLocation->carrentalLocations)&&$tourLocation->infoTourLocation->carrentalLocations->isNotEmpty()){
                            foreach($tourLocation->infoTourLocation->carrentalLocations as $carrentalLocation){
                                $dataCarrental[]    = $carrentalLocation->infoCarrentalLocation;
                            }
                        }
                    }
                }
            @endphp
            @if(!empty($dataCarrental)&&$dataCarrental->isNotEmpty())
                <div class="sectionBox">
                    <h2 class="titlePage">Cho thuê xe {{ $item->display_name ?? null }}</h2>
                    <p>Nếu cần phương tiện di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ <strong>Cho thuê xe tại {{ $item->display_name ?? null }}</strong> của Hitour với đầy đủ lựa chọn (tự lái hoặc có tài xế), xe mới, nhiều loại phù hợp yêu cầu và mức giá hợp lí.</p>
                    <div class="guideList">
                        @foreach($dataCarrental as $carrentalLocation)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="{{ $carrentalLocation->seo->slug_full }}">{{ $carrentalLocation->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- faq -->
            <div class="sectionBox">
                @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
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