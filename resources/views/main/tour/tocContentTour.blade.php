<div class="tocContentTour" style="margin-top:1.5rem;">
    <a href="#diem-noi-bat-chuong-trinh-tour" class="tocContentTour_item">
        <i class="fa-solid fa-award"></i>Điểm nổi bật CT Tour
    </a>
    <a href="#bang-gia-tour" class="tocContentTour_item">
        <i class="fa-solid fa-hand-holding-dollar"></i>Bảng giá Tour
    </a>
    @if($item->timetables->isNotEmpty())
        <a href="#lich-trinh-tour-du-lich" class="tocContentTour_item">
            <i class="fa-solid fa-bookmark"></i>Lịch trình Tour
        </a>
    @endif
    @if(!empty($item->content->policy_child))
        <a href="#chinh-sach-tre-em-tour" class="tocContentTour_item">
            <i class="fa-solid fa-children"></i>Chính sách trẻ em
        </a>
    @endif
    @if(!empty($item->content->include)||!empty($item->content->not_include))
        <a href="#tour-bao-gom-va-khong-bao-gom" class="tocContentTour_item">
            <i class="fa-solid fa-list-check"></i>Tour bao gồm và không bao gồm
        </a>
    @endif
    @if(!empty($item->content->policy_cancel))
        <a href="#chinh-sach-huy-tour" class="tocContentTour_item">
            <i class="fa-solid fa-utensils"></i>Chính sách hủy tour
        </a>
    @endif
    @if(!empty($item->content->policy_cancel))
        <a href="#luu-y-khi-tham-gia-chuong-trinh-tour" class="tocContentTour_item">
            <i class="fa-solid fa-utensils"></i>Lưu ý
        </a>
    @endif
    @if(!empty($item->content->menu))
        <a href="#thuc-don-theo-chuong-trinh-tour" class="tocContentTour_item">
            <i class="fa-solid fa-utensils"></i>Thực đơn
        </a>
    @endif
    @if(!empty($item->content->hotel))
        <a href="#khach-san-tham-khao" class="tocContentTour_item">
            <i class="fa-solid fa-bed"></i>Khách sạn tham khảo
        </a>
    @endif
    {{-- <a href="#" class="tocContentTour_item">
        <i class="fa-solid fa-images"></i>Ảnh đẹp Tour
    </a> --}}
</div>