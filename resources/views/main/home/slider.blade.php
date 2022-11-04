@php
    $dataSlider = [
        [
            'src'   => '/images/main/du-lich-chau-au-hitour-1.jpg',
            'alt'   => 'Trang Tour du lịch Châu Âu Hitour',
            'link'  => 'tour-du-lich-chau-au'
        ],
        [
            'src'   => '/images/main/du-lich-bien-dao-hitour-1.webp',
            'alt'   => 'Trang Tour du lịch biển đảo Hitour'
        ],
        [
            'src'   => '/images/main/dich-vu-lam-visa-hitour-1.png',
            'alt'   => 'Dịch vụ làm Visa Hitour'
        ],
        [
            'src'   => '/images/main/du-lich-nuoc-ngoai-hitour-1.jpg',
            'alt'   => 'Trang Tour du lịch Nước ngoài Hitour'
        ]
    ];
    $dataSliderMobile = [
        [
            'src'   => '/images/main/du-lich-chau-au-hitour-1-mobile.jpg',
            'alt'   => 'Trang Tour du lịch Châu Âu Hitour',
            'link'  => 'tour-du-lich-chau-au'
        ],
        [
            'src'   => '/images/main/du-lich-chau-au-hitour-1-mobile.jpg',
            'alt'   => 'Trang Tour du lịch Châu Âu Hitour',
            'link'  => 'tour-du-lich-chau-au'
        ]
    ];
@endphp
<!-- START: Home slider Desktop -->
<div class="sliderHome hide-767">
    @foreach($dataSlider as $slider)
        <div class="sliderHome_item">
            @if(!empty($slider['link']))
                <a href="/{{ $slider['link'] }}" title="{{ $slider['alt'] }}">
                    <img src="{{ $slider['src'] }}" alt="{{ $slider['alt'] }}" title="{{ $slider['alt'] }}" />
                </a>
            @else 
                <div>
                    <img src="{{ $slider['src'] }}" alt="{{ $slider['alt'] }}" title="{{ $slider['alt'] }}" />
                </div>
            @endif
        </div>
    @endforeach
</div>
<!-- END: Home slider Desktop -->

<!-- START: Home slider Mobile -->
<div class="sliderHome show-767">
    @foreach($dataSliderMobile as $slider)
        <div class="sliderHome_item">
            @if(!empty($slider['link']))
                <a href="/{{ $slider['link'] }}" title="{{ $slider['alt'] }}">
                    <img src="{{ $slider['src'] }}" alt="{{ $slider['alt'] }}" title="{{ $slider['alt'] }}" />
                </a>
            @else 
                <div>
                    <img src="{{ $slider['src'] }}" alt="{{ $slider['alt'] }}" title="{{ $slider['alt'] }}" />
                </div>
            @endif
        </div>
    @endforeach
</div>
<!-- END: Home slider Mobile -->