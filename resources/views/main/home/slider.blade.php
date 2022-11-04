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
<div id="js_setHeightSliderHomeDesktop_box" class="sliderHome hide-767">
    @foreach($dataSlider as $slider)
        <div class="sliderHome_item">
            @if(!empty($slider['link']))
                <a href="/{{ $slider['link'] }}" title="{{ $slider['alt'] }}" style="background:url({{ $slider['src'] }});"></a>
            @else 
                <div style="background:url({{ $slider['src'] }});"></div>
            @endif
        </div>
    @endforeach
</div>
<!-- END: Home slider Desktop -->

<!-- START: Home slider Mobile -->
<div id="js_setHeightSliderHomeMobile_box" class="sliderHome show-767">
    @foreach($dataSliderMobile as $slider)
        <div class="sliderHome_item">
            @if(!empty($slider['link']))
                <a href="/{{ $slider['link'] }}" title="{{ $slider['alt'] }}" style="background:url({{ $slider['src'] }});"></a>
            @else 
                <div style="background:url({{ $slider['src'] }});"></div>
            @endif
        </div>
    @endforeach
</div>
<!-- END: Home slider Mobile -->
@push('scripts-custom')
    <script type="text/javascript">
        setHeightSliderHomeDesktop();
        setHeightSliderHomeMobile();
        $(window).resize(function(){
            setHeightSliderHomeDesktop();
            setHeightSliderHomeMobile();
        });
        function setHeightSliderHomeDesktop(){
            const valueWidth    = $('#js_setHeightSliderHomeDesktop_box').innerWidth();
            const valueHeight   = parseInt(valueWidth)*0.3385;
            $('#js_setHeightSliderHomeDesktop_box .sliderHome_item').css('height', valueHeight+'px');
        }
        function setHeightSliderHomeMobile(){
            const valueWidth    = $('#js_setHeightSliderHomeMobile_box').innerWidth();
            const valueHeight   = parseInt(valueWidth)*0.7333;
            $('#js_setHeightSliderHomeMobile_box .sliderHome_item').css('height', valueHeight+'px');
        }
    </script>
@endpush