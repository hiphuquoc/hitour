@if(!empty($arrayFacilities))
    <div id="modalHotelFacilities" class="modalHotelFacilities">
        <div class="modalHotelFacilities_box">
            <div class="modalHotelFacilities_box_head">
                <h2>Chi tiết các tiện nghi</h2>
                <!-- icon close -->
                <div class="modalHotelFacilities_box_head_close" onClick="openCloseModal('modalHotelFacilities');">
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            <div class="modalHotelFacilities_box_body">
                <div class="hotelFacilitiesTab">
                    <div class="hotelFacilitiesTab_head">
                        @if(!empty($arrayFacilities['hotel_info_feature']))
                            <div class="hotelFacilitiesTab_head_item selected" for="hotel_info_feature" onClick="tabFacilities('hotel_info_feature');">
                                Tiện nghi khách sạn
                            </div>
                        @endif
                        @if(!empty($arrayFacilities['hotel_room_feature']))
                            <div class="hotelFacilitiesTab_head_item" for="hotel_room_feature" onClick="tabFacilities('hotel_room_feature');">
                                Tiện nghi phòng
                            </div>
                        @endif
                        @if(!empty($arrayFacilities['hotel_room_type']))
                            <div class="hotelFacilitiesTab_head_item" for="hotel_room_type" onClick="tabFacilities('hotel_room_type');">
                                Loại phòng
                            </div>
                        @endif
                    </div>
                    <div class="hotelFacilitiesTab_body customScrollBar-y">
                        @if(!empty($arrayFacilities['hotel_info_feature']))
                            <div id="hotel_info_feature" class="hotelFacilitiesTab_body_tab selected">
                                @foreach($arrayFacilities['hotel_info_feature'] as $facility)
                                    <div class="hotelFacilitiesTab_body_tab_item">
                                        @if(!empty($facility['icon']))
                                            {!! $facility['icon'] !!}
                                        @else
                                            <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                        @endif
                                        {{ $facility['name'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($arrayFacilities['hotel_room_feature']))
                            <div id="hotel_room_feature" class="hotelFacilitiesTab_body_tab">
                                @foreach($arrayFacilities['hotel_room_feature'] as $facility)
                                    <div class="hotelFacilitiesTab_body_tab_item">
                                        @if(!empty($facility['icon']))
                                            {!! $facility['icon'] !!}
                                        @else
                                            <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                        @endif
                                        {{ $facility['name'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($arrayFacilities['hotel_room_type']))
                            <div id="hotel_room_type" class="hotelFacilitiesTab_body_tab">
                                @foreach($arrayFacilities['hotel_room_type'] as $facility)
                                    <div class="hotelFacilitiesTab_body_tab_item">
                                        @if(!empty($facility['icon']))
                                            {!! $facility['icon'] !!}
                                        @else
                                            <svg viewBox="0 0 25 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.422 8.499l-5.427 1.875c-.776-2.73.113-5.116.792-6.2 1.11.324 3.46 1.607 4.635 4.325zm1.421-.491c-1.027-2.46-2.862-3.955-4.33-4.73 2.283-.461 4.023-.013 5.304.749a7.133 7.133 0 012.6 2.745l-3.574 1.236zm.495 1.416l4.345-1.502.723-.25-.264-.718c-.482-1.305-1.633-3.07-3.558-4.216-1.957-1.165-4.643-1.647-8.073-.461C6.08 3.462 4.196 5.522 3.274 7.66c-.909 2.108-.86 4.241-.523 5.595l.198.795.775-.268 8.002-2.765 2.962 8.597.186.54c-.104.08-.208.156-.315.23-.473.326-.902.523-1.379.523-.48 0-.896-.19-1.36-.51-.189-.13-.367-.27-.562-.42v-.001l-.003-.002-.15-.117a7.473 7.473 0 00-.948-.642l-.003-.001c-.386-.224-.7-.407-1.07-.5-.376-.095-.8-.095-1.402-.094h-.1c-.551 0-1.043.223-1.44.472-.402.25-.778.574-1.1.862l-.21.187c-.247.223-.456.41-.654.56a1.409 1.409 0 01-.33.203l-.006.003h.008v1.5c.502 0 .94-.29 1.231-.508.256-.193.529-.439.782-.666l.177-.16c.319-.284.613-.532.897-.71.288-.18.496-.243.645-.243.744 0 .965.006 1.136.049.157.04.28.11.822.422.2.116.407.268.648.454l.135.104c.197.154.418.325.645.482.572.395 1.292.776 2.212.776.923 0 1.657-.392 2.232-.79.232-.16.456-.334.655-.489l.087-.067.04-.031c.24-.186.439-.331.624-.437.555-.317.715-.395.877-.433.173-.04.363-.04.976-.04h.107c.111 0 .3.054.594.24.284.18.584.432.913.719l.144.126.004.004c.271.238.564.495.839.696.297.218.74.502 1.238.502v-1.5c.008 0 .008 0-.001-.003a1.49 1.49 0 01-.351-.21c-.216-.158-.449-.361-.72-.6h-.001a78.03 78.03 0 00-.166-.145c-.327-.286-.704-.606-1.095-.855-.382-.242-.864-.474-1.398-.474h-.197c-.493-.002-.875-.003-1.227.079-.397.093-.743.285-1.206.549l-.042-.123-2.962-8.598 2.496-.863.702-.23-.004-.011zM7.87 4.675c-.611 1.541-1.012 3.755-.294 6.19l-3.51 1.213a7.778 7.778 0 01.585-3.824c.55-1.275 1.533-2.566 3.219-3.579z"></path></svg>
                                        @endif
                                        {{ $facility['name'] }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modalHotelFacilities_bg" onClick="openCloseModal('modalHotelFacilities');"></div>
    </div>
    @push('scripts-custom')
        <script type="text/javascript">
            function tabFacilities(key){
                const button        = $(document).find('[for*='+key+']');
                // console.log(button);
                const tabContent    = $('#'+key);
                if(tabContent!=''){
                    /* xóa selected tất cả button */
                    button.parent().children().each(function() {
                        $(this).removeClass('selected');
                    });
                    /* selected lại button được click */
                    button.addClass('selected');
                    /* xóa selected tất cả tab content */
                    tabContent.parent().children().each(function() {
                        $(this).removeClass('selected');
                    });
                    /* selected lại tab content được click */
                    tabContent.addClass('selected');
                }
            }
        </script>
    @endpush
@endif