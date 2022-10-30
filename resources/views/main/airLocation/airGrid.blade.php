@if(!empty($list)&&$list->isNotEmpty())
    <div class="serviceGrid">
        @foreach($list as $air)
            <div class="serviceGrid_item">
                <a href="/{{ $air->seo->slug_full ?? null }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" class="serviceGrid_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $air->seo->image_small ?? $air->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" />
                </a>
                <a href="/{{ $air->seo->slug_full }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" class="serviceGrid_item_title maxLine_1">
                    <i class="fa-solid fa-paper-plane" style="margin-right:0.5rem;"></i><h2>{{ $air->name ?? $air->seo->title ?? null }}</h2>
                </a>
                <a href="/{{ $air->seo->slug_full }}" title="{{ $air->name ?? $air->seo->title ?? $air->seo->seo_title ?? null }}" class="serviceGrid_item_desc maxLine_4">
                    <h3>{{ $air->portDeparture->name }} - {{ $air->portLocation->name }}</h3>
                </a>
            </div>
        @endforeach
    </div>
@endif