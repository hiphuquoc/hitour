<div class="detailTourBox">
    <table class="noResponsive">
        <tbody>
            <tr>
                <td colspan="2"><h2>{{ $item->name }}</h2></td>
            </tr>
            @if(!empty($item->code))
                <tr>
                    <td>Mã tour</td>
                    <td><h3 style="font-size:1rem;">{{ $item->code }}</h3></td>
                </tr>
            @endif
            @if(!empty($item->days))
                @if($item->days>1)
                    <tr>
                        <td>Thời gian</td>
                        <td><h3 style="font-size:1rem;">{{ $item->days }} ngày {{ $item->nights }} đêm</h3></td>
                    </tr>
                @else 
                    <tr>
                        <td>Thời gian</td>
                        <td><h3 style="font-size:1rem;">{{ $item->time_start }} - {{ $item->time_end }}</h3></td>
                    </tr>
                @endif
            @endif
            @if(!empty($item->departure_schedule))
                <tr>
                    <td>Lịch tour</td>
                    <td><h3 style="font-size:1rem;">{{ $item->departure_schedule }}</h3></td>
                </tr>
            @endif
            @if(!empty($item->transport))
                <tr>
                    <td>Vận chuyển</td>
                    <td><h3 style="font-size:1rem;">{{ $item->transport }}</h3></td>
                </tr>
            @endif
            <tr>
                <td>Xuất phát</td>
                <td><h3 style="font-size:1rem;">{{ $item->pick_up }}</h3></td>
            </tr>
        </tbody>
    </table>
</div>