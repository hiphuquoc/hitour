@php
    $arrayImage = [
        '/storage/images/upload/guide_book_tour_1-type-manager-upload.webp',
        '/storage/images/upload/guide_book_tour_2-type-manager-upload.webp',
        '/storage/images/upload/guide_book_tour_1-type-manager-upload.webp',
        '/storage/images/upload/guide_book_tour_2-type-manager-upload.webp'
    ];
@endphp     
<div class="sectionBox hide-767">
    <div class="container">
        <h2 class="sectionBox_title">{{ $title ?? null }}</h2>
        <div class="guideBookBox">
            <div class="guideBookBox_image">
                <div class="galleryCustomBox">
                    <div id="js_setGuideBook_image" class="galleryCustomBox_box">
                        @foreach($arrayImage as $image)
                            <img src="{{ $image }}" alt="Hướng dẫn đặt Tour Phú Quốc" title="Hướng dẫn đặt Tour Phú Quốc" />
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
                    <div class="guideBookStepByStep_item active" onClick="setGuideBook(0);">
                        <div class="guideBookStepByStep_item_title">
                            <span class="guideBookStepByStep_item_title_no">1</span>Chọn các thông tin quan trọng của Tour
                        </div>
                        <div class="guideBookStepByStep_item_text">
                            <ul>
                                <li>Chọn Điểm đến và Tour Quý khách muốn đặt</li>
                                <li>Chọn Số lượng hành khách tham gia Tour</li>
                                <li>Chọn Ngày khởi hành</li>
                                <li>Click <strong>Đặt tour ngay</strong></li>
                            </ul>   
                        </div>
                    </div>
                    <div class="guideBookStepByStep_item active" onClick="setGuideBook(1);">
                        <div class="guideBookStepByStep_item_title">
                            <span class="guideBookStepByStep_item_title_no">2</span>Vui lòng chờ hệ thống tìm kiếm Tour
                        </div>
                        <div class="guideBookStepByStep_item_text">
                            <ul>
                                <li>Hệ thống chuyển hướng Quý khách sang trang đặt Tour chi tiết</li>
                                <li>Điền tiếp thông tin liên hệ của người đặt Tour</li>
                                <li>Chọn option của Tour</li>
                                <li>Click <strong>Xác nhận</strong></li>
                            </ul>   
                        </div>
                    </div>
                    <div class="guideBookStepByStep_item active" onClick="setGuideBook(2);">
                        <div class="guideBookStepByStep_item_title">
                            <span class="guideBookStepByStep_item_title_no">3</span>Thanh toán và hoàn tất
                        </div>
                        <div class="guideBookStepByStep_item_text">
                            <ul>
                                <li>Nhân viên Hitour sẽ liên hệ lại và gửi xác nhận</li>
                                <li>Quý khách vui lòng thanh toán theo hướng dẫn trong xác nhận</li>
                                <li>Và cung cấp thông tin từng hành khách</li>
                                <li>Hoàn tất các bước trên Quý khách sẽ nhận được một <strong>Phiếu xác nhận</strong></li>
                            </ul>   
                        </div>
                    </div>
                    <div class="guideBookStepByStep_item active" onClick="setGuideBook(3);">
                        <div class="guideBookStepByStep_item_title">
                            <span class="guideBookStepByStep_item_title_no">4</span>Sử dụng dịch vụ
                        </div>
                        <div class="guideBookStepByStep_item_text">
                            <ul>
                                <li><strong>Phiếu xác nhận</strong> có giá trị sử dụng dịch vụ của Hitour</li>
                                <li>Tùy thuộc vào mỗi chương trình Tour mà Quý khách nhận thêm các <strong>Vé dịch vụ</strong> đi kèm khác nhau (Ví dụ: vé tàu cao tốc, vé máy bay,...)</li>
                                <li>Trường hợp Quý khách làm mất Phiếu xác nhận và Vé dịch vụ có thể sử dụng <strong>số điện thoại đặt Tour</strong></li>
                            </ul>   
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>

@push('scripts-custom')
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
            let valueTransform = parseInt(valueSet*240);
            $('#js_setGuideBook_image').css('transform', 'translate3d(-'+valueTransform+'px, 0px, 0px)');
            /* set value input */
            hideShowButtonGallery(valueSet);
        }
    </script>
@endpush