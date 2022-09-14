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
@if(!empty($data['name_dp1']))
    <div class="shipBookingTotalBox_row">
        <div style="font-weight:700;"><i class="fa-solid fa-ship"></i>{{ $data['name_dp1'] }}  ({{ date('d/m/Y', strtotime($data['date_1'])) }})</div>
        @if(!empty($data['dp1']))
            @php
                $tmp = [];
                $tmp = explode('|', $data['dp1']);
            @endphp
            <div><span class="highLight">{{ $tmp[1] ?? null }} - {{ $tmp[2] ?? null }}</span> tàu {{ $tmp[4] }} ({{ !empty($tmp[3]) ? strtoupper($tmp[3]) : null }})</div>
            @if(!empty($data['quantity_adult_1'])||!empty($data['quantity_child_1'])||!empty($data['quantity_old_1']))
                @php
                    $infoShipPrice  = [];
                    $infoShipPrice  = \App\Models\ShipPrice::find($tmp[0]);
                @endphp
                @if(!empty($infoShipPrice))
                    @php
                        $priceAdultReal = $tmp[3]=='vip' ? $infoShipPrice->price_vip : $infoShipPrice->price_adult;
                        $priceChildReal = $tmp[3]=='vip' ? $infoShipPrice->price_vip : $infoShipPrice->price_child;
                        $priceOldReal   = $tmp[3]=='vip' ? $infoShipPrice->price_vip : $infoShipPrice->price_old;
                    @endphp
                    <table>
                        <tbody>
                            @if(!empty($data['quantity_adult_1']))
                                <tr>
                                    <td>Người lớn</td>
                                    <td style="text-align:right;">{{ $data['quantity_adult_1'] }} * {{ number_format($priceAdultReal) }}</td>
                                    <td style="text-align:right;">{{ number_format($data['quantity_adult_1']*$priceAdultReal) }}</td>
                                </tr>
                                @php
                                    $total      += $data['quantity_adult_1']*$priceAdultReal;
                                @endphp
                            @endif
                            @if(!empty($data['quantity_child_1']))
                                <tr>
                                    <td>Trẻ em</td>
                                    <td style="text-align:right;">{{ $data['quantity_child_1'] }} * {{ number_format($priceChildReal) }}</td>
                                    <td style="text-align:right;">{{ number_format($data['quantity_child_1']*$priceChildReal) }}</td>
                                </tr>
                                @php
                                    $total      += $data['quantity_child_1']*$priceChildReal;
                                @endphp
                            @endif
                            @if(!empty($data['quantity_old_1']))
                                <tr>
                                    <td>Cao tuổi</td>
                                    <td style="text-align:right;">{{ $data['quantity_old_1'] }} * {{ number_format($priceOldReal) }}</td>
                                    <td style="text-align:right;">{{ number_format($data['quantity_old_1']*$priceOldReal) }}</td>
                                </tr>
                                @php
                                    $total      += $data['quantity_old_1']*$priceOldReal;
                                @endphp
                            @endif
                        </tbody>
                    </table>
                @endif
            @endif
        @endif
    </div>
@endif
@if(!empty($data['name_dp2']))
    <div class="shipBookingTotalBox_row">
        <div style="font-weight:700;"><i class="fa-solid fa-ship"></i>{{ $data['name_dp2'] }} ({{ date('d/m/Y', strtotime($data['date_2'])) }})</div>
        @if(!empty($data['dp2']))
            @php
                $tmp = [];
                $tmp = explode('|', $data['dp2']);
            @endphp
            <div><span class="highLight">{{ $tmp[1] ?? null }} - {{ $tmp[2] ?? null }}</span> tàu {{ $tmp[4] }} ({{ !empty($tmp[3]) ? strtoupper($tmp[3]) : null }})</div>
            @if(!empty($data['quantity_adult_2'])||!empty($data['quantity_child_2'])||!empty($data['quantity_old_2']))
                @php
                    $infoShipPrice  = [];
                    $infoShipPrice  = \App\Models\ShipPrice::find($tmp[0]);
                @endphp
                @if(!empty($infoShipPrice))
                    @php
                        $priceAdultReal = $tmp[3]=='vip' ? $infoShipPrice->price_vip : $infoShipPrice->price_adult;
                        $priceChildReal = $tmp[3]=='vip' ? $infoShipPrice->price_vip : $infoShipPrice->price_child;
                        $priceOldReal   = $tmp[3]=='vip' ? $infoShipPrice->price_vip : $infoShipPrice->price_old;
                    @endphp
                    <table>
                        <tbody>
                            @if(!empty($data['quantity_adult_2']))
                                <tr>
                                    <td>Người lớn</td>
                                    <td style="text-align:right;">{{ $data['quantity_adult_2'] }} * {{ number_format($priceAdultReal) }}</td>
                                    <td style="text-align:right;">{{ number_format($data['quantity_adult_2']*$priceAdultReal) }}</td>
                                </tr>
                                @php
                                    $total      += $data['quantity_adult_2']*$priceAdultReal;
                                @endphp
                            @endif
                            @if(!empty($data['quantity_child_2']))
                                <tr>
                                    <td>Trẻ em</td>
                                    <td style="text-align:right;">{{ $data['quantity_child_2'] }} * {{ number_format($priceChildReal) }}</td>
                                    <td style="text-align:right;">{{ number_format($data['quantity_child_2']*$priceChildReal) }}</td>
                                </tr>
                                @php
                                    $total      += $data['quantity_child_2']*$priceChildReal;
                                @endphp
                            @endif
                            @if(!empty($data['quantity_old_2']))
                                <tr>
                                    <td>Cao tuổi</td>
                                    <td style="text-align:right;">{{ $data['quantity_old_2'] }} * {{ number_format($priceOldReal) }}</td>
                                    <td style="text-align:right;">{{ number_format($data['quantity_old_2']*$priceOldReal) }}</td>
                                </tr>
                                @php
                                    $total      += $data['quantity_old_2']*$priceOldReal;
                                @endphp
                            @endif
                        </tbody>
                    </table>
                @endif
            @endif
        @endif
    </div>
@endif
@if(!empty($total))
    <div class="shipBookingTotalBox_row">
        <table>
            <tbody>
                <tr>
                    <td colspan="2">Tổng</td>
                    <td style="text-align:right;"><span style="font-weight:700;color:#E74C3C;letter-spacing:0.3px;">{{ number_format($total) }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endif