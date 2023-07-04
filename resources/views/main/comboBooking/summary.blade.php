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
<div class="shipBookingTotalBox_row">
    <div>Loại dịch vụ <span style="font-weight:700;"> {{ $data['options']['name'] }}</span></div>
    <div>Ngày sử dụng {{ date('d/m/Y', strtotime($data['date'])) }}</div>
    <table class="noResponsive">
        <tbody>
            @php
                $total                  = 0;
            @endphp 
            @foreach($data['quantity'] as $idPrice => $quantity)
                @foreach($data['options']['prices'] as $price)
                    @if($price['id']==$idPrice)
                        <tr>
                            <td>{{ $price['apply_age'] }}</td>
                            <td style="text-align:right;">{{ $quantity }} * {{ number_format($price['price']) }}</td>
                            <td style="text-align:right;">{{ number_format($quantity*$price['price']) }}</td>
                        </tr>
                        @php
                            $total  += $quantity*$price['price'];
                        @endphp
                    @endif
                @endforeach
            @endforeach
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
@if(!empty($data['note_customer']))
    <div class="shipBookingTotalBox_row">
        <div>Ghi chú của bạn: {{ $data['note_customer'] }}</div>
    </div>
@endif