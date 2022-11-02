<div class="serviceGrid">
    @if(!empty($list))
        @foreach($list as $air)
            <div class="serviceGrid_item">
                <a href="/{{ $air->seo->slug_full ?? null }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" class="serviceGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $air->seo->image_small ?? $air->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" />
                </a>
                <div class="serviceGrid_item_content">
                    <a href="/{{ $air->seo->slug_full ?? null }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null}}" class="serviceGrid_item_content_title maxLine_1">
                        @if(!empty($itemHeading)&&$itemHeading=='h3')
                            <i class="fa-solid fa-paper-plane"></i><h3>{{ $air->name ?? $air->seo->title ?? null }}</h3>
                        @else
                            <i class="fa-solid fa-paper-plane"></i><h2>{{ $air->name ?? $air->seo->title ?? null }}</h2>
                        @endif
                    </a>
                    @if(!empty($air->portDeparture->name)&&!empty($air->portLocation->name))
                        <a href="/{{ $air->seo->slug_full }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" class="serviceGrid_item_content_desc maxLine_4">
                            @if(!empty($itemHeading)&&$itemHeading=='h3')
                                <h4>{{ $air->portDeparture->name }} - {{ $air->portLocation->name }}</h4>
                            @else 
                                <h3>{{ $air->portDeparture->name }} - {{ $air->portLocation->name }}</h3>
                            @endif
                        </a>
                    @endif
                </div>
            </div>
            @php
                if(!empty($limit)&&($loop->index+1)==$limit) break;
            @endphp
        @endforeach
    @endif
</div>
@if(!empty($limit)&&$list->count()>$limit)
    <div class="viewMore">
        <a href="/{{ $link ?? null }}" title="Xem thêm"><i class="fa-solid fa-arrow-down-long"></i>Xem thêm</a>
    </div>
@endif