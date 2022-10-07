<div class="tourGrid">
    @if(!empty($list))
        @foreach($list as $tour)
            @php
                // dd($tour);
            @endphp
            <div class="tourGrid_item">
                <a href="{{ $tour->infoTour->seo->slug_full }}" class="tourGrid_item_image">
                    <img src="{{ $tour->infoTour->seo->image }}" alt="{{ $tour->infoTour->name }}" title="{{ $tour->infoTour->name }}" />
                    @if($tour->infoTour->days>1)
                        <div class="tourGrid_item_image_time">
                            {{ $tour->infoTour->days.'N'.$tour->infoTour->nights.'Đ' }}
                        </div>
                    @else 
                        @if(!empty($tour->infoTour->time_start)&&!empty($tour->infoTour->time_end))
                            <div class="tourGrid_item_image_time">
                                {{ $tour->infoTour->time_start.' - '.$tour->infoTour->time_end }}
                            </div>
                        @endif
                    @endif
                </a>
                <a href="{{ $tour->infoTour->seo->slug_full }}" class="tourGrid_item_title maxLine_1">
                    <h2>{{ $tour->infoTour->name }}</h2>
                </a>
                <a href="{{ $tour->infoTour->seo->slug_full }}" class="tourGrid_item_desc maxLine_4">
                    <h3>{{ $tour->infoTour->seo->description }}</h3>
                </a>
                <div class="column" style="align-items:flex-end !important;">
                    <div class="column_item">
                        <div class="tourGrid_item_departureFrom maxLine_1">
                            Đón tại {{ $tour->infoTour->pick_up }} {{ $tour->infoTour->tour_departure_name }}
                        </div>
                        <div class="tourGrid_item_departureSchedule">
                            {{ $tour->infoTour->departure_schedule }}
                        </div>
                    </div>
                    <div class="column_item tourGrid_item_price">
                        {{ number_format($tour->infoTour->price_show).config('main.unit_currency') }}
                    </div>
                </div>
                {{-- <div class="tourGrid_item_info">

                </div>
                <div class="tourGrid_item_location">
                    <i class="fa-solid fa-location-dot"></i>{{ $tour->infoTour->tour_location_name }}
                </div> --}}
            </div>
        @endforeach
    @endif
</div>