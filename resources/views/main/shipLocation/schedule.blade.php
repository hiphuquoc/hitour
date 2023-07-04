<div class="contentShip_item">
    <div id="lich-tau-va-gia-ve-tau-cao-toc-phu-quoc" class="contentShip_item_title" data-toccontent>
        <i class="fa-solid fa-clock"></i>
        <h2>Lịch trình và giá vé {{ $keyWord ?? null }}</h2>
    </div>
    <div class="contentTour_item_text">
        <p><a href="{{ URL::current() }}" title="{{ !empty($keyWord) ? 'Lịch '.$keyWord : 'Lịch tàu' }}">{{ !empty($keyWord) ? 'Lịch '.$keyWord : 'Lịch tàu' }}</a> bên dưới là lộ trình chính xác được {{ config('company.sortname') }} cập nhật thường xuyên từ hãng tàu. Tuy nhiên, có một số trường hợp do thời tiết, bảo trì,... lịch tàu thay đổi đột xuất sẽ được thông báo riêng cho Quý khách khi đặt vé.</p>
        <p><strong>Giá vé {{ $keyWord ?? 'tàu' }}</strong> niêm yết theo bảng bên dưới áp dụng cho khách lẻ. Đối với khách đoàn lớn (20 khách trở lên) và đối tác vui lòng liện hệ <span style="font-size:1.4rem;font-weight:bold;"><a href="tel:0868684868">{{ config('company.hotline') }}</a></span> để biết thêm chi tiết.</p>

        @if(!empty($schedule))
            {!! $schedule !!}
        @else
            @if(!empty($item->ships)&&$item->ships->isNotEmpty())
                <table class="tableContentBorder" style="font-size:0.95rem;">
                    <thead>
                        <tr>
                            <th style="min-width:210px;">Hãng tàu</th>
                            <th>Khởi hành - cập bến</th>
                            <th style="min-width:210px;">Giá vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($item->ships)&&$item->ships->isNotEmpty())
                            @foreach($item->ships as $ship)
                                @if(!empty($ship->prices)&&$ship->prices->isNotEmpty())
                                    @foreach($ship->prices as $price)
                                        @php
                                            $shipTime = \App\Http\Controllers\AdminShipPriceController::mergeArrayShipPrice($price->times);
                                            // dd($shipTime);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div>
                                                    <h3 class="highLight">{{ $price->partner->name }}</h3>
                                                </div>
                                                <div>
                                                    Ngày áp dụng:<br/>
                                                    @foreach($shipTime[0]['date'] as $date)
                                                        @php
                                                            $dateStart  = date('d/m/Y', strtotime($date['date_start']));
                                                            $dateEnd    = date('d/m/Y', strtotime($date['date_end']));
                                                        @endphp
                                                        @if($dateStart==$dateEnd)
                                                            <div class="highLight">{{ $dateStart }}</div>
                                                        @else 
                                                            <div class="highLight">{{ $dateStart }} - {{ $dateEnd }}</div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                @foreach($shipTime as $time)
                                                    <div class="oneLine">
                                                        <h3>{{ $time['name'] }}</h3>
                                                        @foreach($shipTime[0]['time'] as $t)
                                                            <div>{{ $t['time_departure'] }} - {{ $t['time_arrive'] }}</div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div><span class="highLight" style="font-size:1.1rem;">{{ number_format($price['price_adult']).config('main.unit_currency') }}</span> /Người lớn</div>
                                                <div><span class="highLight" style="font-size:1.1rem;">{{ number_format($price['price_child']).config('main.unit_currency') }}</span> /Trẻ em 6-11</div>
                                                <div><span class="highLight" style="font-size:1.1rem;">{{ number_format($price['price_old']).config('main.unit_currency') }}</span> /Trên 60</div>
                                                @if(!empty($price['price_vip']))
                                                    <div><span class="highLight" style="font-size:1.1rem;">{{ number_format($price['price_vip']).config('main.unit_currency') }}</span> /Vé VIP</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else 
                                    <tr>
                                        <td colspan="3">Chuyến <strong>{{ $ship->name }}</strong> hiện chưa có lịch chạy!</td>
                                    </tr>
                                @endif
                            @endforeach
                        @else 
                            <tr>
                                <td colspan="3">Hiện không có lịch tàu nào!</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif
        @endif
    </div>
</div>