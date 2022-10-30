<div class="serviceGrid">
    @if(!empty($list))
        @foreach($list as $listItem)
            @foreach($listItem->infoServiceLocation->services as $service)
            <div class="serviceGrid_item">
                <a href="/{{ $service->seo->slug_full ?? null }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" class="serviceGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $service->seo->image_small ?? $service->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" />
                    @if(!empty($service->time_start)&&!empty($service->time_end))
                        <div class="serviceGrid_item_image_time">
                            {{ $service->time_start.' - '.$service->time_end }}
                        </div>
                    @endif
                </a>
                <a href="/{{ $service->seo->slug_full }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" class="serviceGrid_item_title maxLine_1">
                    @if(!empty($itemHeading)&&$itemHeading=='h3')
                        <h3>{{ $service->name ?? $service->seo->title ?? null }}</h3>
                    @else 
                        <h2>{{ $service->name ?? $service->seo->title ?? null }}</h2>
                    @endif
                </a>
                <a href="/{{ $service->seo->slug_full ?? null }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" class="serviceGrid_item_desc maxLine_4">
                    @if(!empty($itemHeading)&&$itemHeading=='h3')
                        <h4>{{ $service->description ?? $service->seo->description ?? null }}</h4>
                    @else 
                        <h3>{{ $service->description ?? $service->seo->description ?? null }}</h3>
                    @endif
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
                    @if(!empty($service->price_show))
                        <div class="column_item serviceGrid_item_price">
                            {{ number_format($service->price_show).config('main.unit_currency') }}
                        </div>
                    @endif
                </div>
                {{-- <div class="serviceGrid_item_info">

                </div> --}}
            </div>
            @endforeach
        @endforeach
    @endif
</div>