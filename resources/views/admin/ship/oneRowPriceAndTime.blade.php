@if(!empty($item))
<table class="table table-bordered">
    <thead>
        @php
            $styleHeadTable     = 'background:none !important;color:#345 !important;text-align:center;';
        @endphp
        <tr>
            <th style="{{ $styleHeadTable }}">Hãng tàu /Áp dụng</th>
            <th style="{{ $styleHeadTable }}">Lịch trình</th>
            <th style="{{ $styleHeadTable }}">Giá vé</th>
            <th style="{{ $styleHeadTable }}width:60px;">-</th>
        </tr>
    </thead>
    <tbody>
        @foreach($item as $price)
            @php
                $shipTime   = \App\Http\Controllers\AdminShipPriceController::mergeArrayShipPrice($price->times);
                $rowspan    = count($shipTime);
            @endphp
            <tr id="priceAndTime_{{ $shipTime[0]['id'] }}">
                @if($loop->index==0)
                    <td>
                        <div class="oneLine" style="font-weight:bold;">{{ $price->partner->name }}</div>
                        <div class="oneLine" style="color:rgb(0, 123, 255);">
                            @foreach($shipTime[0]['date'] as $date)
                                <div style="font-weight:700;">{{ date('d/m/Y', strtotime($date['date_start'])) }} - {{ date('d/m/Y', strtotime($date['date_end'])) }}</div>
                            @endforeach
                        </div>
                    </td>
                @endif
                <td>
                    @foreach($shipTime as $item)
                        <div class="oneLine">
                            <div style="font-weight:700;">{{ $item['name'] }}</div>
                            @foreach($item['time'] as $time)
                                <div>{{ $time['time_departure'] }} - {{ $time['time_arrive'] }}</div>
                            @endforeach
                        </div>
                    @endforeach
                </td>
                <td>
                    <div class="oneLine">
                        {{ number_format($price->price_adult).config('main.unit_currency') }} /người lớn
                    </div>
                    <div class="oneLine">
                        {{ number_format($price->price_child).config('main.unit_currency') }} /trẻ em
                    </div>
                    <div class="oneLine">
                        {{ number_format($price->price_old).config('main.unit_currency') }} /cao tuổi
                    </div>
                    @if(!empty($price->price_vip))
                        <div class="oneLine">
                            {{ number_format($price->price_vip).config('main.unit_currency') }} /vé VIP
                        </div>
                    @endif
                </td>
                <td style="display:flex;justify-content:space-between;">
                    <div class="icon-wrapper iconAction">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormModal({{ $price->id }}, 'update')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <div>Sửa</div>
                        </a>
                    </div>
                    <div class="icon-wrapper iconAction">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormModal({{ $price->id }}, 'copy')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            <div>Chép</div>
                        </a>
                    </div>
                    <div class="icon-wrapper iconAction">
                        <a href="#" class="actionDelete" onclick="deletePriceAndTime({{ $price->id }});">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                            <div>Xóa</div>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif