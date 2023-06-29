<div id="js_filterTour_parent" class="serviceGrid">
    @if(!empty($list)&&$list->isNotEmpty())
        @foreach($list as $combo)
            <div class="serviceGrid_item">
                <a href="/{{ $combo->infoCombo->seo->slug_full ?? null }}" title="{{ $combo->infoCombo->name ?? $combo->infoCombo->seo->title ?? $combo->infoCombo->seo->seo_title ?? null }}" class="serviceGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $combo->infoCombo->seo->image_small ?? $combo->infoCombo->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $combo->infoCombo->name ?? $combo->infoCombo->seo->title ?? $combo->infoCombo->seo->seo_title ?? null }}" title="{{ $combo->infoCombo->name ?? $combo->infoCombo->seo->title ?? $combo->infoCombo->seo->seo_title ?? null }}" />
                    @if(!empty($item->display_name))
                        <div class="serviceGrid_item_image_time">
                            <i class="fa-solid fa-location-dot"></i> {{ $item->display_name }}
                        </div>
                    @endif
                </a>
                <div class="serviceGrid_item_content">
                    <a href="/{{ $combo->infoCombo->seo->slug_full }}" title="{{ $combo->infoCombo->name ?? $combo->infoCombo->seo->title ?? $combo->infoCombo->seo->seo_title ?? null }}" class="serviceGrid_item_content_title maxLine_1">
                        <h2>{{ $combo->infoCombo->name ?? $combo->infoCombo->seo->title ?? null }}</h2>
                    </a>
                    <a href="/{{ $combo->infoCombo->seo->slug_full }}" title="{{ $combo->infoCombo->name ?? $combo->infoCombo->seo->title ?? $combo->infoCombo->seo->seo_title ?? null }}" class="serviceGrid_item_content_desc maxLine_4">
                        <h3>{{ $combo->infoCombo->description ?? $combo->infoCombo->seo->description ?? null }}</h3>
                    </a>
                    <div class="column" style="align-items:flex-end;">
                        <div class="column_item">
                            <div class="serviceGrid_item_content_departureFrom maxLine_1">
                                @php
                                    $xhtmlLocation = [];
                                    foreach($combo->infoCombo->options as $option){
                                        foreach($option->prices as $price){
                                            if(!in_array($price->departure->display_name, $xhtmlLocation)) $xhtmlLocation[] = $price->departure->display_name;
                                        }
                                    }
                                    $xhtmlLocation = implode(', ', $xhtmlLocation);
                                @endphp
                                Khởi hành tại {{ $xhtmlLocation }}
                            </div>
                            <div class="serviceGrid_item_content_departureSchedule">
                                Lịch {{ $combo->infoCombo->departure_schedule }}
                            </div>
                        </div>
                        <div class="column_item serviceGrid_item_content_price">
                            {{ number_format($combo->infoCombo->price_show).config('main.unit_currency') }}
                        </div>
                    </div>
                </div>
                {{-- <div class="serviceGrid_item_location">
                    <i class="fa-solid fa-location-dot"></i> Phú Quốc
                </div> --}}
            </div>
        @endforeach
    @else 
        <div style="color:rgb(0,123,255);">Hiện không có Combo {{ $item->display_name ?? null }} phù hợp trên hệ thống {{ config('main.name') }}!</div>
    @endif
</div>
<div id="js_filterTour_hidden" style="display:none;">
    <!-- chứa phần tử tạm của filter => để hiệu chỉnh nth-child cho chính xác -->
</div>
@include('main.tourLocation.loadingGridBox')