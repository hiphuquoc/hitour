@if(!empty($list)&&$list->isNotEmpty()) 
    <div class="tourRelated">
        @foreach($list as $tour)
            <div class="tourRelated_item">
                <a href="/{{ $tour->seo->slug_full ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" class="tourRelated_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $tour->seo->image_small ?? $tour->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" />
                    @if($tour->days>1)
                        <div class="tourRelated_item_image_time">
                            {{ $tour->days.'N'.$tour->nights.'Đ' }}
                        </div>
                    @else 
                        @if(!empty($tour->time_start)&&!empty($tour->time_end))
                            <div class="tourRelated_item_image_time">
                                {{ $tour->time_start.' - '.$tour->time_end }}
                            </div>
                        @endif
                    @endif
                </a>
                <a href="/{{ $tour->seo->slug_full ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" class="tourRelated_item_title maxLine_1">
                    <h2>{{ $tour->name ?? $tour->seo->title ?? null }}</h2>
                </a>
                <a href="/{{ $tour->seo->slug_full ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" class="tourRelated_item_desc maxLine_4">
                    <h3 class="maxLine_3">{{ $tour->description ?? $tour->seo->description ?? null }}</h3>
                </a>
                <div class="column" style="align-items:flex-end !important;">
                    <div class="column_item">
                        <div class="tourRelated_item_departureFrom maxLine_1">
                            Đón tại {{ $tour->pick_up ?? null }} {{ $tour->tour_departure_name ?? null }}
                        </div>
                        @if(!empty($tour->departure_schedule))
                            <div class="tourRelated_item_departureSchedule">
                                {{ $tour->departure_schedule }}
                            </div>
                        @endif
                    </div>
                    @if(!empty($tour->price_show))
                        <div class="column_item tourRelated_item_price">
                            {{ number_format($tour->price_show).config('main.unit_currency') }}
                        </div>
                    @endif
                </div>
                {{-- <div class="tourRelated_item_info">

                </div>
                <div class="tourRelated_item_location">
                    <i class="fa-solid fa-location-dot"></i>{{ $tour->tour_location_name }}
                </div> --}}
            </div>
        @endforeach
    </div>
@endif

@push('scripts-custom')
    <script type="text/javascript">
        $('.tourRelated').slick({
            infinite: false,
            slidesToShow: 2.5,
            slidesToScroll: 2,
            arrows: true,
            prevArrow: '<button class="slick-arrow slick-prev"><i class="fa-solid fa-angle-left"></i></button>',
            nextArrow: '<button class="slick-arrow slick-next"><i class="fa-solid fa-angle-right"></i></button>',
            responsive: [
                {
                    breakpoint: 1199,
                    settings: {
                        infinite: false,
                        slidesToShow: 1.7,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 990,
                    settings: {
                        infinite: false,
                        slidesToShow: 2.5,
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
                    settings: 'unslick'
                }
            ]
        });

    </script>

@endpush