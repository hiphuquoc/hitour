<div class="callBookTour_button">
    <div class="callBookTour_button_item hotline">
        @if(!empty($item->staffs)&&$item->staffs->isNotEmpty())
            <div style="display:flex;line-height:1.7;align-items:center;">
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
            <div style="display:flex;line-height:1.7;align-items:center;">
                <div>
                    Tư vấn:
                </div>
                <div style="margin-left:0.75rem;">
                    <div><span>{{ config('main.hotline') }}</span> (hotline)</div>
                </div>
            </div>
        @endif
    </div>
    @if(!empty($flagButton)&&$flagButton==true)
    <a href="{{ route('main.tourBooking.form', ['tour_location_id' => 12]) }}">
        <h2 class="callBookTour_button_item bookonline">
            {{ $button ?? 'Đặt ngay' }}
        </h2>
    </a>
    @endif
</div>