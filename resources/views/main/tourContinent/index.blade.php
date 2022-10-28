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

            <!-- Mô tả Tour du lịch -->
            <div class="sectionBox">
                <div class="container">
                    <!-- title -->
                    <h1 class="titlePage">Tour du lịch {{ $item->display_name }} - Du lịch {{ $item->display_name }}</h1>
                    <!-- rating -->
                    @include('main.template.rating', compact('item'))
                    <!-- description -->
                    @if(!empty($item->description))
                        <div class="contentBox">
                            <p>{!! $item->description !!}</p>
                        </div>
                    @endif
                    <!-- Tour box -->
                    @if(!empty($item->tourCountries)&&$item->tourCountries->isNotEmpty())
                        @php
                            $dataTours              = new \Illuminate\Support\Collection();
                            foreach($item->tourCountries as $tourCountry){
                                foreach($tourCountry->tours as $tour) $dataTours[] = $tour->infoTourForeign;
                            }
                        @endphp
                        @include('main.tourContinent.tourGrid', ['list' => $dataTours])
                    @endif
                </div>
            </div>

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
                        <h2 class="sectionBox_title">Vé máy bay đi {{ $item->display_name ?? null }}</h2>
                        <p>Để đến được {{ $item->display_name ?? null }} nhanh chóng, an toàn và tiện lợi nhất bạn có thể di chuyển bằng máy bay. Chi tiết các <strong>chuyến bay đến {{ $item->display_name ?? null }}</strong> bạn có thể tham khảo thông tin bên dưới</p>
                        @include('main.tourLocation.airGrid', ['list' => $dataAirs, 'limit' => 3, 'link' => $item->airLocations[0]->infoAirLocation->seo->slug_full])
                    </div>
                </div>
            @endif

            <!-- Vé vui chơi & giải trí -->
            @if(!empty($item->serviceLocations[0]->infoServiceLocation))
                <div class="sectionBox">
                    <div class="container">
                        <h2 class="sectionBox_title">Vé vui chơi tại {{ $item->display_name ?? null }}</h2>
                        <p>Ngoài các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> bạn cũng có thể tham khảo thêm các <strong>hoạt động vui chơi giải trí khác tại {{ $item->display_name ?? null }}</strong>. Đây là các chương trình đặc biệt có thể bù dắp khoảng trống thời gian tự túc trong <strong>chương trình Tour</strong> của bạn và chắc chắn sẽ mang đến cho bạn nhiều trải nghiệm thú vị.</p>
                        @include('main.tourLocation.serviceGrid', ['list' => $item->serviceLocations])
                    </div>
                </div>
            @endif

            <!-- Cẩm nang du lịch -->
            @if(!empty($item->guides[0]->infoGuide))
                <div class="sectionBox withBorder">
                    <div class="container">
                        <h2 class="sectionBox_title">Cẩm nang du lịch {{ $item->display_name ?? null }}</h2>
                        <p>Nếu các chương trình <strong>Tour du lịch {{ $item->display_name ?? null }}</strong> của Hitour không đáp ứng được nhu cầu của bạn, hoặc bạn là người ưu thích du lịch tự túc,... Hitour cung cấp thêm cho bạn <strong>Cẩm nang du lịch {{ $item->display_name ?? null }}</strong> để bạn có thể tham khảo thêm thông tin, tự do lên kế hoạch, sắp xếp cho chuyến đi du lịch của mình được chu đáo nhất.</p>
                        <div class="guideList">
                            @foreach($item->guides as $guide)
                                <div class="guideList_item">
                                    <i class="fa-solid fa-angles-right"></i>Xem thêm <a href="/{{ $guide->infoGuide->seo->slug_full }}">{{ $guide->infoGuide->name }}</a>
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