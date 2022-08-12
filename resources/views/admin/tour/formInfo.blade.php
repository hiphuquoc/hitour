<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label inputRequired" for="special_content">Điểm nổi bật Tour (dạng giới thiệu)</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalSpecialContent">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="special_content"  name="special_content" rows="5" required>{{ old('special_content') ?? $item->content->special_content ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label inputRequired" for="special_list">Điểm nổi bật Tour (dạng danh sách)</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalSpecialList">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="special_list"  name="special_list" rows="5" required>{{ old('special_list') ?? $item->content->special_list ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label inputRequired" for="include">Tour bao gồm</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalInclude">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="include"  name="include" rows="5" required>{{ old('include') ?? $item->content->include ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label inputRequired" for="not_include">Tour chưa bao gồm</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalNoInclude">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="not_include"  name="not_include" rows="5" required>{{ old('not_include') ?? $item->content->not_include ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label inputRequired" for="policy_child">Chính sách trẻ em</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalPolicyChild">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="policy_child"  name="policy_child" rows="5" required>{{ old('policy_child') ?? $item->content->policy_child ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label" for="menu">Thực đơn</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalMenu">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="menu"  name="menu" rows="5">{{ old('menu') ?? $item->content->menu ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label" for="hotel">Khách sạn</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalHotel">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="hotel"  name="hotel" rows="5">{{ old('hotel') ?? $item->content->hotel ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox" style="justify-content:space-between;">
                <label class="form-label" for="policy_cancel">Chính sách hủy Tour</label>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalPolicyCancel">Lấy mẫu</a>
            </div>
            <textarea class="form-control" id="policy_cancel"  name="policy_cancel" rows="5">{{ old('policy_cancel') ?? $item->content->policy_cancel ?? '' }}</textarea>
        </div>
    </div>
</div>

<!-- ===== START:: Modal Điểm nổi bật tour giới thiệu ===== -->
<div class="modal fade" id="modalSpecialContent" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">

                <textarea class="exampleContentBox" rows="1">
                    <p>Nội dung</p>
                </textarea>

            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal Điểm nổi bật tour giới thiệu ===== -->

<!-- ===== START:: Modal Điểm nổi bật tour danh sách ===== -->
<div class="modal fade" id="modalSpecialList" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="5">
                    <ul>
                        <li>Điểm nổi bật 1</li>
                        <li>Điểm nổi bật 2</li>
                        <li>Điểm nổi bật 3</li>
                    </ul>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal Điểm nổi bật tour danh sách ===== -->

<!-- ===== START:: Modal Tour bao gồm ===== -->
<div class="modal fade" id="modalInclude" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="7">
                    <p>Phương tiện: Xe du lịch đời mới 16 chỗ,...</p>
                    <ul>
                        <li>Xe 16 chỗ: Ford Transit,...</li>
                        <li>Xe 29 chỗ: Samco, Thaco,...</li>
                        <li>Xe 45 chỗ: Universe,...</li>
                    </ul>
                    <p>Tàu câu cá, phao cứu hộ,...</p>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal Tour bao gồm ===== -->

<!-- ===== START:: Modal Tour chưa bao gồm ===== -->
<div class="modal fade" id="modalNoInclude" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="7">
                    <p>Vé máy bay hoặc Vé tàu cao...</p>
                    <p>Vé Vinwonders Phú Quốc</p>
                    <ul>
                        <li>Khách hàng ... 880,000<sup>đ</sup></li>
                        <li>Khách hàng ... 660,000<sup>đ</sup></li>
                        <li>Khách hàng ... Miễn phí</li>
                    </ul>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal Tour chưa bao gồm ===== -->

<!-- ===== START:: Modal chính sách trẻ em ===== -->
<div class="modal fade" id="modalPolicyChild" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="6">
                    <ul>
                        <li>Trẻ em dưới 04 tuổi: ...</li>
                        <li>Trẻ em từ 05 – 09 tuổi: ...</li>
                        <li>Trẻ em từ 10 tuổi trở lên: ...</li>
                    </ul>
                    <p>Ghi chú: Khách đi tour kèm 2 trẻ em ...</p>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal chính sách trẻ em ===== -->

<!-- ===== START:: Modal thực đơn ===== -->
<div class="modal fade" id="modalMenu" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="18">
                    <div class="menuTour_item">
                        <div class="menuTour_item_title">
                            <div class="menuTour_item_mainTitle">
                                Thực đơn 1 (150,000<sup>đ</sup>)
                            </div>
                            <div class="menuTour_item_subTitle">
                                Nhà hàng Trùng Dương
                            </div>
                        </div>
                        <div class="menuTour_item_content">
                            <ul>
                                <li>Gỏi cá trích</li>
                                <li>Mực chiên mắm</li>
                                <li>Hải sản xào thập cẩm</li>
                                <li>...</li>
                            </ul>
                        </div>
                    </div>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal thực đơn ===== -->

<!-- ===== START:: Modal khách sạn ===== -->
<div class="modal fade" id="modalHotel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="10">
                    <div class="hotelTour_item">
                        <div class="hotelTour_item_title">
                            <h3>Thiên Thanh (Resort 5*)</h3>
                        </div>
                        <div class="hotelTour_item_image">
                            <img src="" alt="" title="">
                            <img src="" alt="" title="">
                            <img src="" alt="" title="">
                        </div>
                    </div>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal khách sạn ===== -->

<!-- ===== START:: Modal chính sách hủy tour ===== -->
<div class="modal fade" id="modalPolicyCancel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div id="js_loadFormOption_body" class="modal-body">
                <textarea class="exampleContentBox" rows="5">
                    <ul>
                        <li>Trong vòng 05 (năm) ngày: ...</li>
                        <li>Trong vòng 03 (ba) ngày: ...</li>
                        <li>Trong vòng 24 giờ trước ngày: ...</li>
                    </ul>
                </textarea>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal chính sách hủy tour ===== -->