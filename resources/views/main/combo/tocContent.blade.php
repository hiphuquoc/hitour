<div id="js_buildTocContentSidebar_idWrite" class="tocContentTour customScrollBar-y" style="margin-top:1.5rem;">
    {{-- <a href="#diem-noi-bat-chuong-trinh-tour" title="Điểm nổi bật Chương trình Tour" class="tocContentTour_item">
        <i class="fa-solid fa-award"></i>Điểm nổi bật CT Tour
    </a> --}}
    <a href="#bang-gia-combo" title="Bảng giá Tour" class="tocContentTour_item">
        <i class="fa-solid fa-hand-holding-dollar"></i>Bảng giá Tour
    </a>
    @foreach($item->contents as $content)
        @php
            $idHead = \App\Helpers\Charactor::convertStrToUrl($content->name);
        @endphp
        <a href="#{{ $idHead }}" title="{{ $content->name }}" class="tocContentTour_item">
            <i class="fa-solid fa-bookmark"></i>{{ $content->name }}
        </a>
    @endforeach
    @if(!empty($related)&&$related->isNotEmpty())
        <a href="#combo-lien-quan" title="Combo liên quan" class="tocContentTour_item">
            <i class="fa-solid fa-person-walking-luggage"></i>Combo liên quan
        </a>
    @endif
</div>

@push('scripts-custom')
    <script type="text/javascript">
        $(window).ready(function(){
            /* tính toán chiều cao sidebar */
            const heightW       = $(window).height();
            const heightBox     = $('#js_buildTocContentSidebar_idWrite').parent().outerHeight();
            const heightElemt   = $('#js_buildTocContentSidebar_idWrite').outerHeight();
            const height        = parseInt(heightW) - parseInt(heightBox - heightElemt);
            $('#js_buildTocContentSidebar_idWrite').css('max-height', 'calc('+height+'px - 3rem)');
        });
    </script>
@endpush