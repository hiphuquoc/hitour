<div id="js_autoLoadTocContentWithIcon_idWrite" class="tocContentTour customScrollBar-y" style="margin-top:1.5rem;">
    <a href="#diem-noi-bat-chuong-trinh-tour" title="Điểm nổi bật Chương trình Tour" class="tocContentTour_item">
        <i class="fa-solid fa-award"></i>Điểm nổi bật CT Tour
    </a>
    <a href="#bang-gia-tour" title="Bảng giá Tour" class="tocContentTour_item">
        <i class="fa-solid fa-hand-holding-dollar"></i>Bảng giá Tour
    </a>
    @if(!empty($item->timetables)&&$item->timetables->isNotEmpty())
        <a href="#lich-trinh-tour-du-lich" title="Lịch trình Tour" class="tocContentTour_item">
            <i class="fa-solid fa-bookmark"></i>Lịch trình Tour
        </a>
    @endif
    @if(!empty($item->content->policy_child))
        <a href="#chinh-sach-tre-em-tour" title="Chính sách trẻ em" class="tocContentTour_item">
            <i class="fa-solid fa-children"></i>Chính sách trẻ em
        </a>
    @endif
    @if(!empty($item->content->include)||!empty($item->content->not_include))
        <a href="#tour-bao-gom-va-khong-bao-gom" title="Tour bao gồm và không bao gồm" class="tocContentTour_item">
            <i class="fa-solid fa-list-check"></i>Tour bao gồm và không bao gồm
        </a>
    @endif
    @if(!empty($item->content->policy_cancel))
        <a href="#chinh-sach-huy-tour" title="Chính sách hủy tour" class="tocContentTour_item">
            <i class="fa-solid fa-xmark"></i>Chính sách hủy tour
        </a>
    @endif
    @if(!empty($item->content->note))
        <a href="#luu-y-khi-tham-gia-chuong-trinh-tour" title="Lưu ý" class="tocContentTour_item">
            <i class="fa-solid fa-circle-exclamation"></i>Lưu ý
        </a>
    @endif
    @if(!empty($item->content->menu))
        <a href="#thuc-don-theo-chuong-trinh-tour" title="Thực đơn" class="tocContentTour_item">
            <i class="fa-solid fa-utensils"></i>Thực đơn
        </a>
    @endif
    @if(!empty($item->content->hotel))
        <a href="#khach-san-tham-khao" title="Khách sạn tham khảo" class="tocContentTour_item">
            <i class="fa-solid fa-bed"></i>Khách sạn tham khảo
        </a>
    @endif
    @if(!empty($item->questions)&&$item->questions->isNotEmpty())
        <a href="#cau-hoi-thuong-gap" title="Câu hỏi thường gặp" class="tocContentTour_item">
            <i class="fa-solid fa-circle-question"></i>Câu hỏi thường gặp
        </a>
    @endif
    @if(!empty($related)&&$related->isNotEmpty())
        <a href="#tour-lien-quan" title="Tour liên quan" class="tocContentTour_item">
            <i class="fa-solid fa-person-walking-luggage"></i>Tour liên quan
        </a>
    @endif
    {{-- <a href="#" class="tocContentTour_item">
        <i class="fa-solid fa-images"></i>Ảnh đẹp Tour
    </a> --}}
</div>

@push('scripts-custom')
    <script type="text/javascript">
        $(window).ready(function(){
            /* tính toán chiều cao sidebar */
            const heightW       = $(window).height();
            const heightBox     = $('#js_autoLoadTocContentWithIcon_idWrite').parent().outerHeight();
            const heightElemt   = $('#js_autoLoadTocContentWithIcon_idWrite').outerHeight();
            const height        = parseInt(heightW) - parseInt(heightBox - heightElemt);
            $('#js_autoLoadTocContentWithIcon_idWrite').css('max-height', 'calc('+height+'px - 3rem)');
        });
    </script>
@endpush