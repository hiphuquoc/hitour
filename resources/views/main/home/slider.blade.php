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
    /* xhtml của slider desktop */
    $xhtmlSliderDesktop         = null;
    foreach($dataSlider as $slider){
        if(!empty($slider['link'])){
            // style="background:url('.$slider['src'].');"
            $xhtmlSliderDesktop .= '<div class="sliderHome_item">
                                        <a href="/'.$slider['link'].'" title="'.$slider['alt'].'" data-image="'.$slider['src'].'" style="background:url('.$slider['src'].');"></a>
                                    </div>';
        }else {
            $xhtmlSliderDesktop .= '<div class="sliderHome_item">
                                        <div data-image="'.$slider['src'].'" style="background:url('.$slider['src'].');"></div>
                                    </div>';
        }
    }
    /* xhtml của slider mobile */
    $xhtmlSliderMobile          = null;
    foreach($dataSliderMobile as $slider){
        if(!empty($slider['link'])){
            $xhtmlSliderMobile  .= '<div class="sliderHome_item">
                                        <a href="/'.$slider['link'].'" title="'.$slider['alt'].'" data-image="'.$slider['src'].'" style="background:url('.$slider['src'].');"></a>
                                    </div>';
        }else {
            $xhtmlSliderMobile  .= '<div class="sliderHome_item">
                                        <div data-image="'.$slider['src'].'" style="background:url('.$slider['src'].');"></div>
                                    </div>';
        }
    }
@endphp
<!-- START: Home slider Desktop -->
<div id="js_lazyloadSliderDesktop_box" class="sliderHome hide-767">
    <div class="sliderHome_item">
        <div><img src="{{ config('main.background_slider_home') }}" alt="{{ config('main.description') }}" title="{{ config('main.description') }}" /></div>
    </div>
</div>
<!-- END: Home slider Desktop -->

<!-- START: Home slider Mobile -->
<div id="js_lazyloadSliderMobile_box" class="sliderHome show-767">
    <div class="sliderHome_item">
        <div><img src="{{ config('main.background_slider_home') }}" alt="{{ config('main.description') }}" title="{{ config('main.description') }}" /></div>
    </div>
</div>
<!-- END: Home slider Mobile -->
@push('scripts-custom')
    <script type="text/javascript">
        lazyloadSliderDesktop();
        lazyloadSliderMobile();
        $(window).resize(function(){
            setHeightBox('js_lazyloadSliderDesktop_box', 0.3385);
            setHeightBox('js_lazyloadSliderMobile_box', 0.7333);
        });
        function lazyloadSliderDesktop(){
            /* hiển thị ảnh */
            const valueContent  = <?php echo json_encode($xhtmlSliderDesktop) ?>;
            $('#js_lazyloadSliderDesktop_box').html(valueContent);
            /* setheight box */
            setHeightBox('js_lazyloadSliderDesktop_box', 0.3385);
        }
        function lazyloadSliderMobile(){
            /* hiển thị ảnh */
            const valueContent  = <?php echo json_encode($xhtmlSliderMobile) ?>;
            $('#js_lazyloadSliderMobile_box').html(valueContent);
            /* setheight box */
            setHeightBox('js_lazyloadSliderMobile_box', 0.7333);
        }
        function setHeightBox(idBox, ratio){
            const valueWidth    = $('#'+idBox).innerWidth();
            const valueHeight   = parseInt(valueWidth)*ratio;
            $('#'+idBox+' .sliderHome_item').css('height', valueHeight+'px');
        }
    </script>
@endpush