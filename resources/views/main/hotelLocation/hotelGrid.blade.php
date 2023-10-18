<div id="js_filter_parent" class="hotelList">
    @foreach($list as $hotel)
        @if(!empty($hotel->images)&&$hotel->images->isNotEmpty()&&!empty($hotel->rooms)&&$hotel->rooms->isNotEmpty())
            @php
                $displayShow    = 'display:flex;';
                if($loop->index>9) $displayShow = 'display:none;';
            @endphp
            <div id="js_loadHotelInfo_{{ $hotel->id }}" class="hotelList_item" data-filter-type="{{ \App\Helpers\Charactor::convertStrToUrl($hotel->type_name) }}" style="{{ $displayShow }}">
                {{-- @include('main.hotelLocation.oneHotel', compact('hotel')) --}}
                <!-- load Ajax chép đè -->
                <div style="width:100%;height:230px;display:flex;justify-content:center;align-items:center;">
                    <img src="{{ config('main.svg.loading_main_nobg')}}" alt="tải thông tin phòng {{ $hotel->name }}" title="tải thông tin phòng {{ $hotel->name }}" style="width:230px;" />
                </div>
            </div>
        @endif
    @endforeach
</div>
@include('main.hotelLocation.loadingGridBox')