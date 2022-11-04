@extends('main.layouts.main')
@push('head-custom')
<!-- ===== START:: SCHEMA ===== -->

<!-- STRAT:: Organization Schema -->
@include('main.schema.organization')
<!-- END:: Organization Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.article', compact('item'))
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.creativeworkseries', compact('item'))
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')
    @include('main.home.slider')

    <!-- START: Sort Booking -->
    @php
        $active = 'ship';
    @endphp
    @include('main.form.formBooking', compact('active'))
    <!-- END: Sort Booking -->

    <!-- START: Điểm đến nổi bật -->
    @if(!empty($islandLocations)&&$islandLocations->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                <h2 class="sectionBox_title">Điểm đến nổi bật</h2>
                <p>Điểm đến hot nhất do khách du lịch bình chọn.</p>
                @include('main.home.specialLocation', compact('specialLocations'))
            </div>
        </div>
    @endif
    <!-- END: Điểm đến nổi bật -->

    <!-- START: Điểm đến biển đảo -->
    @if(!empty($islandLocations)&&$islandLocations->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                <h2 class="sectionBox_title">Điểm đến biển đảo</h2>
                <p>Danh sách điểm đến biển đảo hấp dẫn tại Việt Nam với đầy đủ thông tin du lịch bạn cần.</p>
                @include('main.home.islandLocation', compact('islandLocations'))
            </div>
        </div>
    @endif
    <!-- END: Điểm đến biển đảo -->
    

    <!-- START: Vé máy bay -->
    @if(!empty($airLocations)&&$airLocations->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                <h2 class="sectionBox_title">Vé máy bay trong nước</h2>
                <p>Tổng hợp các chuyến bay trong nước của tất cả các hãng máy bay đang hoạt động tại Việt Nam.</p>
                @include('main.home.airLocationList', compact('airLocations'))
            </div>
        </div>
    @endif
    <!-- END: Vé máy bay -->

    <!-- START: Tàu cao tốc -->
    @if(!empty($shipLocations)&&$shipLocations->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                <h2 class="sectionBox_title">Vé tàu cao tốc</h2>
                <p>Tổng hợp các chuyến tàu cao tốc biển đảo của tất cả các hãng tàu đang hoạt động tại Việt Nam.</p>
                @include('main.home.shipLocationList', compact('shipLocations'))
            </div>
        </div>
    @endif
    <!-- END: Tàu cao tốc -->

    <!-- START: Vé vui chơi giải trí -->
    @if(!empty($serviceLocations)&&$serviceLocations->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                <h2 class="sectionBox_title">Vé vui chơi giải trí</h2>
                <p>Tổng hợp vé vui chơi và hoạt động giải trí theo từng địa điểm cụ thể.</p>
                @include('main.home.serviceLocationList', compact('serviceLocations'))
            </div>
        </div>
    @endif
    <!-- END: Vé vui chơi giải trí -->

    <!-- START: Đối tác tàu cao tốc -->
    @if(!empty($shipPartners)&&$shipPartners->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                @include('main.home.partner', [
                    'list'          => $shipPartners,
                    'title'         => 'Đối tác tàu cao tốc',
                    'description'   => 'Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ'
                ])
            </div>
        </div>
    @endif
    <!-- END: Đối tác tàu cao tốc -->

    <!-- START: Đối tác máy bay -->
    @if(!empty($airPartners)&&$airPartners->isNotEmpty())
        <div class="sectionBox withBorder">
            <div class="container">
                @include('main.home.partner', [
                    'list'          => $airPartners,
                    'title'         => 'Đối tác máy bay',
                    'description'   => 'Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ'
                ])
            </div>
        </div>
    @endif
    <!-- END: Đối tác máy bay -->

    {{-- <div class="sectionBox">
        <div class="container">
            @include('main.home.blogListNoImage')
        </div>
    </div> --}}
    
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