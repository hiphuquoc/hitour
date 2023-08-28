<div class="contentTour">
    <!-- Bảng giá Tour -->
	@if($item->options->isNotEmpty())
        <div id="bang-gia-combo" class="contentTour_item">
            <div class="contentTour_item_title noBorder">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <h2>Bảng giá {{ $item->name ?? null }}</h2>
            </div>
            <div class="contentTour_item_text">
                <table class="tableContentBorder" style="margin-bottom:0;">
                    <thead>
                        <tr>
                            <th>Tùy chọn</th>
                            <th>Khởi hành</th>
                            <th>Giá áp dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->options as $option)
                            <tr>
                                <td>
                                    <h3 style="font-weight:700;font-size:1.05rem;">{{ $option['name'] }}</h3>
                                    <div style="font-size:0.95rem;">từ <b>{{ !empty($option->prices[0]->date_start) ? date('d/m/Y', strtotime($option->prices[0]->date_start)) : '...' }}</b> đến <b>{{ !empty($option->prices[0]->date_end) ? date('d/m/Y', strtotime($option->prices[0]->date_end)) : '...' }}</b></div>
                                </td>
                                <td>
                                    {{ $option->prices[0]->departure->display_name }}
                                </td>
                                <td style="vertical-align:top;">
                                    @foreach($option->prices as $price)
                                        <div><span style="font-weight:700;color:rgb(0, 90, 180);font-size:1.1rem;">{!! !empty($price->price) ? number_format($price->price).config('main.unit_currency') : '-' !!}</span> /{{ $price->apply_age ?? '-' }}</div>

                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
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