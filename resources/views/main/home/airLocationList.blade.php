@if(!empty($airLocations)&&$airLocations->isNotEmpty())
    <div class="airLocationList">
        @foreach($airLocations as $airLocation)
            <div class="airLocationList_item">
                <a href="/{{ $airLocation->seo->slug_full ?? null }}" title="{{ $airLocation->name ?? $airLocation->seo->title ?? $airLocation->seo->seo_title ?? null }}" class="airLocationList_item_title">
                    <h3>{{ $airLocation->name ?? $airLocation->seo->title ?? null }}</h3>
                </a>
            </div>
        @endforeach
    </div>
@endif