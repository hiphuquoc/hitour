@if(!empty($list)&&$list->isNotEmpty())
    <div class="serviceGrid">
        @foreach($list as $air)
            <div class="serviceGrid_item">
                <a href="/{{ $air->seo->slug_full }}" class="serviceGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $air->seo->image_small ?? $air->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" />
                    {{-- @if(!empty($air->time_start)&&!empty($air->time_end))
                        <div class="serviceGrid_item_image_time">
                            {{ $air->time_start.' - '.$air->time_end }}
                        </div>
                    @endif --}}
                </a>
                <a href="/{{ $air->seo->slug_full }}" class="serviceGrid_item_title maxLine_1">
                    <i class="fa-solid fa-paper-plane" style="margin-right:0.5rem;"></i><h2>{{ $air->name ?? $air->seo->title ?? null }}</h2>
                </a>
                <a href="/{{ $air->seo->slug_full }}" class="serviceGrid_item_desc maxLine_4">
                    {{-- <h3>{{ $air->seo->description }}</h3> --}}
                    <h3>{{ $air->portDeparture->name }} - {{ $air->portLocation->name }}</h3>
                </a>
                {{-- <div class="column">
                    <div class="column_item">
                        <div class="serviceGrid_item_departureFrom maxLine_1">
                            Kiểm tra vé dễ dàng
                        </div>
                        <div class="serviceGrid_item_departureSchedule">
                            Đặt vé nhanh chóng
                        </div>
                    </div>
                    <div class="column_item serviceGrid_item_price">
                        {{ number_format($air->price_show).config('main.unit_currency') }}
                    </div>
                </div> --}}
                {{-- <div class="serviceGrid_item_info">

                </div> --}}
            </div>
        @endforeach
    </div>
@endif