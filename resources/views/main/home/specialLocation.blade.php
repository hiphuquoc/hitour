@if(!empty($specialLocations)&&$specialLocations->isNotEmpty())
    <div class="specialLocationBox">
        @foreach($specialLocations as $specialLocation)
            <div class="specialLocationBox_item">
                <a href="/{{ $specialLocation->seo->slug_full ?? null }}" title="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}" class="specialLocationBox_item_image">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $specialLocation->seo->image_small ?? $specialLocation->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}" title="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}" />
                    <h3>{{ $specialLocation->display_name ?? $specialLocation->name ?? $specialLocation->seo->title ?? null }}</h3>
                </a>
            </div>
        @endforeach
    </div>
@endif