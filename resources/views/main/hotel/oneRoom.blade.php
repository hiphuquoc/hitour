
<div class="hotelList_item_gallery" onClick="openCloseModal('js_loadHotelPrice_modal_{{ $price->id }}');">
    @if(!empty($price->room->images)&&$price->room->images->isNotEmpty())
        <div class="hotelList_item_gallery_top">
            @php
                $imageContent       = config('admin.images.default_750x460');
                $contentImage       = Storage::disk('gcs')->get($price->room->images[0]->image);
                if(!empty($contentImage)){
                    $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(550, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();
                    $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                }
            @endphp
            <img src="{{ $imageContent }}" alt="{{ $price->room->name }}" title="{{ $price->room->name }}" style="aspect-ratio:750/400;" />
        </div>
        <div class="hotelList_item_gallery_bottom">
            @php
                $j = 0;
            @endphp
            @foreach($price->room->images as $image)
                @php
                    ++$j;
                    if($j==1) continue;
                    if($j==5) break;
                    $imageContent       = config('admin.images.default_750x460');
                    $contentImage       = Storage::disk('gcs')->get($image->image);
                    if(!empty($contentImage)){
                        $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(550, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->encode();
                        $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                    }
                @endphp
                <div class="hotelList_item_gallery_bottom_item">
                    <img src="{{ $imageContent }}" alt="{{ $price->room->name }}" title="{{ $price->room->name }}" style="aspect-ratio:750/400;" />
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="hotelList_item_info">
    <div class="hotelList_item_info_title" onClick="openCloseModal('js_loadHotelPrice_modal_{{ $price->id }}');">
        <h2>
            {{ $price->room->name }}
            @if($price->breakfast==1||$price->given==1)
                @php
                    $tmp            = [];
                    if($price->breakfast==1) $tmp[] = 'Bữa sáng';
                    if($price->given==1) $tmp[] = 'Đưa đón';
                    $xhtmlInclude   = implode(' + ', $tmp);
                @endphp
                 ({{ $xhtmlInclude }})
            @endif
        </h2>
        
    </div>
    {{-- <div class="hotelList_item_info_rating">
        @php
            $rating         = $room->seo->rating_aggregate_star*2;
            $ratingCount    = $room->seo->rating_aggregate_count;
            if(!empty($room->comments)&&$room->comments->isNotEmpty()){
                $tmpTotal   = 0;
                $tmpCount   = 0;
                foreach($room->comments as $comment){
                    $tmpTotal += $comment->rating;
                    $tmpCount += 1;
                }
                $rating     = number_format($tmpTotal/$tmpCount, 1);
                $ratingCount = $tmpCount;
            }
            $rating         = $rating*2;
            $ratingText     = \App\Helpers\Rating::getTextRatingByRule($rating);
        @endphp
        <div class="hotelList_item_info_rating_number">
            <svg width="21" height="16" fill="none" style="margin-right:3px"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.825 8.157c.044-.13.084-.264.136-.394.31-.783.666-1.548 1.118-2.264.3-.475.606-.95.949-1.398.474-.616 1.005-1.19 1.635-1.665.27-.202.55-.393.827-.59.019-.015.034-.033.038-.08-.036.015-.078.025-.111.045-.506.349-1.024.68-1.51 1.052A15.241 15.241 0 006.627 4.98c-.408.47-.78.97-1.144 1.474-.182.249-.31.534-.474.818-1.096-1.015-2.385-1.199-3.844-.77.853-2.19 2.291-3.862 4.356-5.011 3.317-1.843 7.495-1.754 10.764.544 2.904 2.041 4.31 5.497 4.026 8.465-1.162-.748-2.38-.902-3.68-.314.05-.92-.099-1.798-.3-2.67a14.842 14.842 0 00-.834-2.567 16.416 16.416 0 00-1.225-2.345l-.054.028c.103.193.21.383.309.58.402.81.642 1.67.8 2.553.152.86.25 1.724.287 2.595.027.648.003 1.294-.094 1.936-.01.066-.018.133-.027.219-1.223-1.305-2.68-2.203-4.446-2.617a9.031 9.031 0 00-5.19.29l-.033-.03z" fill="#007bff"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M10 12.92h-.003c.31-1.315.623-2.627.93-3.943.011-.052-.015-.145-.052-.176a1.039 1.039 0 00-.815-.247c-.082.01-.124.046-.142.135-.044.216-.088.433-.138.646-.285 1.207-.57 2.413-.859 3.62l.006.001c-.31 1.314-.623 2.626-.93 3.942-.011.052.016.145.052.177.238.196.51.285.815.247.082-.01.125-.047.142-.134.044-.215.088-.433.138-.648.282-1.208.567-2.414.857-3.62z" fill="#007bff"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M15.983 19.203s-8.091-6.063-17.978-.467c0 0-.273.228.122.241 0 0 8.429-4.107 17.739.458-.002 0 .282.034.117-.232z" fill="#007bff"></path></svg>
            <span>{{ $rating }}</span> ({{ $ratingCount }})
        </div>
        <div class="hotelList_item_info_rating_text">
            {{ $ratingText }}
        </div>
    </div> --}}
    @if(!empty($price->breakfast)||!empty($price->given))
        @php
            $tmp = [];
            if($price->breakfast==1) $tmp[] = '<i class="fa-solid fa-check"></i>Bữa sáng ngon';
            if($price->given==1) $tmp[] = '<i class="fa-solid fa-check"></i>Đưa - đón khách sạn';
            $xhtmlInclude = implode(' ', $tmp);
        @endphp
        <div class="hotelList_item_info_include">
            Bao gồm: {!! $xhtmlInclude !!}
        </div>
    @endif
    <div class="hotelList_item_info_sub">
        <div> 
            <svg class="bk-icon -streamline-room_size" fill="#678" size="medium" width="16" height="16" viewBox="0 0 24 24"><path d="M3.75 23.25V7.5a.75.75 0 0 0-1.5 0v15.75a.75.75 0 0 0 1.5 0zM.22 21.53l2.25 2.25a.75.75 0 0 0 1.06 0l2.25-2.25a.75.75 0 1 0-1.06-1.06l-2.25 2.25h1.06l-2.25-2.25a.75.75 0 0 0-1.06 1.06zM5.78 9.22L3.53 6.97a.75.75 0 0 0-1.06 0L.22 9.22a.75.75 0 1 0 1.06 1.06l2.25-2.25H2.47l2.25 2.25a.75.75 0 1 0 1.06-1.06zM7.5 3.75h15.75a.75.75 0 0 0 0-1.5H7.5a.75.75 0 0 0 0 1.5zM9.22.22L6.97 2.47a.75.75 0 0 0 0 1.06l2.25 2.25a.75.75 0 1 0 1.06-1.06L8.03 2.47v1.06l2.25-2.25A.75.75 0 1 0 9.22.22zm12.31 5.56l2.25-2.25a.75.75 0 0 0 0-1.06L21.53.22a.75.75 0 1 0-1.06 1.06l2.25 2.25V2.47l-2.25 2.25a.75.75 0 0 0 1.06 1.06zM10.5 13.05v7.2a2.25 2.25 0 0 0 2.25 2.25h6A2.25 2.25 0 0 0 21 20.25v-7.2a.75.75 0 0 0-1.5 0v7.2a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1-.75-.75v-7.2a.75.75 0 0 0-1.5 0zm13.252 2.143l-6.497-5.85a2.25 2.25 0 0 0-3.01 0l-6.497 5.85a.75.75 0 0 0 1.004 1.114l6.497-5.85a.75.75 0 0 1 1.002 0l6.497 5.85a.75.75 0 0 0 1.004-1.114z"></path></svg> 
            <span>{{ $price->room->size }} m2</span>
        </div>
        <div>
            Đủ chỗ ngủ cho
            <span>
                @for($i=0;$i<$price->number_people;++$i)
                    <i class="fa-solid fa-person"></i>
                    @php
                        if($price->number_people>4){
                            echo 'x'.$price->number_people;
                            break;
                        }
                    @endphp
                @endfor
            </span>
        </div>
    </div>
    @if(!empty($price->beds)&&$price->beds->isNotEmpty())
        <div class="hotelList_item_info_bed">
        @foreach($price->beds as $bed)
            <span>{{ $bed->quantity }}</span> {{ $bed->infoHotelBed->name }}
            @if($loop->index!=($price->beds->count()-1))
                +
            @endif
        @endforeach
        </div>
    @endif
    <div class="hotelList_item_info_condition">
        {!! $price->description !!}
    </div>
    <div class="hotelList_item_info_facilities">
        @foreach($price->room->facilities as $facility)
            <div class="hotelList_item_info_facilities_item">
                {!! $facility->infoHotelRoomFacility->icon !!}
                <span class="maxLine_1">{{ $facility->infoHotelRoomFacility->name }}</span>
            </div>
            @php
                if($loop->index==8) break;
            @endphp
        @endforeach
        @if(($price->room->facilities->count() - 9) > 0)
            <div class="hotelList_item_info_facilities_item">+ {{ $price->room->facilities->count() - 9 }} tiện ích khác</div>
        @endif
    </div>
</div>
<div class="hotelList_item_action">
    <div class="hotelList_item_action_price">
        @if(!empty($price->sale_off))
            <div class="hotelList_item_action_price_old">
                <div class="hotelList_item_action_price_old_number">
                    {{ number_format($price->price_old) }} <sup>đ</sup>
                </div>
                <div class="hotelList_item_action_price_old_saleoff">
                    -{{ $price->sale_off }}%
                </div>
            </div>
        @endif
        <div class="hotelList_item_action_price_now">
            {{ number_format($price->price) }} <sup>đ</sup>
        </div>
    </div>
    <a href="{{ route('main.hotelBooking.form', ['hotel_price_id' => $price->id]) }}" class="hotelList_item_action_button">Đặt phòng</a>
</div>