@if(!empty($item->options)&&$item->options->isNotEmpty())
    <div class="contentShip_item">
        <div id="gia-ve-vinwonders-phu-quoc" class="contentShip_item_title" data-toccontent="">
            <i class="fa-solid fa-money-check-dollar"></i>
            <h2 id="randomIdTocContent_1">Bảng giá {{ $item->name ?? null }}</h2>
        </div>
        <div class="contentTour_item_text">
            <p>
                <strong>Bảng giá vé</strong> sẽ được cập nhật liên tục theo chính sách của các hãng vui chơi, đơn vị tổ chức. Đây là bảng giá tham khảo áp dụng cho khách lẻ. Đối với khách đoàn lớn (20 khách trở lên) và đối tác vui lòng liện hệ <a href="tel:0868684868"><span style="font-size:1.4rem;font-weight:bold;color:rgb(0,123,255);">08 6868 4868</span></a> để biết thêm chi tiết.
            </p>
            <table class="tableContentBorder" style="margin-bottom:0;font-size:0.95rem;">
                <thead>
                    <tr>
                        <th>Loại vé /Ghi chú</th>
                        <th style="min-width:200px;">Giá vé</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->options as $option)
                        <tr>
                            <td>
                                <div>
                                    {{ $option->name ?? null }}
                                </div>
                                @if(!empty($option->prices[0]->promotion))
                                    <div>
                                        Ghi chú: {{ $option->prices[0]->promotion ?? null }}
                                    </div>
                                @endif
                                @if(!empty($option->prices)&&$option->prices->isNotEmpty())
                                    <div>
                                        Ngày áp dụng:<br>
                                        @php
                                            if($option->prices[0]->date_start==$option->prices[0]->date_end){
                                                $xhtmlDate = date('d/m/Y', strtotime($option->prices[0]->date_start));
                                            }else {
                                                $xhtmlDate = date('d/m/Y', strtotime($option->prices[0]->date_start)).' - '.date('d/m/Y', strtotime($option->prices[0]->date_end));
                                            } 
                                        @endphp
                                        <div class="highLight">{{ $xhtmlDate }}</div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if(!empty($option->prices)&&$option->prices->isNotEmpty())
                                    @foreach($option->prices as $price)
                                        <div>{{ $price->apply_age ?? null }} <span style="font-weight:700;color:rgb(0, 90, 180);font-size:1.1rem;">{{ !empty($price->price) ? number_format($price->price).config('main.unit_currency') : 'Liên hệ' }}</span></div>
                                    @endforeach
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('main.serviceBooking.form', [
                                    'service_location_id'   => $item->serviceLocation->id ?? 0,
                                    'service_info_id'       => $item->id ?? 0
                                ]) }}" class="buttonSecondary" style="min-width:125px;padding:0.75rem;"><i class="fa-solid fa-check"></i>Đặt vé này</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="text-align:center;color:red;">
                <em>(Giá trên đã bao gồm VAT 10%)</em>
            </p>
        </div>
    </div>
@endif