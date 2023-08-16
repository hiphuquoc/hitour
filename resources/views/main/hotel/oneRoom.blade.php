{{-- <div class="hotelRoom_body_item_image" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
    <div class="hotelRoom_body_item_image_top">
        @php
            $imageContent       = config('admin.images.default_750x460');
            $contentImage       = Storage::disk('gcs')->get($room->images[0]->image);
            if(!empty($contentImage)){
                $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(300, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode();
                $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
            }
        @endphp
        <img src="{{ $imageContent }}" alt="{{ $room->name }}" title="{{ $room->name }}" />
    </div>
    <div class="hotelRoom_body_item_image_bottom">
        @foreach($room->images as $image)
            @php
                if($loop->index==0) continue;
                if($loop->index==4) break;
                $imageContent       = config('admin.images.default_750x460');
                $contentImage       = Storage::disk('gcs')->get($image->image);
                if(!empty($contentImage)){
                    $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(100, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->encode();
                    $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                }
            @endphp
            <div class="hotelRoom_body_item_image_bottom_item">
                <img src="{{ $imageContent }}" alt="{{ $room->name }}" title="{{ $room->name }}" />
                @if($loop->index==3&&$room->images->count()>3) 
                    <div class="hotelRoom_body_item_image_bottom_item_number">
                        <span>+{{ ($room->images->count()-3) }}</span>
                        <i class="fa-solid fa-image"></i>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
<div class="hotelRoom_body_item_info">
    <div class="hotelRoom_body_item_info_name maxLine_2" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
        {{ $room->name }}
    </div>
    <div class="hotelRoom_body_item_info_sub">
        <div>
            Kích thước phòng: 
            <span class="highLight">{{ $room->size }} m<sup>2</sup></span>
        </div>
        <div>
            Tối đa: 
            <span class="highLight">
                @for($i=0;$i<$room->number_people;++$i)
                    <i class="fa-solid fa-person"></i>
                @endfor
            </span>
        </div>
    </div>
    <div class="hotelRoom_body_item_info_desc">
        {!! $room->condition ?? null !!}
    </div>
    <div class="hotelRoom_body_item_info_facilities">
        @foreach($room->facilities as $facility)
            @if(!empty($facility->infoHotelRoomFacility->icon)&&!empty($facility->infoHotelRoomFacility->name))
                <div class="hotelRoom_body_item_info_facilities_item">
                    {!! $facility->infoHotelRoomFacility->icon !!}
                    <span>{{ $facility->infoHotelRoomFacility->name }}</span>
                </div>
            @endif
        @endforeach
    </div>
</div>
<div class="hotelRoom_body_item_action">
    @if(!empty($room->price))
    <div class="hotelRoom_body_item_action_price">
        @if(!empty($room->sale_off)&&!empty($room->price_old))
            <div class="hotelRoom_body_item_action_price_old">
                <div class="hotelRoom_body_item_action_price_old_number">
                    {{ number_format($room->price_old) }} <sup>đ</sup>
                </div>
                <div class="hotelRoom_body_item_action_price_old_saleoff">
                    -{{ $room->sale_off }}%
                </div>
            </div>
        @endif
        <div class="hotelRoom_body_item_action_price_now">
            {{ number_format($room->price) }} <sup>đ</sup>
        </div>
    </div>
    @endif
    <div class="hotelRoom_body_item_action_button">đặt phòng</div>
</div> --}}

<div class="hotelList_item_gallery" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
    <div class="hotelList_item_gallery_top">
        @php
            $imageContent       = config('admin.images.default_750x460');
            $contentImage       = Storage::disk('gcs')->get($room->images[0]->image);
            if(!empty($contentImage)){
                $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(550, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode();
                $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
            }
        @endphp
        <img src="{{ $imageContent }}" alt="{{ $room->name }}" title="{{ $room->name }}" />
    </div>
    <div class="hotelList_item_gallery_bottom">
        @php
            $j = 0;
        @endphp
        @foreach($room->images as $image)
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
                <img src="{{ $imageContent }}" alt="{{ $room->name }}" title="{{ $room->name }}" />
            </div>
        @endforeach
    </div>
</div>
<div class="hotelList_item_info">
    <div class="hotelList_item_info_title" onClick="openCloseModal('js_loadHotelRoom_modal_{{ $room->id }}');">
        <h2>{{ $room->name }}</h2>
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
    <div class="hotelList_item_info_sub">
        <div>
            Kích thước phòng: 
            <span class="highLight">{{ $room->size }} m<sup>2</sup></span>
        </div>
        <div>
            Tối đa: 
            <span class="highLight">
                @for($i=0;$i<$room->number_people;++$i)
                    <i class="fa-solid fa-person"></i>
                @endfor
            </span>
        </div>
    </div>
    <div class="hotelList_item_info_condition">
        {!! $room->condition !!}
    </div>
    <div class="hotelList_item_info_facilities">
        @foreach($room->facilities as $facility)
            <div class="hotelList_item_info_facilities_item">
                {!! $facility->infoHotelRoomFacility->icon !!}
                <span class="maxLine_1">{{ $facility->infoHotelRoomFacility->name }}</span>
            </div>
            @php
                if($loop->index==8) break;
            @endphp
        @endforeach
        @if(($room->facilities->count() - 9) > 0)
            <div class="hotelList_item_info_facilities_item">+ {{ $room->facilities->count() - 9 }} tiện ích khác</div>
        @endif
    </div>
</div>
<div class="hotelList_item_action">
    <div class="hotelList_item_action_price">
        @if(!empty($room->sale_off))
            <div class="hotelList_item_action_price_old">
                <div class="hotelList_item_action_price_old_number">
                    {{ number_format($room->price_old) }} <sup>đ</sup>
                </div>
                <div class="hotelList_item_action_price_old_saleoff">
                    -{{ $room->sale_off }}%
                </div>
            </div>
        @endif
        <div class="hotelList_item_action_price_now">
            {{ number_format($room->price) }} <sup>đ</sup>
        </div>
    </div>
    <a href="/#" class="hotelList_item_action_button">chọn phòng</a>
</div>