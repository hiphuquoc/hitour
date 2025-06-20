@php
    $arrayData  = [
        0 => [
            'img'       => '/storage/images/upload/huong-dan-dat-ve-vui-choi-giai-tri-1-type-manager-upload.webp',
            'title'     => 'Chọn các thông tin quan trọng của vé',
            'content'   => '<ul>
                                <li>Chọn Điểm đến và Vé Quý khách muốn đặt</li>
                                <li>Chọn Ngày khởi hành</li>
                                <li>Click <strong>Đặt vé ngay</strong></li>
                            </ul>'
        ],
        1 => [
            'img'       => '/storage/images/upload/huong-dan-dat-ve-vui-choi-giai-tri-2-type-manager-upload.webp',
            'title'     => 'Vui lòng chờ hệ thống tìm kiếm Vé',
            'content'   => '<ul>
                                <li>Hệ thống chuyển hướng Quý khách sang trang đặt Vé chi tiết</li>
                                <li>Điền tiếp thông tin liên hệ của người đặt</li>
                                <li>Chọn option của Vé</li>
                                <li>Click <strong>Xác nhận</strong></li>
                            </ul>'
        ],
        2 => [
            'img'       => '/storage/images/upload/huong-dan-dat-tour-3-type-manager-upload.webp',
            'title'     => 'Thanh toán và hoàn tất',
            'content'   => '<ul>
                                <li>Nhân viên '.config('company.sortname').' sẽ liên hệ lại và gửi xác nhận chi tiết</li>
                                <li>Quý khách thanh toán theo hướng dẫn trong xác nhận</li>
                                <li>Hoàn tất các bước trên nhân viên sẽ gửi vé điện tử cho Quý khách</li>
                            </ul>'
        ],
        3 => [
            'img'       => '/storage/images/upload/huong-dan-dat-ve-vui-choi-giai-tri-4-type-manager-upload.webp',
            'title'     => 'Sử dụng dịch vụ',
            'content'   => '<ul>
                                <li>Trước ngày khởi hành Nhân viên sẽ liên hệ Quý khách để dặn dò chi tiết</li>
                                <li><strong>Vé điện tử</strong> có giá trị sử dụng dịch vụ tương ứng</li>
                            </ul>
                            <p>
                                <em>Ghi chú: Trường hợp làm lạc Vé điện tử Quý khách có thể dùng số điện thoại đặt vé để được hỗ trợ gửi lại vé mới.</em>
                            </p>'
        ]
    ]
@endphp    

{{-- <div class="sectionBox"> --}}
    <!-- Desktop --> 
    <div class="hide-767">
        <div class="container" style="margin: 1.5rem 0;">
            <div class="guideBookBox">
                <div class="guideBookBox_image">
                    <div class="galleryCustomBox">
                        <div id="js_setGuideBook_image" class="galleryCustomBox_box">
                            @foreach($arrayData as $item)
                                <img src="{{ $item['img'] }}" alt="{{ $title ?? null }}" title="{{ $title ?? null }}" />
                            @endforeach
                            <input type="hidden" id="js_prevNextGallery_input" value="0" />
                        </div>
                        <div class="galleryCustomBox_arrow">
                            <div class="privious" id="js_prevNextGallery_prev" onClick="prevNextGallery('previous');"></div>
                            <div class="next" id="js_prevNextGallery_next" onClick="prevNextGallery('next');"></div>
                        </div>
                    </div>
                </div>
                <div class="guideBookBox_content">
                    <div id="js_setGuideBook_box" class="guideBookStepByStep">
                        @foreach($arrayData as $item)
                            @php
                                $active = $loop->index==0 ? 'active' : null;
                            @endphp
                            <div class="guideBookStepByStep_item {{ $active }}" onClick="setGuideBook({{ $loop->index }});">
                                <div class="guideBookStepByStep_item_title">
                                    <span class="guideBookStepByStep_item_title_no">{{ $loop->index + 1 }}</span>{{ $item['title'] }}
                                </div>
                                <div class="guideBookStepByStep_item_text">
                                    {!! $item['content'] !!} 
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <!-- Mobile --> 
    <div class="show-767">
        <div class="container" style="margin-bottom:1.25rem;">
            <div class="guideBookBoxMobile">
                <div class="guideBookBoxMobile_image">
                    @foreach($arrayData as $image)
                        <img src="{{ $image['img'] }}" alt="{{ $title ?? null }}" title="{{ $title ?? null }}" />
                        @php
                            if($loop->index==2) break;
                        @endphp
                    @endforeach
                </div>
                <div id="js_showHideElement_box" class="guideBookBoxMobile_box">
                    <div class="guideBookBoxMobile_box_title">
                        <h3 class="maxLine_1">{{ $title ?? null }}</h3>
                        <div class="guideBookBoxMobile_box_title_close" onClick="showHideElement('js_showHideElement_box');"></div>
                    </div>
                    <div class="guideBookBoxMobile_box_content customScrollBar-y" style="height:calc(100% - 100px)">
                        @foreach($arrayData as $item)
                            <div class="guideBookBoxMobile_box_content_item">
                                <img src="{{ $item['img'] }}" alt="{{ $title ?? null }}" title="{{ $title ?? null }}" />
                                {!! $item['content'] !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="viewMore">
                <div onClick="showHideElement('js_showHideElement_box');"><i class="fa-solid fa-arrow-down-long"></i>Xem chi tiết</div>
            </div>
        </div>
    </div>
{{-- </div> --}}

{{-- @push('scripts-custom') --}}
    <script type="text/javascript">
        $(window).ready(function(){
            prevNextGallery();
        })

        function prevNextGallery(action = null){
            const valueNow      = $('#js_prevNextGallery_input').val();
            /* thực hiện */
            let valueNew    = 0;
            if(action=='previous'&&valueNow>0) {
                valueNew    = parseInt(valueNow) - 1;
            }else if(action=='next'&&valueNow<parseInt($('#js_setGuideBook_box').children().length)-1) {
                valueNew    = parseInt(valueNow) + 1;
            }
            hideShowButtonGallery(valueNew);
            setGuideBook(valueNew);
        }

        function hideShowButtonGallery(valueCompare){
            /* ẩn button privious nếu là phần tử đầu tiên */
            if(valueCompare==0){
                $('#js_prevNextGallery_prev').css('display', 'none');
            }else {
                $('#js_prevNextGallery_prev').css('display', 'block');
            }
            /* ẩn button next nếu là phần tử cuối cùng */
            if(valueCompare==parseInt($('#js_setGuideBook_box').children().length)-1){
                $('#js_prevNextGallery_next').css('display', 'none');
            }else {
                $('#js_prevNextGallery_next').css('display', 'block');
            }
            $('#js_prevNextGallery_input').val(valueCompare);
        }

        function setGuideBook(valueSet){
            /* active */
            $('#js_setGuideBook_box').children().each(function(){
                $(this).removeClass('active');
            });
            $('#js_setGuideBook_box').children().eq(valueSet).addClass('active');
            /* set transform */
            let valueTransform = parseInt(valueSet*245);
            $('#js_setGuideBook_image').css('transform', 'translate3d(-'+valueTransform+'px, 0px, 0px)');
            /* set value input */
            hideShowButtonGallery(valueSet);
        }

        function showHideElement(idElement){
            const displayElement = $('#'+idElement).css('display');
            if(displayElement=='none'){
                $('#'+idElement).css({
                    'display'   : 'block'
                });

            }else {
                $('#'+idElement).css({
                    'display'   : 'none'
                });
            }

            $('.guideBookBoxMobile_box_content').slick({
                infinite: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                dots: true
            });
        }
    </script>
{{-- @endpush --}}