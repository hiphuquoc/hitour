@extends('main.layouts.main')
{{-- @push('head-custom')
<!-- ===== START:: SCHEMA ===== -->
<!-- STRAT:: Title - Description - Social -->
@include('main.schema.social', ['data' => $item])
<!-- END:: Title - Description - Social -->

<!-- STRAT:: Organization Schema -->
@include('main.schema.organization')
<!-- END:: Organization Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.article', ['data' => $item])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.creativeworkseries', ['data' => $item])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush --}}
@section('content')
    
    
    
@endsection
@push('scripts-custom')
    {{-- <script type="text/javascript">
        $('.serviceGrid').slick({
            infinite: false,
            slidesToShow: 3,
            slidesToScroll: 3,
            arrows: true,
            prevArrow: '<button class="slick-arrow slick-prev"><i class="fa-solid fa-angle-left"></i></button>',
            nextArrow: '<button class="slick-arrow slick-next"><i class="fa-solid fa-angle-right"></i></button>',
            responsive: [
                {
                    breakpoint: 1023,
                    settings: {
                        infinite: false,
                        slidesToShow: 2.6,
                        slidesToScroll: 2,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        infinite: false,
                        slidesToShow: 1.7,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 577,
                    settings: {
                        infinite: false,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                }
            ]
        });
    </script> --}}
@endpush