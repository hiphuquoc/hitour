<div class="callBookTour" style="margin-top:1.5rem;">
    <div class="callBookTour_price">
        <div>giá từ: <span>{{ !empty($item->price_show) ? number_format($item->price_show) : 'Liên hệ' }}{{ config('main.unit_currency') }}</span></div>
    </div>
    <div class="callBookTour_content">
        <b>Điểm Nổi Bật Chương Trình:</b>
        <div class="contentWithViewMore">
            <div id="js_viewMoreContent_content" class="contentWithViewMore_content" style="height:80px;">
                {!! $item->content->special_list ?? null !!}
            </div>
            <div class="contentWithViewMore_btn" onClick="viewMoreContent(this, 'js_viewMoreContent_content', 80);">
                Xem thêm<i class="fa-solid fa-arrow-right-long"></i>
            </div>
        </div>
    </div>
    @include('main.template.callbook', ['flagButton' => true])
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function viewMoreContent(btn, idContent, maxHeight){
            const heightNow     = $('#'+idContent).outerHeight();
            const heightFull    = $('#'+idContent+' ul').outerHeight();
            if(Math.floor(heightNow)<Math.floor(heightFull)){
                $('#'+idContent).css('height', heightFull);
                $(btn).html('Thu gọn<i class="fa-solid fa-arrow-left-long"></i>');
            }else {
                $('#'+idContent).css('height', maxHeight);
                $(btn).html('Xem thêm<i class="fa-solid fa-arrow-right-long"></i>');
            }
        }
    </script>
@endpush