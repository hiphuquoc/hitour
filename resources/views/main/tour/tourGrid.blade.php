<div class="tourGrid">
    @if(!empty($list))
        @foreach($list as $tour)
            <div class="tourGrid_item">
                <a href="{{ $tour->slug_full }}" class="tourGrid_item_image">
                    <img src="{{ $tour->image }}" alt="{{ $tour->name }}" title="{{ $tour->name }}" />
                    @if($tour->days>1)
                        <div class="tourGrid_item_image_time">
                            {{ $tour->days.'N'.$tour->nights.'Đ' }}
                        </div>
                    @else 
                        @if(!empty($tour->time_start)&&!empty($tour->time_end))
                            <div class="tourGrid_item_image_time">
                                {{ $tour->time_start.' - '.$tour->time_end }}
                            </div>
                        @endif
                    @endif
                </a>
                <a href="{{ $tour->slug_full }}" class="tourGrid_item_title maxLine_1">
                    <h2>{{ $tour->name }}</h2>
                </a>
                <a href="{{ $tour->slug_full }}" class="tourGrid_item_desc maxLine_4">
                    <h3>{{ $tour->description }}</h3>
                </a>
                <div class="column">
                    <div class="column_item">
                        <div class="tourGrid_item_departureFrom maxLine_1">
                            Đón tại {{ $tour->pick_up }} {{ $tour->tour_departure_name }}
                        </div>
                        <div class="tourGrid_item_departureSchedule">
                            {{ $tour->departure_schedule }}
                        </div>
                    </div>
                    <div class="column_item tourGrid_item_price">
                        {{ number_format($tour->price_show).config('main.unit_currency') }}
                    </div>
                </div>
                <div class="tourGrid_item_info">

                </div>
                <div class="tourGrid_item_location">
                    <i class="fa-solid fa-location-dot"></i>{{ $tour->tour_location_name }}
                </div>
            </div>
        @endforeach
    @endif
</div>