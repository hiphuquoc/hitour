@if(!empty($shipLocations)&&$shipLocations->isNotEmpty())
    <div class="airLocationList">
        @foreach($shipLocations as $shipLocation)
            <div class="airLocationList_item">
                <a href="/{{ $shipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->name ?? $shipLocation->seo->title ?? $shipLocation->seo->seo_title ?? null }}" class="airLocationList_item_title">
                    <h3>{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}</h3>
                </a>
            </div>
        @endforeach
    </div>
@endif