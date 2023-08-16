<div class="hotelList">
    @foreach($list as $hotel)
        @php
            $urlHotel = $hotel->seo->slug_full;
        @endphp
        <div class="hotelList_item">
            <a href="/{{ $urlHotel }}" class="hotelList_item_gallery">
                <div class="hotelList_item_gallery_top">
                    @php
                        $imageContent       = config('admin.images.default_750x460');
                        $contentImage       = Storage::disk('gcs')->get($hotel->images[0]->image);
                        if(!empty($contentImage)){
                            $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(550, null, function ($constraint) {
                                $constraint->aspectRatio();
                            })->encode();
                            $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                        }
                    @endphp
                    <img src="{{ $imageContent }}" alt="{{ $hotel->name }}" title="{{ $hotel->name }}" />
                </div>
                <div class="hotelList_item_gallery_bottom">
                    @php
                        $j = 0;
                    @endphp
                    @foreach($hotel->images as $image)
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
                            <img src="{{ $imageContent }}" alt="{{ $hotel->name }}" title="{{ $hotel->name }}" />
                        </div>
                    @endforeach
                </div>
            </a>
            <div class="hotelList_item_info">
                <div class="hotelList_item_info_title">
                    <a href="/{{ $urlHotel }}"><h2>{{ $hotel->name }}</h2></a>
                </div>
                <div class="hotelList_item_info_rating">
                    @php
                        $rating         = $hotel->seo->rating_aggregate_star*2;
                        $ratingCount    = $hotel->seo->rating_aggregate_count;
                        if(!empty($hotel->comments)&&$hotel->comments->isNotEmpty()){
                            $tmpTotal   = 0;
                            $tmpCount   = 0;
                            foreach($hotel->comments as $comment){
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
                        <img src="{{ Storage::url('images/svg/icon-comment.svg') }}" alt="Đánh giá khách sạn" title="Đánh giá khách sạn" />
                        <span>{{ $rating }}</span> ({{ $ratingCount }})
                    </div>
                    <div class="hotelList_item_info_rating_text">
                        {{ $ratingText }}
                    </div>
                </div>
                <div class="hotelList_item_info_type">
                    <div class="hotelList_item_info_type_text">
                        <i class="fa-solid fa-hotel"></i>{{ $hotel->type_name }}
                    </div>
                    @if(!empty($hotel->type_rating))
                        <div class="hotelList_item_info_type_icon">
                            @for($i=0;$i<$hotel->type_rating;++$i)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                    @endif
                </div>
                <div class="hotelList_item_info_address">
                    {{ $hotel->address }}
                </div>
                <div class="hotelList_item_info_facilities">
                    @foreach($hotel->facilities as $facility)
                        <div class="hotelList_item_info_facilities_item">
                            {!! $facility->infoFacility->icon !!}
                            <span class="maxLine_1">{{ $facility->infoFacility->name }}</span>
                        </div>
                        @php
                            if($loop->index==8) break;
                        @endphp
                    @endforeach
                    <div class="hotelList_item_info_facilities_item">+ {{ $hotel->facilities->count() - 9 }} tiện ích khác</div>
                </div>
            </div>
            <div class="hotelList_item_action">
                @php
                    $priceMin   = 100000000;
                    $saleOff    = 0;
                    $priceOld   = 0;
                    if(!empty($hotel->rooms)){
                        foreach($hotel->rooms as $room){
                            if(!empty($room->price)&&$priceMin>$room->price){
                                $priceMin   = $room->price;
                                $priceOld   = $room->price_old;
                                $saleOff    = $room->sale_off;
                            }
                        }
                    }
                @endphp
                <div class="hotelList_item_action_price">
                    @if(!empty($saleOff))
                        <div class="hotelList_item_action_price_old">
                            <div class="hotelList_item_action_price_old_number">
                                {{ number_format($priceOld) }} <sup>đ</sup>
                            </div>
                            <div class="hotelList_item_action_price_old_saleoff">
                                -{{ $saleOff }}%
                            </div>
                        </div>
                    @endif
                    <div class="hotelList_item_action_price_now">
                        {{ number_format($priceMin) }} <sup>đ</sup>
                    </div>
                </div>
                <a href="/{{ $urlHotel }}#chon-phong-khach-san" class="hotelList_item_action_button">chọn phòng</a>
            </div>
        </div>
    @endforeach
</div>
@include('main.hotelLocation.loadingGridBox')