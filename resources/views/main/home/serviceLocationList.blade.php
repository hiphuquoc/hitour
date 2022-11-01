@if(!empty($serviceLocations)&&$serviceLocations->isNotEmpty())
    <div class="serviceLocationList">
        @foreach($serviceLocations as $serviceLocation)
            <div class="serviceLocationList_item">
                <a href="/{{ $serviceLocation->seo->slug_full ?? null }}" title="{{ $serviceLocation->name ?? $serviceLocation->seo->title ?? $serviceLocation->seo->seo_title ?? null }}" class="serviceLocationList_item_title">
                    <h3>{{ $serviceLocation->name ?? $serviceLocation->seo->title ?? null }}</h3>
                </a>
                <ul>
                    @foreach($serviceLocation->services as $service)
                        <li>
                            <a href="/{{ $service->seo->slug_full ?? null }}" title="{{ $service->name ?? $service->seo->slug_full ?? null }}">
                                <h4>{{ $service->name ?? $service->seo->slug_full ?? null }}</h4>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
@endif