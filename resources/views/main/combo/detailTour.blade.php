<div class="detailTourBox">
    <table class="noResponsive">
        <tbody>
            <tr>
                <td colspan="2"><h2>{{ $item->name }}</h2></td>
            </tr>
            @if(!empty($item->code))
                <tr>
                    <td>Mã combo</td>
                    <td><h3 style="font-size:1.05rem;">{{ $item->code }}</h3></td>
                </tr>
            @endif
            @if(!empty($item->days))
                @if($item->days>1)
                    <tr>
                        <td>Thời gian</td>
                        <td><h3 style="font-size:1.05rem;">{{ $item->days }} ngày {{ $item->nights }} đêm</h3></td>
                    </tr>
                @else 
                    <tr>
                        <td>Thời gian</td>
                        <td><h3 style="font-size:1.05rem;">{{ $item->time_start }} - {{ $item->time_end }}</h3></td>
                    </tr>
                @endif
            @endif
            @if(!empty($item->departure_schedule))
                <tr>
                    <td>Lịch khởi hành</td>
                    <td><h3 style="font-size:1.05rem;">{{ $item->departure_schedule }}</h3></td>
                </tr>
            @endif
            @php
                $xhtmlDeparture             = [];
                foreach($item->options as $option){
                    foreach($option->prices as $price){
                        if(!in_array($price->departure->display_name, $xhtmlDeparture)) $xhtmlDeparture[] = $price->departure->display_name;
                    }
                }
                $xhtmlDeparture             = implode(', ', $xhtmlDeparture);
            @endphp
            <tr>
                <td>Xuất phát</td>
                <td><h3 style="font-size:1.05rem;">{{ $xhtmlDeparture }}</h3></td>
            </tr>
        </tbody>
    </table>
</div>