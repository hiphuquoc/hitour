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
@php
    $highPrice  = $item->price_show ?? 5000000;
    $lowPrice   = round($highPrice/2, 0);
@endphp
@include('main.schema.product', ['data' => $dataSchema, 'files' => $item->files, 'lowPrice' => $lowPrice, 'highPrice' => $highPrice])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- STRAT:: FAQ Schema -->
@include('main.schema.faq', ['data' => $item->questions])
<!-- END:: FAQ Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">

                <div class="pageContent_head">
                    <!-- title -->
                    <h1 class="titlePage">{{ $item->name }}</h1>
                    <!-- rating -->
                    @include('main.template.rating', compact('item'))
                </div>
    
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
    
                        <!-- gallery -->
                        @include('main.tour.gallery', compact('item'))
    
                        <!-- content box -->
                        @include('main.tour.content', compact('item'))
                        
                    </div>
                    <div class="pageContent_body_sidebar">
    
                        @include('main.tour.detailTour', compact('item'))

                        @include('main.tour.price', compact('item'))
    
                        <div class="js_scrollFixed" style="margin-top:0;">
                            @php
                                $linkTourBooking = route('main.tourBooking.form', [
                                    'tour_location_id'  => $item->locations[0]->infoLocation->id ?? 0,
                                    'tour_info_id'      => $item->id ?? 0
                                ]);
                            @endphp 
                            @include('main.template.callbook', [
                                'flagButton'    => true,
                                'button'        => 'Đặt Tour',
                                'linkFull'      => $linkTourBooking
                            ])
                            @include('main.tour.tocContentTour', compact('item'))
                            <!-- đoạn này để xử lý trường hợp đang mở xem thêm điểm nổi bật tour thì không scroll -->
                            <input type="hidden" id="js_scrollFixed_flag" value="true" />
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection
@push('bottom')
    <!-- button book tour mobile -->
    @if(!empty($item->price_show))
        <div class="show-990">
            <div class="callBookTourMobile">
                <div class="callBookTourMobile_price">
                    {{ number_format($item->price_show).config('main.unit_currency') }}
                </div>
                <a href="{{ $linkTourBooking }}"><h2>Đặt Tour này</h2></a>
            </div>
        </div>
    @endif
@endpush
@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function (){
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
        });
    </script>
@endpush