@if(!empty($shipLocations)&&$shipLocations->isNotEmpty())
    <div class="shipLocationList">
        @foreach($shipLocations as $shipLocation)
            <div class="shipLocationList_item">
                <a href="/{{ $shipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->name ?? $shipLocation->seo->title ?? $shipLocation->seo->seo_title ?? null }}" class="shipLocationList_item_title">
                    <h3>{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}</h3>
                </a>
                <ul>
                    @foreach($shipLocation->ships as $ship)
                        <li>
                            <a href="/{{ $ship->seo->slug_full ?? null }}" title="{{ $ship->name ?? $ship->seo->slug_full ?? null }}">
                                <h4>{{ $ship->name ?? $ship->seo->slug_full ?? null }}</h4>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
@endif