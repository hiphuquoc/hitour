<div class="serviceGrid slickBox">
    @if(!empty($list))
        @foreach($list as $service)
            <div class="serviceGrid_item">
                <a href="/{{ $service->seo->slug_full ?? null }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" class="serviceGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $service->seo->image_small ?? $service->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" />
                    @if(!empty($service->time_start)&&!empty($service->time_end))
                        <div class="serviceGrid_item_image_time">
                            {{ $service->time_start.' - '.$service->time_end }}
                        </div>
                    @endif
                </a>
                <div class="serviceGrid_item_content">
                    <a href="/{{ $service->seo->slug_full }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" class="serviceGrid_item_content_title maxLine_1">
                        <h3 class="maxLine_1">{{ $service->name ?? $service->seo->title ?? null }}</h3>
                    </a>
                    <a href="/{{ $service->seo->slug_full }}" title="{{ $service->name ?? $service->seo->title ?? $service->seo->seo_title ?? null }}" class="serviceGrid_item_content_desc maxLine_4">
                        <h4 class="maxLine_3">{{ $service->description ?? $service->seo->description ?? null }}</h4>
                    </a>
                    <div class="column">
                        <div class="column_item">
                            {{-- <div class="serviceGrid_item_content_departureFrom maxLine_1">
                                Đón tại {{ $service->pick_up }} {{ $service->tour_departure_name }}
                            </div>
                            <div class="serviceGrid_item_content_departureSchedule">
                                {{ $service->departure_schedule }}
                            </div> --}}
                        </div>
                        <div class="column_item serviceGrid_item_content_price">
                            {{ number_format($service->price_show).config('main.unit_currency') }}
                        </div>
                    </div>
                </div>
                <div class="serviceGrid_item_location">
                    <i class="fa-solid fa-location-dot"></i>{{ $service->serviceLocation->display_name ?? null }}
                </div>
            </div>
        @endforeach
    @endif
</div>
{{-- <div class="viewMore">
    <a href="/{{ $link ?? null }}" title="Xem thêm"><i class="fa-solid fa-arrow-down-long"></i>Xem thêm</a>
</div> --}}