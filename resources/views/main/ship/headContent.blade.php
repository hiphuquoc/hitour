<div class="contentShip_item">
    <div id="lich-tau-va-gia-ve-tau-cao-toc-phu-quoc" class="contentShip_item_title" data-toccontent>
        <i class="fa-solid fa-clock"></i>
        <h2>Lịch trình và giá vé tàu {{ $keyWord ?? null }}</h2>
    </div>
    <div class="contentTour_item_text">
        <p><a href="{{ URL::current() }}" title="{{ !empty($keyWord) ? 'Lịch tàu '.$keyWord : 'Lịch tàu cao tốc' }}">{{ !empty($keyWord) ? 'Lịch tàu '.$keyWord : 'Lịch tàu cao tốc' }}</a> bên dưới là lộ trình chính xác được Hitour cập nhật thường xuyên từ hãng tàu. Tuy nhiên, có một số trường hợp do thời tiết, bảo trì,... lịch tàu thay đổi đột xuất sẽ được thông báo riêng cho Quý khách khi đặt vé.</p>
        <p><strong>Giá vé tàu</strong> niêm yết theo bảng bên dưới áp dụng cho khách lẻ. Đối với khách đoàn lớn (20 khách trở lên) và đối tác vui lòng liện hệ <span style="font-size:1.4rem;font-weight:bold;color:rgb(0,123,255);">08.6868.4868</span> để biết thêm chi tiết.</p>
        @php
            // dd($item->toArray());
        @endphp

        <table class="tableContentBorder" style="font-size:0.95rem;">
            <thead>
                <tr>
                    <th>Hãng tàu</th>
                    <th>Khởi hành - cập bến</th>
                    <th>Giá vé</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($item->prices)&&$item->prices->isNotEmpty())
                    @foreach($item->prices as $price)
                        @php
                            $shipTime = \App\Http\Controllers\AdminShipPriceController::mergeArrayShipPrice($price->times);
                        @endphp
                        <tr>
                            <td>
                                <div>
                                    <h3 class="highLight">{{ $price->partner->name }}</h3>
                                </div>
                                <div>
                                    Ngày áp dụng:<br/>
                                    @foreach($shipTime[0]['date'] as $date)
                                        <div class="highLight">{{ date('d/m/Y', strtotime($date['date_start'])) }} - {{ date('d/m/Y', strtotime($date['date_end'])) }}</div>
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
                        <td colspan="3">Chuyến <strong>{{ $item->name }}</strong> hiện chưa có lịch chạy!</td>
                    </tr>
                @endif
            </tbody>
        </table>

    </div>
</div>

<!-- Hãng tàu -->
<div class="contentShip_item">
    <div id="hang-tau-cao-toc-phu-quoc" class="contentShip_item_title" data-tocContent>
        <i class="fa-solid fa-award"></i>
        <h2>Lựa chọn hãng tàu {{ $keyWord ?? 'tàu cao tốc' }} uy tín - chất lượng?</h2>
    </div>
    <div class="contentTour_item_text">
        <p>Việc lựa chọn <strong>hãng tàu cao tốc</strong> phù hợp cho riêng bạn rất quan trọng vì nó ảnh hưởng trực tiếp đến trải nghiệm, sức khoẻ của bạn trong suốt chuyến du lịch. Thường du khách sẽ lựa chọn dựa trên các tiêu chí như sau:</p>
        <ul class="listStyle">
            <li>Loại tàu, đời tàu và thiết kế của tàu</li>
            <li>Trong quá trình tàu vận hành có ổn không? Tàu có say sóng không?</li>
            <li>Chỗ ngồi có rộng rãi, thoáng mát và dễ chịu?</li>
            <li>Giá vé của tàu và dịch vụ đi kèm trên tàu</li>
            <li>Sự phục vụ của nhân viên từ Đặt vé đến khi Đi tàu</li>
            <li>Và trải nghiệm, đánh giá của khách hàng khác trước đó</li>
        </ul>
        <p>Bên dưới là các <strong>hãng tàu cao tốc {{ $keyWord ?? 'tàu cao tốc' }}</strong> tốt nhất với đầy đủ thông tin để Quý khách có thể cân nhắc cho chuyến đi của mình.</p>
        <div class="shipPartnerBox">
            @foreach($item->partners as $partner)
                <div class="shipPartnerBox_item">
                    <a href="/{{ $partner->infoPartner->seo->slug_full ?? null }}" title="{{ $partner->infoPartner->name ?? $partner->infoPartner->seo->title ?? $partner->infoPartner->seo->seo_title ?? null }}" class="shipPartnerBox_item_image">
                        <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $partner->infoPartner->seo->image_small ?? $partner->infoPartner->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $partner->infoPartner->name ?? $partner->infoPartner->seo->title ?? $partner->infoPartner->seo->seo_title ?? null }}" title="{{ $partner->infoPartner->name ?? $partner->infoPartner->seo->title ?? $partner->infoPartner->seo->seo_title ?? null }}" />
                    </a>
                    <div class="shipPartnerBox_item_content">
                        <a href="/{{ $partner->infoPartner->seo->slug_full ?? null }}" title="{{ $partner->infoPartner->name ?? $partner->infoPartner->seo->title ?? $partner->infoPartner->seo->seo_title ?? null }}">
                            <h3>{{ $partner->infoPartner->name ?? $partner->infoPartner->seo->title ?? null }}</h3>
                        </a>
                        <div class="shipPartnerBox_item_content_desc maxLine_4">{{ $partner->infoPartner->seo->seo_description ?? null }}</div>
                    </div>
                </div>  
            @endforeach
        </div>
    </div>
</div>