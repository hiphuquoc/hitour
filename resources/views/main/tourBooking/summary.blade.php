@php
    $total          = 0;
@endphp
@if(!empty($data['name'])&&!empty($data['phone']))
    <div class="shipBookingTotalBox_row">
        <div>{{ $data['name'] }} - {{ $data['phone'] }} @if(!empty($data['zalo'])&&$data['zalo']==$data['phone']) (Zalo)@endif</div>
        @if(!empty($data['email']))
            <div>@if(!empty($data['zalo'])&&$data['zalo']!=$data['phone']) Zalo: {{ $data['zalo'] }} - @endif{{ $data['email'] }}</div>
        @endif
    </div>
@endif
@php
    dd($data);
@endphp
<div class="shipBookingTotalBox_row">
    <div style="font-weight:700;">Tour Phú Quốc 2 ngày 1 đêm</div>
    <div>13/11/2022 - 15/11/2022</div>
    <div>Đón cảng tàu/ sân bay</div>
    <div style="font-weight:700;">Khách sạn 2*</div>
    <table class="noResponsive">
        <tbody>
            <tr>
                <td>Người lớn</td>
                <td style="text-align:right;">1 * 560,000</td>
                <td style="text-align:right;">560,000</td>
            </tr>
        </tbody>
    </table>
</div>
@if(!empty($total))
    <div class="shipBookingTotalBox_row">
        <table class="noResponsive">
            <tbody>
                <tr>
                    <td colspan="2">Tổng</td>
                    <td style="text-align:right;"><span style="font-weight:700;color:#E74C3C;letter-spacing:0.3px;">{{ number_format($total) }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endif