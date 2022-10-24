<div class="serviceGrid">
    @if(!empty($list))
        @foreach($list as $air)
            <div class="serviceGrid_item">
                <a href="/{{ $air->seo->slug_full }}" class="serviceGrid_item_image">
                    <img src="{{ $air->seo->image }}" alt="{{ $air->name }}" title="{{ $air->name }}" />
                </a>
                <a href="/{{ $air->seo->slug_full }}" class="serviceGrid_item_title maxLine_1">
                    <i class="fa-solid fa-paper-plane"></i><h2>{{ $air->name }}</h2>
                </a>
                <a href="/{{ $air->seo->slug_full }}" class="serviceGrid_item_desc maxLine_4">
                    <h3>{{ $air->portDeparture->name }} - {{ $air->portLocation->name }}</h3>
                </a>
            </div>
            @php
                if(!empty($limit)&&($loop->index+1)==$limit) break;
            @endphp
        @endforeach
    @endif
</div>
@if(!empty($limit)&&$list->count()>$limit)
    <div class="viewMore">
        <a href="/{{ $link ?? null }}"><i class="fa-solid fa-arrow-down-long"></i>Xem thêm</a>
    </div>
@endif