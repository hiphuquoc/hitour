<div class="shipGrid">
    @foreach($list as $item)
        @if(!empty($item->prices[0]->price_adult))
        <div class="shipGrid_item">
            <div class="shipGrid_item_image">
                <a href="/{{ $item->seo->slug_full }}">
                    <img src="{{ $item->seo->image_small ?? $item->seo->image }}" title="{{ $item->name }}" alt="{{ $item->name }}">
                </a>
                <div class="shipGrid_item_image_left">{{ !empty($item->prices[0]->times[0]->time_move) ? \App\Helpers\Time::convertMkToTimeMove($item->prices[0]->times[0]->time_move) : null }}</div>
                <div class="shipGrid_item_image_bottom">Phú Quốc Express</div>
            </div>
            <div class="shipGrid_item_content">
                <div class="shipGrid_item_content_title maxLine_1">
                <a href="/{{ $item->seo->slug_full }}">
                    <h2>{{ $item->name }}</h2>
                </a>
                </div>
                <div class="shipGrid_item_content_table">
                <div class="shipGrid_item_content_table_row" style="align-items:center !important;">
                    <div class="shipGrid_item_content_table_row__dp maxLine_1" style="flex:unset !important;">
                        Cảng {{ $item->departure->district->district_name ?? $item->departure->province->province_name }}
                    </div>
                    <div style="text-align:center;flex: 0 0 40px;">
                        <i class="fas fa-exchange-alt" style="vertical-align:middle;"></i>
                    </div>
                    <div class="shipGrid_item_content_table_row__dp maxLine_1">
                        Cảng {{ $item->location->district->district_name ?? $item->location->province->province_name }}
                    </div>
                </div>
                @php
                    /* filter */
                    $arrayDeparture     = [];
                    $arrayPrice         = [
                        'price_adult'   => [], 
                        'price_child'   => [], 
                        'price_old'     => [], 
                        'price_vip'     => []
                    ];
                    foreach($item->prices as $price){
                        /* xây dựng mảng price */
                        if(!in_array($price->price_adult, $arrayPrice['price_adult'])) {
                            $arrayPrice['price_adult'][] = $price->price_adult;
                            sort($arrayPrice['price_adult']);
                        }
                        if(!in_array($price->price_child, $arrayPrice['price_child'])) {
                            $arrayPrice['price_child'][] = $price->price_child;
                            sort($arrayPrice['price_child']);
                        }
                        if(!in_array($price->price_old, $arrayPrice['price_old'])) {
                            $arrayPrice['price_old'][] = $price->price_old;
                            sort($arrayPrice['price_old']);
                        }
                        if(!in_array($price->price_vip, $arrayPrice['price_vip'])) {
                            if(!empty($price->price_vip)) $arrayPrice['price_vip'][] = $price->price_vip;
                            sort($arrayPrice['price_vip']);
                        }
                        /* xây dựng mảng time */
                        foreach($price->times as $time){
                            $arrayDeparture[$time->ship_from_sort.'-'.$time->ship_to_sort][] = $time->time_departure;
                            /* sắp xếp mỗi lần thêm vào mảng */
                            sort($arrayDeparture[$time->ship_from_sort.'-'.$time->ship_to_sort]);
                        }
                    }
                @endphp
                @foreach($arrayDeparture as $key => $value)
                    <div class="shipGrid_item_content_table_row" style="width:100%;{{ $loop->first ? 'margin-top:0.5rem' : null }}">
                        <div class="maxLine_1">
                            Chặng {{ $key }}
                        </div>
                        <div class="maxLine_1" style="color:#003B7B;">
                            @foreach(array_unique($value) as $v)
                                <span style="font-weight:bold;">{{ $v }}</span>{{ !$loop->last ? ' | ' : null }}
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="shipGrid_item_content_table_row">
                    <div>
                        Người lớn: 
                    </div>
                    <div>
                        @if(count($arrayPrice['price_adult'])>1)
                            <span class="text-price_500">{{ number_format($arrayPrice['price_adult'][0]) }} - {{ number_format(end($arrayPrice['price_adult'])) }}<sup>đ</sup></span> /vé
                        @else
                            <span class="text-price_500">{!! !empty($arrayPrice['price_adult'][0]) ? number_format($arrayPrice['price_adult'][0]).'<sup>đ</sup></span> /vé' : '-' !!}
                        @endif
                    </div>
                </div>
                <div class="shipGrid_item_content_table_row">
                    <div>
                        Trẻ em 6-11: 
                    </div>
                    <div>
                        @if(count($arrayPrice['price_child'])>1)
                            <span class="text-price_500">{{ number_format($arrayPrice['price_child'][0]) }} - {{ number_format(end($arrayPrice['price_child'])) }}<sup>đ</sup></span> /vé
                        @else
                            <span class="text-price_500">{!! !empty($arrayPrice['price_child'][0]) ? number_format($arrayPrice['price_child'][0]).'<sup>đ</sup></span> /vé' : '-' !!}
                        @endif
                    </div>
                </div>
                <div class="shipGrid_item_content_table_row">
                    <div>
                        Trên 60: 
                    </div>
                    <div>
                        @if(count($arrayPrice['price_old'])>1)
                            <span class="text-price_500">{{ number_format($arrayPrice['price_old'][0]) }} - {{ number_format(end($arrayPrice['price_old'])) }}<sup>đ</sup></span> /vé
                        @else
                            <span class="text-price_500">{!! !empty($arrayPrice['price_old'][0]) ? number_format($arrayPrice['price_old'][0]).'<sup>đ</sup></span> /vé' : '-' !!}
                        @endif
                    </div>
                </div>
                <div class="shipGrid_item_content_table_row">
                    <div>
                        Vé Vip: 
                    </div>
                    <div>
                        @if(count($arrayPrice['price_vip'])>1)
                            <span class="text-price_500">{{ number_format($arrayPrice['price_vip'][0]) }} - {{ number_format(end($arrayPrice['price_vip'])) }}<sup>đ</sup></span> /vé
                        @else
                            <span class="text-price_500">{!! !empty($arrayPrice['price_vip'][0]) ? number_format($arrayPrice['price_vip'][0]).'<sup>đ</sup></span> /vé' : 'Không có<sup></sup>' !!}
                        @endif
                    </div>
                </div>
                </div>
                <div class="shipGrid_item_content_btn">
                    <div onclick="javascript:openCloseModal('myModal')" style="border-radius:0 0 0 5px;">
                        <i class="far fa-edit"></i>Đặt vé
                    </div>
                    <a href="/{{ $item->seo->slug_full }}" style="border-radius:0 0 5px 0;">
                        <i class="fas fa-external-link-alt"></i>Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
        @endif
    @endforeach
 </div>