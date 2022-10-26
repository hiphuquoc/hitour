<div class="serviceGrid">
    @if(!empty($list))
        @foreach($list as $listItem)
            @foreach($listItem->infoServiceLocation->services as $service)
            <div class="serviceGrid_item">
                <a href="/{{ $service->seo->slug_full }}" class="serviceGrid_item_image">
                    <img src="{{ $service->seo->image }}" alt="{{ $service->name }}" title="{{ $service->name }}" />
                    @if(!empty($service->time_start)&&!empty($service->time_end))
                        <div class="serviceGrid_item_image_time">
                            {{ $service->time_start.' - '.$service->time_end }}
                        </div>
                    @endif
                </a>
                <a href="/{{ $service->seo->slug_full }}" class="serviceGrid_item_title maxLine_1">
                    <h2>{{ $service->name }}</h2>
                </a>
                <a href="/{{ $service->seo->slug_full }}" class="serviceGrid_item_desc maxLine_4">
                    <h3>{{ $service->seo->description }}</h3>
                </a>
                <div class="column">
                    <div class="column_item">
                        {{-- <div class="serviceGrid_item_departureFrom maxLine_1">
                            Đón tại {{ $service->pick_up }} {{ $service->tour_departure_name }}
                        </div>
                        <div class="serviceGrid_item_departureSchedule">
                            {{ $service->departure_schedule }}
                        </div> --}}
                    </div>
                    <div class="column_item serviceGrid_item_price">
                        {{ number_format($service->price_show).config('main.unit_currency') }}
                    </div>
                </div>
                {{-- <div class="serviceGrid_item_info">

                </div> --}}
            </div>
            @endforeach
        @endforeach
    @endif
</div>