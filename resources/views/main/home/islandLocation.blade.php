@if(!empty($islandLocations)&&$islandLocations->isNotEmpty())
    <div class="islandLocationBox">
        @foreach($islandLocations as $islandLocation)
            <div class="islandLocationBox_item">
                <a href="/{{ $islandLocation->seo->slug_full ?? null }}" title="{{ $islandLocation->name ?? $islandLocation->seo->title ?? $islandLocation->seo->seo_title ?? null }}" class="islandLocationBox_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $islandLocation->seo->image_small ?? $islandLocation->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $islandLocation->name ?? $islandLocation->seo->title ?? $islandLocation->seo->seo_title ?? null }}" title="{{ $islandLocation->name ?? $islandLocation->seo->title ?? $islandLocation->seo->seo_title ?? null }}" />
                    <h3>{{ $islandLocation->display_name ?? $islandLocation->name ?? $islandLocation->seo->title ?? null }}</h3>
                </a>
            </div>
        @endforeach
    </div>
@endif