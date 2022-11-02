@if(!empty($list)&&$list->isNotEmpty()) 
    <div id="js_filterTour_parent" class="tourGrid">
        @foreach($list as $tour)
            <div class="tourGrid_item" data-filter-day="{{ $tour->days ?? 0 }}">
                <a href="/{{ $tour->seo->slug_full ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" class="tourGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $tour->seo->image_small ?? $tour->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" />
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
                <div class="tourGrid_item_content">
                    <a href="/{{ $tour->seo->slug_full ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" class="tourGrid_item_content_title maxLine_1">
                        <h2>{{ $tour->name ?? $tour->seo->title ?? null }}</h2>
                    </a>
                    <a href="/{{ $tour->seo->slug_full ?? null }}" title="{{ $tour->name ?? $tour->seo->title ?? $tour->seo->seo_title ?? null }}" class="tourGrid_item_content_desc maxLine_4">
                        <h3>{{ $tour->description ?? $tour->seo->description ?? null }}</h3>
                    </a>
                    <div class="column" style="align-items:flex-end !important;">
                        <div class="column_item">
                            <div class="tourGrid_item_content_departureFrom maxLine_1">
                                Đón tại {{ $tour->pick_up ?? null }} {{ $tour->tour_departure_name ?? null }}
                            </div>
                            @if(!empty($tour->departure_schedule))
                                <div class="tourGrid_item_content_departureSchedule">
                                    {{ $tour->departure_schedule }}
                                </div>
                            @endif
                        </div>
                        @if(!empty($tour->price_show))
                            <div class="column_item tourGrid_item_content_price">
                                {{ number_format($tour->price_show).config('main.unit_currency') }}
                            </div>
                        @endif
                    </div>
                </div>
                {{-- <div class="tourGrid_item_info">

                </div>
                <div class="tourGrid_item_location">
                    <i class="fa-solid fa-location-dot"></i>{{ $tour->tour_location_name }}
                </div> --}}
            </div>
        @endforeach
    </div>
    <div id="js_filterTour_hidden" style="display:none;">
        <!-- chứa phần tử tạm của filter => để hiệu chỉnh nth-child cho chính xác -->
    </div>
@endif