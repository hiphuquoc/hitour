@for($j=0;$j<2;++$j)
<div class="blogListLeftRight">
    <div class="blogListLeftRight_title">
        <a href="/"><h2>Đặc sản Phú Quốc</h2></a>
    </div>
    <div class="blogListLeftRight_box">
        <div class="blogListLeftRight_box_left">
            <a href="/" class="blogListLeftRight_box_left_image">
                <img src="/storage/images/upload/anh-dep-phu-quoc-083-750x460-type-manager-upload.webp" alt="" title="" />
            </a>
            <a href="/" class="blogListLeftRight_box_left_title">
                <h3 class="maxLine_2">Bài viết đặc sản Phú Quốc - Nấm Tràm Phú Quốc</h3>
            </a>
            <div class="blogListLeftRight_box_left_time">
                <i class="fa-regular fa-calendar-days"></i> 12:27, 26/12/2022
            </div>
            <div class="blogListLeftRight_box_left_desc maxLine_5">
                HDV đón quý khách tại khách sạn đến điểm tập trung và khởi hành đi Nam đảo Phú Quốc. Xe và HDV đưa quý khách đến cảng An Thới tận cùng phía Nam Phú Quốc bắt đầu chương trình tour.
            </div>
        </div>
        <div class="blogListLeftRight_box_right">
            @for($i=0;$i<5;++$i)
                <div class="blogListLeftRight_box_right_item">
                    <a href="/" class="blogListLeftRight_box_right_item_image">
                        <img src="/storage/images/upload/anh-dep-phu-quoc-083-750x460-type-manager-upload.webp" alt="" title="" />
                    </a>
                    <div class="blogListLeftRight_box_right_item_content">
                        <h3 class="maxLine_2">Bài viết đặc sản Phú Quốc - Nấm Tràm Phú Quốc</h3>
                        <div class="blogListLeftRight_box_right_item_content_time">
                            <i class="fa-regular fa-calendar-days"></i> 12:27, 26/12/2022
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    <div class="blogListLeftRight_footer">
        <div class="blogListLeftRight_footer_text">
            Chuyên mục Đặc sản Phú Quốc có <span class="highLight">20</span> bài
        </div>
        <div class="blogListLeftRight_footer_button"><i class="fa-solid fa-arrow-down-long"></i>Xem tất cả</div>
    </div>
</div>

@endfor