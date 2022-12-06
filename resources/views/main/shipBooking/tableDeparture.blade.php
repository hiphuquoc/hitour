<table class="noResponsive" width="100%" style="border-collapse:collapse;">
    <tbody>
    <tr>
        <td colspan="2" style="display:flex;padding:0.5rem 1rem !important;border-bottom:1px dashed #d1d1d1">
            <div style="width:calc(50% - 30px);display:inline-block;vertical-align:top">
                <div style="font-size:15px;font-weight:normal;color:#456"><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->departure }}</span>, {{ $departure->port_departure_province }}</div>
                <div><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->time_departure }}</span> Xuất bến tại {{ $departure->port_departure }}, {{ $departure->port_departure_address }}</div>
            </div>
            <div style="width:60px;margin-top:10px;display:inline-block;vertical-align:top;margin-left:15px;">
                <img src="https://hitour.vn/images/main/icon-arrow-email.png" style="width:100%;">
            </div>
            <div style="width:calc(50% - 30px);display:inline-block;vertical-align:top;margin-left:15px;">
                <div style="font-size:15px;font-weight:normal;color:#456"><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->location }}</span>, {{ $departure->port_location_province }}</div>
                <div><span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->time_arrive }}</span> Cập bến tại {{ $departure->port_location }}, {{ $departure->port_location_address }}</div>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding:0.5rem 1rem !important;border-bottom:1px dashed #d1d1d1">
            <span style="width:70px;display:inline-block;">Ngày</span> : <span style="font-weight:bold;color:rgb(0,90,180);">{{ \App\Helpers\DateAndTime::convertMktimeToDayOfWeek(strtotime($departure->date)) }}, ngày {{ date('d-m-Y', strtotime($departure->date)) }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding:0.5rem 1rem !important;border-bottom:1px dashed #d1d1d1">
            <span style="width:70px;display:inline-block;">Hãng tàu</span> : <span style="font-weight:bold;color:rgb(0,90,180);">{{ $departure->partner_name ?? '-' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding:0.5rem 1rem !important;border-bottom:1px dashed #d1d1d1">
            @php
                $tmp    = [];
                if(!empty($departure->quantity_adult)) $tmp[] = '<span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">'.$departure->quantity_adult.'</span> người lớn';
                if(!empty($departure->quantity_child)) $tmp[] = '<span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">'.$departure->quantity_child.'</span> trẻ em 6-11 tuổi';
                if(!empty($departure->quantity_old)) $tmp[] = '<span style="font-size:18px;font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">'.$departure->quantity_old.'</span> người trên 60 tuổi';
                $xhtmlQuantity = implode(', ', $tmp);
            @endphp
            <span style="width:70px;display:inline-block;">Số lượng</span> : <span>{!! $xhtmlQuantity !!}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding:0.5rem 1rem !important;border-bottom:1px dashed #d1d1d1">
            <span style="width:70px;display:inline-block;">Loại vé</span> : <span style="font-weight:bold;color:rgb(0,90,180);text-transform:capitalize;">{{ $departure->type ?? '-' }}</span>
        </td>
    </tr>
    </tbody>
</table>