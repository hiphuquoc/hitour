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

        <div class="pageContent">
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
                            <div class="hotelInfoHead_left_type">
                                <div class="hotelInfoHead_left_type_name">
                                    {{ $item->type_name ?? null }}
                                </div>
                                <div class="hotelInfoHead_left_type_rating">
                                    @for($i=0;$i<$item->type_rating;++$i)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="hotelInfoHead_left_rating">
                                @php
                                    $rating         = 0;
                                    $ratingCount    = 0;
                                    if(!empty($item->comments)&&$item->comments->isNotEmpty()){
                                        $tmpTotal   = 0;
                                        foreach($item->comments as $comment){
                                            $tmpTotal += $comment->rating;
                                            $ratingCount += 1;
                                        }
                                        $rating     = number_format($tmpTotal/$ratingCount, 1);
                                    }
                                    $ratingText     = \App\Helpers\Rating::getTextRatingByRule($rating);
                                @endphp
                                @if(!empty($ratingCount))
                                    <div class="hotelInfoHead_left_rating_box">
                                        <img src="{{ Storage::url('images/svg/icon-comment.svg') }}" alt="Đánh giá khách sạn" title="Đánh giá khách sạn" />
                                        <div>{{ $rating }}</div>
                                    </div>
                                    <div class="hotelInfoHead_left_rating_text maxLine_1">
                                        {{ $ratingText }} (<span class="highLight">{{ $ratingCount }}</span> đánh giá)
                                    </div>
                                @else
                                    Chưa có đánh giá cho chỗ nghỉ này
                                @endif
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
                        <div id="js_hideElement" class="hotelInfoHead_right">
                            @php
                                $priceMin   = 100000000;
                                $saleOff    = 0;
                                $priceOld   = 0;
                                if(!empty($item->rooms)){
                                    foreach($item->rooms as $room){
                                        foreach($room->prices as $price){
                                            if(!empty($price->price)&&$priceMin>$price->price){
                                                $priceMin   = $price->price;
                                                $priceOld   = $price->price_old;
                                                $saleOff    = $price->sale_off;
                                            }
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
                    @if(!empty($item->images)&&$item->images->isNotEmpty())
                    <div id="anh-khach-san" class="hotelGallery" onClick="openCloseModalImage('modalImage')">
                        <div class="hotelGallery_left">
                            <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $item->images[0]->image }}" data-size="750" alt="{{ $item->name }}" title="{{ $item->name }}" />
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
                                @endphp
                                <div class="hotelGallery_right_item">
                                    <img src="{{ config('main.svg.loading_main') }}" data-google-cloud="{{ $image->image }}" data-size="300" alt="{{ $item->name }}" title="{{ $item->name }}" />
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
                    @endif
                </div>
            </div>

            <div class="sectionBox" style="background:#EDF2F7;">
                <div class="container">
                    <div class="hotelInfo">
                        @php
                            /* xây dựng lại mảng facilities */
                            $arrayFacilities = [];
                            foreach($item->facilities as $facility){
                                if(!empty($facility->infoFacility->type)) {
                                    /* trương hợp có facility của tripadvisor */
                                    $arrayFacilities['tripadvisor'][$facility->infoFacility->type][] = $facility->infoFacility->toArray();
                                }else {
                                    /* trương hợp không có facility của tripadvisor => dùng của traveloka */
                                    $arrayFacilities['traveloka'][$facility->infoFacility->category_name][] = $facility->infoFacility->toArray();
                                }
                            }
                        @endphp
                        <!-- facilities của tripadvisor -->
                        @if(!empty($arrayFacilities['tripadvisor']))
                            <div class="hotelInfo_facilities">
                                @foreach($arrayFacilities['tripadvisor'] as $key => $group)
                                    <div class="hotelInfo_facilities_item">
                                        @php
                                            switch ($key) {
                                                case 'hotel_info_feature':
                                                    $nameGroup = 'Tiện ích khách sạn';
                                                    break;
                                                case 'hotel_room_feature':
                                                    $nameGroup = 'Tiện ích trong phòng';
                                                    break;
                                                case 'hotel_room_type':
                                                    $nameGroup = 'Loại phòng';
                                                    break;
                                                default:
                                                    $nameGroup = $key;
                                                    break;
                                            }
                                        @endphp
                                        <div class="hotelInfo_facilities_item_title" onClick="openCloseModal('modalHotelFacilities');">
                                            {!! $nameGroup !!}
                                        </div>
                                        <div class="hotelInfo_facilities_item_list maxLine_2">
                                            @foreach($group as $facility)
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
                                        <div class="hotelInfo_facilities_item_button" onClick="openCloseModal('modalHotelFacilities');tabFacilities('{{ $key }}');">Hiển thị thêm</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <!-- facilities của traveloka -->
                        @if(!empty($arrayFacilities['traveloka']))  
                            <div class="hotelInfo_facilities2">
                                @foreach($arrayFacilities['traveloka'] as $key => $group)
                                    <div class="hotelInfo_facilities2_item">
                                        <div class="hotelInfo_facilities2_item_title">
                                            {!! $key !!}
                                        </div>
                                        <div class="hotelInfo_facilities2_item_list">
                                            @foreach($group as $facility)
                                                <div class="hotelInfo_facilities2_item_list_item">
                                                    @if(!empty($facility['icon']))
                                                        {!! $facility['icon'] !!}
                                                    @else
                                                        <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                                    @endif
                                                    <span class="maxLine_1">{{ $facility['name'] ?? null }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        
                        <div class="hotelInfo_desc">
                            {!! $content !!}
                            <div class="hotelInfo_desc_viewmore" onClick="openCloseModal('modalHotelDescription');">
                                <span>Xem thêm</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div id="chon-phong-khach-san" class="sectionBox" style="background:#EDF2F7 !important;">
                <div class="container">
                    <div class="hotelList">
                        @foreach($item->rooms as $room)
                            @foreach($room->prices as $price)
                                <div id="js_loadHotelPrice_{{ $price->id }}" class="hotelList_item">
                                    <!-- load Ajax chép đè -->
                                    <div style="width:100%;height:230px;display:flex;justify-content:center;align-items:center;">
                                        <img src="{{ config('main.svg.loading_main_nobg') }}" alt="tải thông tin phòng {{ $room->name }}" title="tải thông tin phòng {{ $room->name }}" style="width:230px;" />
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contents -->
            <div class="sectionBox withBorder">
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
                <div class="container" style="border-bottom:none !important;">
                    @include('main.snippets.faq', ['list' => $item->questions, 'title' => $item->name])
                </div>
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
        @foreach($room->prices as $price)
            <div id="js_loadHotelPrice_modal_{{ $price->id }}" class="modalHotelRoom">
                <!-- load Ajax -->
            </div>
        @endforeach
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
                @foreach ($room->prices as $price)
                    loadHotelPrice('{{ $price->id }}');
                @endforeach
            @endforeach
            
            /* ẩn button call book */
            var element = $('#js_hideElement');
            if (element.length>0){
                var isVisible = true; // Biến kiểm tra trạng thái hiển thị
                $(window).scroll(function() {
                    var elementTop = element.offset().top;
                    // Kiểm tra nếu phần tử nằm dưới vị trí cuộn trang 1000px và vẫn hiển thị
                    if (elementTop > 1000 && isVisible) {
                        element.stop().fadeOut(400);
                        isVisible = false; // Đánh dấu phần tử đã ẩn
                    }
                    // Kiểm tra nếu phần tử nằm trên vị trí cuộn trang 1000px và đã bị ẩn
                    if (elementTop <= 1000 && !isVisible) {
                        element.stop().fadeIn(400);
                        isVisible = true; // Đánh dấu phần tử đã hiển thị
                    }
                });
            }
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

        function loadHotelPrice(idHotelPrice){
            $.ajax({
                url         : "{{ route('main.hotel.loadHotelPrice') }}",
                type        : "GET",
                dataType    : "json",
                data        : { hotel_price_id : idHotelPrice }
            }).done(function(data){
                /* ghi nội dung room vào bảng */
                if(data.row!='') {
                    $('#js_loadHotelPrice_'+idHotelPrice).html(data.row);
                    /* load ảnh sau */
                    loadImageFromGoogleCloudInBox('js_loadHotelPrice_'+idHotelPrice);
                }else {
                    $('#js_loadHotelPrice_'+idHotelPrice).hidden();
                }
                /* ghi nội dung modal room */
                if(data.row!='') {
                    $('#js_loadHotelPrice_modal_'+idHotelPrice).html(data.modal);
                }else {
                    $('#js_loadHotelPrice_modal_'+idHotelPrice).hidden();
                }
            });
        }

        function loadImageFromGoogleCloudInBox(idBox){
            $('#'+idBox).find('img[data-google-cloud]').each(function(){
                const image = $(this);
                if (!image.hasClass('loaded')&&image.is(":visible")) {
                    loadImageFromGoogleCloud(image);
                    image.addClass('loaded');
                }
            })
        }

        function openCloseModalImage(idModal){
            const elementModal  = $('#'+idModal);
            const flag          = elementModal.css('display');

            if(!elementModal.hasClass('loadImage')){
                elementModal.addClass('loadImage');
                loadHotelImage();
            }
            
            /* tooggle */
            if(flag=='none'){
                elementModal.css('display', 'flex');
                $('#js_openCloseModal_blur').addClass('blurBackground');
                $('body').css('overflow', 'hidden');
            }else {
                elementModal.css('display', 'none');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
                $('body').css('overflow', 'unset');
            }
        }

        function openCloseModalRoom(idPrice){
            const element    = $('#js_loadHotelPrice_modal_'+idPrice);
            let displayE    = element.css('display');
            if(displayE=='none'){
                element.css('display', 'block');
                $('body').css('overflow', 'hidden');
                $('#js_openCloseModal_blur').addClass('blurBackground');
                /* set height modal height */
                // setHeightBoxByBox(idPrice);
                /* tải hình ảnh từ google cloud */ 
                loadImageFromGoogleCloudInBox('js_loadHotelPrice_modal_'+idPrice);
            }else {
                element.css('display', 'none');
                $('body').css('overflow', 'unset');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
            }
        }

        function setHeightBoxByBox(id){
            const valHeight = $('#js_setHeightBoxByBox_rule_'+id).outerHeight();
            $('#js_setHeightBoxByBox_element_'+id).css('height', valHeight+'px');
            console.log(valHeight);
        }

    </script>
@endpush