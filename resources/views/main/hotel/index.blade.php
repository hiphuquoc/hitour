@extends('main.layouts.main')
@push('head-custom')
<!-- ===== START:: SCHEMA ===== -->
@php
    $dataSchema = $item->seo ?? null;
@endphp

<!-- STRAT:: Title - Description - Social -->
@include('main.schema.social', ['data' => $dataSchema])
<!-- END:: Title - Description - Social -->

<!-- STRAT:: Organization Schema -->
@include('main.schema.organization')
<!-- END:: Organization Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.article', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.creativeworkseries', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Product Schema -->
@php
    $arrayPrice = [];
    if(!empty($item->services)&&$item->services->isNotEmpty()){
        foreach($item->services as $service) if(!empty($service->price_show)) $arrayPrice[] = $service->price_show;
    }
    $highPrice  = !empty($arrayPrice) ? max($arrayPrice) : 5000000;
    $lowPrice   = !empty($arrayPrice) ? min($arrayPrice) : 3000000;
@endphp
@include('main.schema.product', ['data' => $dataSchema, 'files' => $item->files, 'lowPrice' => $lowPrice, 'highPrice' => $highPrice])
<!-- END:: Product Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- STRAT:: FAQ Schema -->
@include('main.schema.faq', ['data' => $item->questions])
<!-- END:: FAQ Schema -->

@php
    $dataList       = null;
    if(!empty($item->services)&&$item->services->isNotEmpty()) $dataList = $item->services;
@endphp
<!-- STRAT:: Article Schema -->
@include('main.schema.itemlist', ['data' => $dataList])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

        <input type="hidden" id="hotel_info_id" name="hotel_info_id" value="{{ $item->id }}" />
        <!-- Giới thiệu Hotel du lịch -->
        <div class="sectionBox noBackground">
            <div class="container">
                <!-- title -->
                <h1 class="titlePage">
                    {{ $item->name }}
                </h1>
                <!-- rating & address -->
                <div class="hotelInfoHead">
                    <div class="hotelInfoHead_left">
                        <div class="hotelInfoHead_left_rating">
                            @php
                                $rating         = $item->seo->rating_aggregate_star*2;
                                $ratingCount    = $item->seo->rating_aggregate_count;
                                if(!empty($item->comments)&&$item->comments->isNotEmpty()){
                                    $tmpTotal   = 0;
                                    $tmpCount   = 0;
                                    foreach($item->comments as $comment){
                                        $tmpTotal += $comment->rating;
                                        $tmpCount += 1;
                                    }
                                    $rating     = number_format($tmpTotal/$tmpCount, 1);
                                    $ratingCount = $tmpCount;
                                }
                                $rating         = $rating*2;
                                $ratingText     = \App\Helpers\Rating::getTextRatingByRule($rating);
                            @endphp
                            <div class="hotelInfoHead_left_rating_box">
                                <svg width="21" height="16" fill="none" style="margin-right:3px"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.825 8.157c.044-.13.084-.264.136-.394.31-.783.666-1.548 1.118-2.264.3-.475.606-.95.949-1.398.474-.616 1.005-1.19 1.635-1.665.27-.202.55-.393.827-.59.019-.015.034-.033.038-.08-.036.015-.078.025-.111.045-.506.349-1.024.68-1.51 1.052A15.241 15.241 0 006.627 4.98c-.408.47-.78.97-1.144 1.474-.182.249-.31.534-.474.818-1.096-1.015-2.385-1.199-3.844-.77.853-2.19 2.291-3.862 4.356-5.011 3.317-1.843 7.495-1.754 10.764.544 2.904 2.041 4.31 5.497 4.026 8.465-1.162-.748-2.38-.902-3.68-.314.05-.92-.099-1.798-.3-2.67a14.842 14.842 0 00-.834-2.567 16.416 16.416 0 00-1.225-2.345l-.054.028c.103.193.21.383.309.58.402.81.642 1.67.8 2.553.152.86.25 1.724.287 2.595.027.648.003 1.294-.094 1.936-.01.066-.018.133-.027.219-1.223-1.305-2.68-2.203-4.446-2.617a9.031 9.031 0 00-5.19.29l-.033-.03z" fill="#007bff"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M10 12.92h-.003c.31-1.315.623-2.627.93-3.943.011-.052-.015-.145-.052-.176a1.039 1.039 0 00-.815-.247c-.082.01-.124.046-.142.135-.044.216-.088.433-.138.646-.285 1.207-.57 2.413-.859 3.62l.006.001c-.31 1.314-.623 2.626-.93 3.942-.011.052.016.145.052.177.238.196.51.285.815.247.082-.01.125-.047.142-.134.044-.215.088-.433.138-.648.282-1.208.567-2.414.857-3.62z" fill="#007bff"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M15.983 19.203s-8.091-6.063-17.978-.467c0 0-.273.228.122.241 0 0 8.429-4.107 17.739.458-.002 0 .282.034.117-.232z" fill="#007bff"></path></svg>
                                <span>{{ $rating }}</span>
                            </div>
                            <div class="hotelInfoHead_left_rating_text maxLine_1">
                                {{ $ratingText }} (<span class="highLight">{{ $ratingCount }}</span> đánh giá)
                            </div>
                        </div>
                        <div class="hotelInfoHead_left_address">
                            @php
                                if(!empty($item->address)){
                                    $address    = $item->address;
                                }else {
                                    $arrayTmp   = [];
                                    $arrayTmp[] = $item->location->province->province_name;
                                    $arrayTmp[] = $item->location->district->district_name;
                                    $address    = implode(', ', $arrayTmp);
                                }
                            @endphp
                            {{ $address }}
                        </div>
                    </div>
                    <div class="hotelInfoHead_right">
                        @php
                            $priceMin   = 100000000;
                            $saleOff    = 0;
                            $priceOld   = 0;
                            if(!empty($item->rooms)){
                                foreach($item->rooms as $room){
                                    if(!empty($room->price)&&$priceMin>$room->price){
                                        $priceMin   = $room->price;
                                        $priceOld   = $room->price_old;
                                        $saleOff    = $room->sale_off;
                                    }
                                }
                            }
                        @endphp
                        <div class="hotelInfoHead_right_price">
                            @if(!empty($saleOff))
                                <div class="hotelInfoHead_right_price_old">
                                    <div class="hotelInfoHead_right_price_old_number">
                                        {{ number_format($priceOld) }} <sup>đ</sup>
                                    </div>
                                    <div class="hotelInfoHead_right_price_old_saleoff">
                                        -{{ number_format($saleOff) }}%
                                    </div>
                                </div>
                            @endif
                            <div class="hotelInfoHead_right_price_now">
                                {{ number_format($priceMin) }} <sup>đ</sup>
                            </div>
                        </div>
                        <div class="hotelInfoHead_right_button">
                            <a href="#chon-phong-khach-san">Chọn phòng</a>
                        </div>
                    </div>
                </div>
                <!-- giới thiệu & gallery -->
                <div id="anh-khach-san" class="hotelGallery" onClick="openCloseModalImage('modalImage')">
                    <div class="hotelGallery_left">
                        @php
                            $imageContent       = config('admin.images.default_750x460');
                            $contentImage       = Storage::disk('gcs')->get($item->images[0]->image);
                            if(!empty($contentImage)){
                                $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(750, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                })->encode();
                                $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                            }
                        @endphp
                        <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $imageContent }}" alt="{{ $item->name }}" title="{{ $item->name }}" />
                        <span class="hotelGallery_left_count">
                            +{{ $item->images->count()-6 }}
                            <i class="fa-solid fa-image"></i>
                        </span>
                    </div>
                    <div class="hotelGallery_right">
                        @foreach($item->images as $image)
                            @php
                                if($loop->index==0) continue;
                                if($loop->index==7) break;
                                $imageContent       = config('admin.images.default_750x460');
                                $contentImage       = Storage::disk('gcs')->get($image->image);
                                if(!empty($contentImage)){
                                    $thumbnail      = \Intervention\Image\ImageManagerStatic::make($contentImage)->resize(300, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    })->encode();
                                    $imageContent   = 'data:image/jpeg;base64,'.base64_encode($thumbnail);
                                }
                            @endphp
                            <div class="hotelGallery_right_item">
                                <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $imageContent }}" alt="{{ $item->name }}" title="{{ $item->name }}" />
                                @if($loop->index==6)
                                    <span class="hotelGallery_right_item_count">
                                        +{{ $item->images->count()-6 }}
                                        <i class="fa-solid fa-image"></i>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <div class="sectionBox" style="background:#EDF2F7;">
            <div class="container">
                <div class="hotelInfo">
                    <div class="hotelInfo_facilities">
                        @php
                            /* xây dựng lại mảng facilities */
                            $arrayFacilities = [];
                            foreach($item->facilities as $facility){
                                $arrayFacilities[$facility->infoFacility->type][] = $facility->infoFacility->toArray();
                            }
                        @endphp
                        <div class="hotelInfo_facilities_item">

                            <div class="hotelInfo_facilities_item_title" onClick="openCloseModal('modalHotelFacilities');">
                                Tiện ích khách sạn
                            </div>
                            <div class="hotelInfo_facilities_item_list maxLine_2">
                                @foreach($arrayFacilities['hotel_info_feature'] as $facility)
                                    <div class="hotelInfo_facilities_item_list_item">
                                        @if(!empty($facility['icon']))
                                            {!! $facility['icon'] !!}
                                        @else
                                            <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                        @endif
                                        <span class="maxLine_1">{{ $facility['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hotelInfo_facilities_item_button" onClick="openCloseModal('modalHotelFacilities');tabFacilities('hotel_info_feature');">Hiển thị thêm</div>
                        </div>

                        <div class="hotelInfo_facilities_item">
                            <div class="hotelInfo_facilities_item_title" onClick="openCloseModal('modalHotelFacilities');">
                                Tiện ích trong phòng
                            </div>
                            <div class="hotelInfo_facilities_item_list">
                                @foreach($arrayFacilities['hotel_room_feature'] as $facility)
                                    <div class="hotelInfo_facilities_item_list_item">
                                        @if(!empty($facility['icon']))
                                            {!! $facility['icon'] !!}
                                        @else
                                            <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                        @endif
                                        <span class="maxLine_1">{{ $facility['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hotelInfo_facilities_item_button" onClick="openCloseModal('modalHotelFacilities');tabFacilities('hotel_room_feature');">Hiển thị thêm</div>
                        </div>

                        <div class="hotelInfo_facilities_item">
                            <div class="hotelInfo_facilities_item_title" onClick="openCloseModal('modalHotelFacilities');">
                                Loại phòng
                            </div>
                            <div class="hotelInfo_facilities_item_list">
                                @foreach($arrayFacilities['hotel_room_type'] as $facility)
                                    <div class="hotelInfo_facilities_item_list_item">
                                        @if(!empty($facility['icon']))
                                            {!! $facility['icon'] !!}
                                        @else
                                            <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                        @endif
                                        <span class="maxLine_1">{{ $facility['name'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="hotelInfo_facilities_item_button" onClick="openCloseModal('modalHotelFacilities');tabFacilities('hotel_room_type');">Hiển thị thêm</div>
                        </div>
                    </div>
                    <div class="hotelInfo_desc">
                        {!! $content !!}
                        <div class="hotelInfo_desc_viewmore" onClick="openCloseModal('modalHotelDescription');">
                            <span>Xem thêm</span>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div id="chon-phong-khach-san" class="sectionBox noBackground">
            <div class="hotelRoom">
                <div class="container">
                    <div class="hotelRoom_head">
                        <div class="hotelRoom_head_image">
                            Ảnh phòng
                        </div>
                        <div class="hotelRoom_head_info">
                            Thông tin
                        </div>
                        <div class="hotelRoom_head_action">
                            Giá (đ)
                        </div>
                    </div>
                    <div class="hotelRoom_body">
                        @foreach($item->rooms as $room)
                            <div id="js_loadHotelRoom_{{ $room->id }}" class="hotelRoom_body_item">
                                <!-- load Ajax chép đè -->
                                <img src="{{ config('main.svg.loading_main_nobg')}}" alt="tải thông tin phòng {{ $item->name }}" title="tải thông tin phòng {{ $item->name }}" style="margin:0 auto;width:250px;" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Contents -->
        <div class="sectionBox" style="background:#EDF2F7;">
            <div class="container">
                @foreach($item->contents as $c)
                    @if(trim($c->name)=='Chính sách khách sạn')
                        <!-- Định dạng content "chính sách khách sạn" được lấy riêng từ Mytour -->
                        <div class="hotelPolicy">
                            <div class="hotelPolicy_content">
                                {!! $c->content !!}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Câu hỏi thường gặp -->
        <div class="sectionBox withBorder">
            <div class="container">
                <h2 class="sectionBox_title">Câu hỏi thường gặp về {{ $item->name ?? null }}</h2>
                @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
            </div>
        </div>

@endsection
@push('modal')
    <!-- modal của ảnh -->
    @include('main.hotel.modalImage', ['images' => $item->images])
    <!-- modal của tiện nghi -->
    @include('main.hotel.modalFacilities', compact('arrayFacilities'))
    <!-- modal của tiện nghi -->
    @include('main.hotel.modalDescription', compact('content'))
    <!-- modal của phòng -->
    @foreach($item->rooms as $room)
        <div id="js_loadHotelRoom_modal_{{ $room->id }}" class="modalHotelRoom">
            <!-- load Ajax -->
        </div>
    @endforeach
@endpush
@push('bottom')
    <!-- button book tour mobile -->
    {{-- @php
        $linkBooking = route('main.serviceBooking.form', [
            'service_location_id'   => $item->id ?? 0
        ]);
    @endphp 
    <div class="show-990">
        <div class="callBookTourMobile">
            <a href="tel:{{ \App\Helpers\Charactor::removeSpecialCharacters(config('main.company.hotline')) }}" class="callBookTourMobile_phone maxLine_1">{{ config('main.company.hotline') }}</a>
            <a href="{{ $linkBooking ?? '/' }}" class="callBookTourMobile_button"><h2 style="margin:0;">Đặt Hotel</h2></a>
        </div>
    </div> --}}
@endpush
@push('scripts-custom')
    <script type="text/javascript">
        $('window').ready(function(){
            @foreach ($item->rooms as $room)
                loadHotelRoom('{{ $room->id }}');
            @endforeach
        })

        function showHideFullContent(elementButton, classCheck){
            const contentBox = $('#js_showHideFullContent_content');
            if(contentBox.hasClass(classCheck)){
                contentBox.removeClass(classCheck);
                $(elementButton).html('<i class="fa-solid fa-arrow-up-long"></i>Thu gọn');
            }else {
                contentBox.addClass(classCheck);
                $(elementButton).html('<i class="fa-solid fa-arrow-down-long"></i>Đọc thêm');
            }
        }

        function loadHotelRoom(idHotelRoom){
            $.ajax({
                url         : "{{ route('main.hotel.loadHotelRoom') }}",
                type        : "GET",
                dataType    : "json",
                data        : { hotel_room_id : idHotelRoom }
            }).done(function(data){
                /* ghi nội dung room vào bảng */
                if(data.row!='') {
                    $('#js_loadHotelRoom_'+idHotelRoom).html(data.row);
                }else {
                    $('#js_loadHotelRoom_'+idHotelRoom).hidden();
                }
                /* ghi nội dung modal room */
                if(data.row!='') {
                    $('#js_loadHotelRoom_modal_'+idHotelRoom).html(data.modal);
                }else {
                    $('#js_loadHotelRoom_modal_'+idHotelRoom).hidden();
                }
            });
        }

        function openCloseModalImage(idModal){
            const elementModal  = $('#'+idModal);
            const flag          = elementModal.css('display');
            $('a[href="#anh-khach-san"]').trigger('click');
            /* tooggle */
            if(flag=='none'){
                elementModal.css('display', 'flex');
                $('#js_openCloseModal_blur').addClass('blurBackground');
                // $('body').css('overflow', 'hidden');
            }else {
                elementModal.css('display', 'none');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
                // $('body').css('overflow', 'unset');
            }
        }

    </script>
@endpush