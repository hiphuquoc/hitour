<div id="modalHotelDescription" class="modalHotelDescription">
    <div class="modalHotelDescription_box">
        <div class="modalHotelDescription_box_head">
            <h2>Giới thiệu khách sạn {{ $item->name }}</h2>
            <!-- icon close -->
            <div class="modalHotelDescription_box_head_close" onClick="openCloseModal('modalHotelDescription');">
                <i class="fa-solid fa-xmark"></i>
            </div>
        </div>
        <div class="modalHotelDescription_box_body customScrollBar-y">
            {!! $content !!}
        </div>
    </div>
    <div class="modalHotelDescription_bg" onClick="openCloseModal('modalHotelDescription');"></div>
</div>