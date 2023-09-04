<div class="contentTour">
    <!-- gallery -->
    <div class="contentTour_item">
        @include('main.combo.gallery', compact('item'))
    </div>

    <!-- Bảng giá Tour -->
	@if($item->options->isNotEmpty())
        <div id="bang-gia-combo" class="contentTour_item">
            <div class="contentTour_item_title noBorder">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <h2>Bảng giá {{ $item->name ?? null }}</h2>
            </div>
            <div class="contentTour_item_text">
                <div class="comboTableBox">
                    @foreach($item->options as $option)
                        @php
                            $xhtmlLocation = [];
                            foreach($item->locations as $location){
                                $xhtmlLocation[] = $location->infoLocation->display_name;
                            }
                            $xhtmlLocation = implode(', ', $xhtmlLocation);
                        @endphp
                        <div class="comboTableBox_item">
                            <div class="comboTableBox_item">
                                <div class="comboTableBox_item_info">
                                    <div class="comboTableBox_item_info_title">Combo {{ $xhtmlLocation }} {{ $option->name ?? null }} đã gồm</div>
                                    <div class="comboTableBox_item_info_include">
                                        @if(!empty($option->prices[0]->include))
                                            {!! $option->prices[0]->include !!}
                                        @endif
                                        @if(!empty($option->hotelRoom))
                                            <div>{{ $option->hotelRoom->name }} - {{ $option->hotel->name }}<br/>
                                                <a href="/{{ $option->hotel->seo->slug_full }}">Xem chi tiết khách sạn</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="comboTableBox_item_info_note">
                                        Giá áp dụng từ <b>{{ !empty($option->prices[0]->date_start) ? date('d/m/Y', strtotime($option->prices[0]->date_start)) : '...' }}</b> - <b>{{ !empty($option->prices[0]->date_end) ? date('d/m/Y', strtotime($option->prices[0]->date_end)) : '...' }}</b>
                                    </div>
                                </div>
                                <div class="comboTableBox_item_action">
                                    @php
                                        /* lấy giá người lớn (giá cao nhất) */
                                        $priceShow  = 0;
                                        $priceOld   = 0;
                                        $saleOff    = 0;
                                        foreach($option->prices as $price){
                                            if($price->price>$priceShow) {
                                                $priceShow  = $price->price;
                                                $priceOld   = $price->price_old;
                                                $saleOff    = $price->sale_off;
                                            }
                                        }
                                    @endphp
                                    <div class="comboTableBox_item_action_price">
                                        @if(!empty($priceOld)&&$priceOld>$priceShow)
                                            <div class="comboTableBox_item_action_price_old">
                                                <div class="comboTableBox_item_action_price_old_number">
                                                    {{ number_format($priceOld) }} <sup>đ</sup>
                                                </div>
                                                <div class="comboTableBox_item_action_price_old_saleoff">
                                                    {{ $saleOff }}%
                                                </div>
                                            </div>
                                        @endif
                                        <div class="comboTableBox_item_action_price_now">
                                            {{ number_format($priceShow) }} <sup>đ</sup>
                                        </div>
                                    </div>
                                    <a href="{{ $linkBooking ?? '#' }}" class="comboTableBox_item_action_button">Đặt combo này</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    @endif
    <!-- Nội dung -->
    @foreach($item->contents as $content)
        @php
            $idHead = \App\Helpers\Charactor::convertStrToUrl($content->name);
        @endphp
        <div id="{{ $idHead }}" class="contentTour_item">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-bookmark"></i>
                <h2>{{ $content->name }}</h2>
            </div>
            <div class="contentTour_item_text">
                {!! $content->content !!}
            </div>
        </div>
    @endforeach
    <!-- Câu hỏi thường gặp -->
    @if(!empty($item->questions)&&$item->questions->isNotEmpty())
        <div id="cau-hoi-thuong-gap" class="contentTour_item">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-circle-question"></i>
                <h2>Câu hỏi thường gặp về {{ $item->name ?? null }}</h2>
            </div>
            <div class="contentTour_item_text">
                @include('main.snippets.faq', [
                    'list' => $item->questions, 
                    'title' => $item->name,
                    'hiddenTitle'   => true
                ])
            </div>
        </div>
    @endif

    <!-- Combo liên quan -->
    @if(!empty($related)&&$related->isNotEmpty())
        <div id="combo-lien-quan" class="contentTour_item notPrint">
            <div class="contentTour_item_title">
                <i class="fa-solid fa-person-walking-luggage"></i>
                <h2>Combo liên quan</h2>
            </div>
            <div class="contentTour_item_text">
                @include('main.combo.related', ['list' => $related])
            </div>
        </div>
    @endif
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function tabContent(elemtBtn){
            const idShow        = $(elemtBtn).data('tabcontent');
            const elementShow   = $('#'+idShow);
            /* ẩn tất cả phần tử con => hiện lại phần tử được chọn */
            elementShow.parent().children().each(function(){
                $(this).css('display', 'none');
            });
            elementShow.css('display', 'block');
            /* xóa active tất cả phần tử con button => active button vừa click */
            $(elemtBtn).parent().children().each(function(){
                $(this).removeClass('active');
            });
            $(elemtBtn).addClass('active');
        }
        function hideShowContent(elemtBtn){
            const elemtContent      = $(elemtBtn).next();
            const displayContent    = elemtContent.css('display');
            if(displayContent=='none'){
                elemtContent.css('display', 'block');
            }else {
                elemtContent.css('display', 'none');
            }
        }
    </script>
@endpush