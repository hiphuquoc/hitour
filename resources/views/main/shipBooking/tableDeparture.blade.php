<table class="noResponsive" width="100%" style="border-collapse:collapse;">
    <tbody>
    <tr>
        <td colspan="2"  class="infoShipDeparture">
            <div class="infoShipDeparture_info">
                <div><span class="highLight">{{ $departure->departure }}</span>, {{ $departure->port_departure_province }}</div>
                <div><span class="highLight">{{ $departure->time_departure }}</span> Xuất bến tại {{ $departure->port_departure }}, {{ $departure->port_departure_address }}</div>
            </div>
            <div class="infoShipDeparture_icon">
                <i class="fa-solid fa-ship"></i>
            </div>
            <div class="infoShipDeparture_info">
                <div><span class="highLight">{{ $departure->location }}</span>, {{ $departure->port_location_province }}</div>
                <div><span class="highLight">{{ $departure->time_arrive }}</span> Cập bến tại {{ $departure->port_location }}, {{ $departure->port_location_address }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <span style="width:70px;display:inline-block;">Ngày</span> : <span class="highLight">{{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($departure->date)) }}, ngày {{ date('d-m-Y', strtotime($departure->date)) }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <span style="width:70px;display:inline-block;">Hãng tàu</span> : <span class="highLight">{{ $departure->partner_name ?? '-' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            @php
                $tmp    = [];
                if(!empty($departure->quantity_adult)) $tmp[] = '<span class="highLight">'.$departure->quantity_adult.'</span> người lớn';
                if(!empty($departure->quantity_child)) $tmp[] = '<span class="highLight">'.$departure->quantity_child.'</span> trẻ em 6-11 tuổi';
                if(!empty($departure->quantity_old)) $tmp[] = '<span class="highLight">'.$departure->quantity_old.'</span> người trên 60 tuổi';
                $xhtmlQuantity = implode(', ', $tmp);
            @endphp
            <span style="width:70px;display:inline-block;">Số lượng</span> : <span>{!! $xhtmlQuantity !!}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <span style="width:70px;display:inline-block;">Loại vé</span> : <span class="highLight">{{ $departure->type ?? '-' }}</span>
        </td>
    </tr>
    </tbody>
</table>