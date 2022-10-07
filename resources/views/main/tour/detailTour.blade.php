<div class="detailTourBox">
    <table>
        <tbody>
            <tr>
                <td colspan="2"><h2>{{ $item->name }}</h2></td>
            </tr>
            @if(!empty($item->code))
                <tr>
                    <td>Mã tour</td>
                    <td>{{ $item->code }}</td>
                </tr>
            @endif
            @if(!empty($item->days))
                @if($item->days>1)
                    <tr>
                        <td>Thời gian</td>
                        <td>{{ $item->days }} ngày {{ $item->nights }} đêm</td>
                    </tr>
                @else 
                    <tr>
                        <td>Thời gian</td>
                        <td>{{ $item->time_start }} - {{ $item->time_end }}</td>
                    </tr>
                @endif
            @endif
            @if(!empty($item->departure_schedule))
                <tr>
                    <td>Lịch tour</td>
                    <td>{{ $item->departure_schedule }}</td>
                </tr>
            @endif
            @if(!empty($item->transport))
                <tr>
                    <td>Vận chuyển</td>
                    <td>{{ $item->transport }}</td>
                </tr>
            @endif
            <tr>
                <td>Xuất phát</td>
                <td>{{ $item->pick_up }} {{ $item->departure->name }}</td>
            </tr>
        </tbody>
    </table>
</div>