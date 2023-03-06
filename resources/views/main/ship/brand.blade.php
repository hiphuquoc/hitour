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