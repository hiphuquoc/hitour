<div class="callBookTour">
    <div class="callBookTour_price">
        <div>giá từ: <span>{{ !empty($item->price_show) ? number_format($item->price_show) : 'Liên hệ' }}<sup>đ</sup></span></div>
    </div>
    <div class="callBookTour_content">
        <div>Điểm Nổi Bật Chương Trình:</div>
        {!! $item->content->special_list ?? null !!}
    </div>
    <div class="callBookTour_button">
        <div class="callBookTour_button_item hotline">
            @if($item->staffs->isNotEmpty())
                <div style="display:flex;">
                    <div>
                        Tư vấn:
                    </div>
                    <div style="margin-left:0.5rem;">
                        @foreach($item->staffs as $staff)
                            <div><span>{{ $staff->infoStaff->phone }}</span> ({{ $staff->infoStaff->prefix_name }} {{ $staff->infoStaff->lastname }})</div>
                        @endforeach
                    </div>
                </div>
            @else
                <div style="display:flex;">
                    <div>
                        Tư vấn:
                    </div>
                    <div style="margin-left:0.5rem;">
                        <div><span>{{ config('main.hotline') }}</span> (hotline)</div>
                    </div>
                </div>
            @endif
        </div>
        <div class="callBookTour_button_item bookonline">
            Đặt Tour
        </div>
    </div>
</div>