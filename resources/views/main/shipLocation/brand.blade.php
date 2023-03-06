<!-- Hãng tàu -->
<div class="contentShip_item">
    <div id="hang-tau-cao-toc-phu-quoc" class="contentShip_item_title" data-tocContent>
        <i class="fa-solid fa-award"></i>
        <h2>Lựa chọn hãng {{ $keyWord ?? 'tàu cao tốc' }} uy tín - chất lượng?</h2>
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
        <p>Bên dưới là các <strong>hãng {{ $keyWord ?? 'tàu cao tốc' }}</strong> tốt nhất với đầy đủ thông tin để Quý khách có thể cân nhắc cho chuyến đi của mình.</p>
        <div class="shipPartnerBox">
            @php
                $dataPartnerUnique  = [];
                foreach($item->ships as $ship){
                    foreach($ship->partners as $partner) {
                        if(!in_array($partner->infoPartner->id, $dataPartnerUnique)) {
                            $dataPartnerUnique[$partner->infoPartner->id]                       = $partner->infoPartner->toArray();
                            $dataPartnerUnique[$partner->infoPartner->id]['seo_description']    = $partner->infoPartner->seo->seo_description;
                            $dataPartnerUnique[$partner->infoPartner->id]['slug_full']          = $partner->infoPartner->seo->slug_full;
                        }
                    }
                }
            @endphp
            @foreach($dataPartnerUnique as $partner)
                <div class="shipPartnerBox_item">
                    <a href="/{{ $partner['slug_full'] ?? null }}" title="{{ $partner['name'] ?? null }}" class="shipPartnerBox_item_image">
                        <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $partner['company_logo'] }}" alt="{{ $partner['name'] ?? null }}" title="{{ $partner['name'] ?? null }}" />
                    </a>
                    <div class="shipPartnerBox_item_content">
                        <a href="/{{ $partner['slug_full'] ?? null }}" title="{{ $partner['name'] ?? null }}"><h3>{{ $partner['name'] ?? null }}</h3></a>
                        <div class="shipPartnerBox_item_content_desc maxLine_4">{{ $partner['seo_description'] ?? null }}</div>
                    </div>
                </div>  
            @endforeach
        </div>
    </div>
</div>