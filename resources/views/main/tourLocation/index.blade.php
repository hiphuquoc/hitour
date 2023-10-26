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
    foreach($item->tours as $tour) if(!empty($tour->infoTour->price_show)) $arrayPrice[] = $tour->infoTour->price_show;
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
    $dataList           = new \Illuminate\Support\Collection();
    if(!empty($item->tours)&&$item->tours->isNotEmpty()){
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

    @include('main.form.sortBooking', [
        'item'      => $item,
        'active'    => 'tour'
    ])

    @include('main.snippets.breadcrumb')

    <div class="pageContent">

        <!-- Tour box -->
        <div class="sectionBox backgroundPrimaryGradiend">
            <div class="container">
                <h2 class="titlePage">Tour {{ $item->display_name ?? null }} - Danh sách Tour du lịch {{ $item->display_name ?? null }} chất lượng</h2>
                <p class="sectionBox_desc">Tổng hợp các chương trình <strong>Tour {{ $item->display_name ?? null }} trọn gói</strong> và <strong>Tour {{ $item->display_name ?? null }} trong ngày</strong> đa dạng, chất lượng hàng đầu được cung cấp và đảm bảo bởi {{ config('main.name') }} cùng hệ thống đối tác du lịch trên toàn quốc.</p>
                @include('main.tourLocation.filterBox')
                @php
                    $dataTours              = new \Illuminate\Support\Collection();
                    foreach($item->tours as $tour) if(!empty($tour->infoTour)) $dataTours[] = $tour->infoTour;
                @endphp
                @if(!empty($dataTours)&&$dataTours->isNotEmpty())
                    @include('main.tourLocation.tourItem', ['list' => $dataTours])
                @else 
                    <div style="color:rgb(0,123,255);">Các chương trình <strong>Tour {{ $item->display_name ?? null }}</strong> đang được {{ config('main.name') }} cập nhật và sẽ sớm giới thiệu đến Quý khách trong thời gian tới!</div>
                @endif
            </div>
        </div>

        <!-- Giới thiệu Tour du lịch -->
        <div class="sectionBox" style="padding-top:0;">
            <div class="container">
                <!-- title -->
                <h2 class="sectionBox_title">Tour {{ $item->display_name ?? null }} - Giới hiệu thêm về Tour du lịch {{ $item->display_name ?? null }}</h2>
                <!-- content -->
                @if(!empty($content))
                    <div id="js_showHideFullContent_content" class="contentBox maxLine_4">
                        {!! $content !!}
                    </div>
                    <div class="viewMore">
                        <div onClick="showHideFullContent(this, 'maxLine_4');">
                            <i class="fa-solid fa-arrow-down-long"></i>Đọc thêm
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- START:: Video -->
        @include('main.tourLocation.videoBox', [
            'item'  => $item,
            'title' => 'Video Tour du lịch '.$item->display_name
        ])
        <!-- END:: Video -->

        <!-- Hướng dẫn đặt Tour -->
        @include('main.tourLocation.guideBook', ['title' => 'Hướng dẫn đặt Tour '.$item->display_name])

        <!-- Combo du lịch -->
        @php
            $dataCombos             = new \Illuminate\Support\Collection();
            $i                      = 0;
            foreach($item->comboLocations as $comboLocation){
                foreach($comboLocation->infoComboLocation->combos as $combo){
                    $dataCombos[$i]               = $combo->infoCombo;
                    $dataCombos[$i]->seo          = $combo->infoCombo->seo;
                    $dataCombos[$i]->location     = $combo->infoCombo->location;
                    $dataCombos[$i]->departure    = $combo->infoCombo->departure;
                    ++$i;
                }
            }
        @endphp
        @if(!empty($dataCombos)&&$dataCombos->isNotEmpty())
            <div class="sectionBox">
                <div class="container">
                    @if(!empty($item->comboLocations[0]->infoComboLocation->seo->slug_full))
                        <a href="/{{ $item->comboLocations[0]->infoComboLocation->seo->slug_full }}" title="Combo du lịch {{ $item->display_name ?? null }}">
                            <h2 class="sectionBox_title">Combo du lịch {{ $item->display_name ?? null }}</h2>
                        </a>
                    @else 
                        <h2 class="sectionBox_title">Combo du lịch {{ $item->display_name ?? null }}</h2>
                    @endif
                    <p class="sectionBox_desc">Danh sách Combo các dịch vụ tại Côn Đảo đang được ưa chương có thể phù hợp với bạn</p>
                    @include('main.comboLocation.comboItem', [
                        'list'          => $dataCombos
                    ])
                </div>
            </div>
        @endif

        <!-- Cho thuê xe -->
        @if(!empty($item->carrentalLocations[0]->infoCarrentalLocation))
            <div class="sectionBox withBorder">
                <div class="container">
                    <h2 class="sectionBox_title">Cho thuê xe {{ $item->display_name ?? null }}</h2>
                    <p class="sectionBox_desc">Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ <strong>Cho thuê xe tại {{ $item->display_name ?? null }}</strong> của {{ config('main.name') }} với đầy đủ lựa chọn (tự lái hoặc có tài xế), xe đời mới, nhiều loại phù hợp yêu cầu và mức giá hợp lí.</p>
                    <div class="guideList">
                        @foreach($item->carrentalLocations as $carrentalLocation)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="/{{ $carrentalLocation->infoCarrentalLocation->seo->slug_full }}" title="{{ $carrentalLocation->infoCarrentalLocation->name }}">{{ $carrentalLocation->infoCarrentalLocation->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Vé máy bay -->
        @php
            $dataAirs               = new \Illuminate\Support\Collection();
            $i                      = 0;
            foreach($item->airLocations as $airLocation){
                foreach($airLocation->infoAirLocation->airs as $air){
                    $dataAirs[$i]               = $air;
                    $dataAirs[$i]->seo          = $air->seo;
                    $dataAirs[$i]->location     = $air->location;
                    $dataAirs[$i]->departure    = $air->departure;
                    ++$i;
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
                    <p class="sectionBox_desc">Để đến được {{ $item->display_name ?? null }} nhanh chóng, an toàn và tiện lợi tốt nhất bạn nên di chuyển bằng máy bay. Thông tin chi tiết các <strong>chuyến bay đến {{ $item->display_name ?? null }}</strong> bạn có thể tham khảo bên dưới</p>
                    @include('main.airLocation.airItem', [
                        'list'          => $dataAirs, 
                        'limit'         => 3, 
                        'link'          => $item->airLocations[0]->infoAirLocation->seo->slug_full, 
                        'itemHeading'   => 'h3',
                        'slick'         => true
                    ])
                </div>
            </div>
        @endif

        <!-- Vé tàu cao tốc -->
        @if(!empty($item->shipLocations[0]->infoShipLocation))
            <div class="sectionBox">
                <div class="container">
                    @if(!empty($item->shipLocations[0]->infoShipLocation->seo->slug_full))
                        <a href="/{{ $item->shipLocations[0]->infoShipLocation->seo->slug_full }}" title="Vé tàu cao tốc {{ $item->display_name ?? null }}">
                            <h2 class="sectionBox_title">Vé tàu cao tốc {{ $item->display_name ?? null }}</h2>
                        </a>
                    @else
                        <h2 class="sectionBox_title">Vé tàu cao tốc {{ $item->display_name ?? null }}</h2>
                    @endif
                    <p class="sectionBox_desc">Một trong các phương tiện tối ưu nhất hiện nay để đi đến {{ $item->display_name ?? null }} là <strong>Tàu cao tốc</strong>, ngoài việc tiết kiệm thời gian, chi phí bạn còn được trải nghiệm khung cảnh biển đúng nghĩa. Thông tin chi tiết các <strong>chuyến tàu {{ $item->display_name ?? null }}</strong> đang hoạt động năm {{ date('Y', time() )}} bạn có thể tham khảo bên dưới</p>
                    @php
                        $dataShips      = new \Illuminate\Support\Collection();
                        foreach($item->shipLocations as $shipLocation){
                            $dataShips  = $dataShips->merge($shipLocation->infoShipLocation->ships);
                        }
                    @endphp
                    @include('main.shipLocation.shipGridMerge', [
                        'list'          => $dataShips, 
                        'limit'         => 3, 
                        'link'          => $item->shipLocations[0]->infoShipLocation->seo->slug_full,
                        'itemHeading'   => 'h3'
                    ])
                </div>
            </div>
        @endif

        <!-- Vé vui chơi & giải trí -->
        @php
            $dataServices            = new \Illuminate\Support\Collection();
            $i                      = 0;
            foreach($item->serviceLocations as $serviceLocation){
                foreach($serviceLocation->infoServiceLocation->services as $service){
                    $dataServices[$i]               = $service;
                    $dataServices[$i]->seo          = $service->seo;
                    ++$i;
                }
            }
        @endphp
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
                    <p class="sectionBox_desc">Ngoài các <strong>chương trình Tour {{ $item->display_name ?? null }}</strong> bạn cũng có thể tham khảo thêm các <strong>hoạt động vui chơi, giải trí khác tại {{ $item->display_name ?? null }}</strong>. Đây là các chương trình đặc biệt bạn có thể tham gia để bù đắp thời gian tự túc trong <strong>chương trình Tour</strong> và chắc chắn sẽ mang đến cho bạn nhiều trải nghiệm thú vị.</p>
                    @include('main.serviceLocation.serviceItem', [
                        'list'          => $dataServices,
                        'itemHeading'   => 'h3',
                        'slick'         => true
                    ])
                </div>
            </div>
        @endif

        <!-- Cẩm nang du lịch -->
        @if(!empty($item->guides[0]->infoGuide))
            <div class="sectionBox withBorder">
                <div class="container">
                    <h2 class="sectionBox_title">Cẩm nang du lịch {{ $item->display_name ?? null }}</h2>
                    <p class="sectionBox_desc">Nếu các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> của {{ config('main.name') }} không đáp ứng được nhu cầu của bạn hoặc là người ưu thích du lịch tự túc,... Bạn có thể tham khảo <strong>Cẩm nang du lịch</strong> bên dưới để có đầy đủ thông tin, tự do lên kế hoạch, sắp xếp lịch trình cho chuyến đi của mình được chu đáo nhất.</p>
                    <div class="guideList">
                        @foreach($item->guides as $guide)
                            <div class="guideList_item">
                                <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="/{{ $guide->infoGuide->seo->slug_full }}" title="{{ $guide->infoGuide->name }}">{{ $guide->infoGuide->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Điểm đến -->
        @if(!empty($destinationList[0]))
            <div class="sectionBox">
                <div class="container">
                    <h2 class="sectionBox_title">Điểm đến {{ $item->display_name ?? null }}</h2>
                    <p class="sectionBox_desc">Danh sách điểm đến nổi tiếng tại {{ $item->display_name ?? null }} mà bạn được khám phá trong quá trình tham gia <strong>Tour {{ $item->display_name ?? null }}</strong>.</p>
                    @include('main.tourLocation.blogGridSlick', ['list' => $destinationList, 'link' => $item->destinations[0]->infoCategory->seo->slug_full ?? null, 'limit' => 10])
                </div>
            </div>
        @endif
        <!-- Đặc sản -->
        @if(!empty($specialList[0]))
            <div class="sectionBox">
                <div class="container">
                    <h2 class="sectionBox_title">Đặc sản {{ $item->display_name ?? null }}</h2>
                    <p class="sectionBox_desc">Tổng hợp những món ngon, đặc sản nổi tiếng tại {{ $item->display_name ?? null }} bạn nên mua làm quà hoặc thưởng thức ít nhất một lần.</p>
                    @include('main.tourLocation.blogGridSlick', ['list' => $specialList, 'link' => $item->specials[0]->infoCategory->seo->slug_full ?? null, 'limit' => 10])
                </div>
            </div>
        @endif

        <!-- faq -->
        @if(!empty($item->questions)&&$item->questions->isNotEmpty())
            <div class="sectionBox withBorder">
                <div class="container" style="border-bottom:none !important;">
                    @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                </div>
            </div>
        @endif
    </div>
</div>

    
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function () {
            setSlick();
        });
        $(window).resize(function(){
            setSlick();
        })

        function showHideFullContent(elementButton, classCheck){
            const contentBox = $('#js_showHideFullContent_content');
            if(contentBox.hasClass(classCheck)){
                contentBox.removeClass(classCheck);
                $(elementButton).html('<i class="fa-solid fa-arrow-up-long"></i>Thu gọn');
            }else {
                contentBox.addClass(classCheck);
                $(elementButton).html('<i class="fa-solid fa-arrow-down-long"></i>Đọc thêm');
            }
        }

        function setSlick(){
            $('.slickBox').slick({
                infinite: false,
                slidesToShow: 3.01,
                slidesToScroll: 3,
                arrows: true,
                prevArrow: '<button class="slick-arrow slick-prev" aria-label="Slide trước"><i class="fa-solid fa-angle-left"></i></button>',
                nextArrow: '<button class="slick-arrow slick-next" aria-label="Slide tiếp theo"><i class="fa-solid fa-angle-right"></i></button>',
                responsive: [
                    {
                        breakpoint: 1023,
                        settings: {
                            infinite: false,
                            slidesToShow: 2.6,
                            slidesToScroll: 2,
                            arrows: true,
                        }
                    },
                    {
                        breakpoint: 767,
                        settings: {
                            infinite: false,
                            slidesToShow: 1.7,
                            slidesToScroll: 1,
                            arrows: true,
                        }
                    },
                    {
                        breakpoint: 577,
                        settings: {
                            infinite: false,
                            slidesToShow: 1.3,
                            slidesToScroll: 1,
                            arrows: false,
                        }
                    }
                ]
            });
        }
    </script>
@endpush