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
<div id="js_lazyloadSliderDesktop_box" class="sliderHome hide-767">
    @foreach($dataSlider as $slider)
        <div class="sliderHome_item" data-image="{{ $slider['src'] }}" data-link="{{ $slider['link'] ?? null }}" data-title="{{ $slider['alt'] }}">
            <img src="{{ config('main.background_slider_home') }}" alt="{{ config('main.description') }}" title="{{ config('main.description') }}" />
        </div>
    @endforeach
</div>
<!-- END: Home slider Desktop -->

{{-- <!-- START: Home slider Mobile -->
<div id="js_lazyloadSliderMobile_box" class="sliderHome show-767">
    @foreach($dataSlider as $slider)
        <div class="sliderHome_item">
            <div class="sliderHome_item">
                <div><img src="{{ config('main.background_slider_home') }}" alt="{{ config('main.description') }}" title="{{ config('main.description') }}" /></div>
            </div>
        </div>
    @endforeach
</div>
<!-- END: Home slider Mobile --> --}}
@push('scripts-custom')
    <script type="text/javascript">
        setTimeout(() => {
            lazyloadSliderDesktop();
            // lazyloadSliderMobile();
        }, 0);

        setupSlick();
        $('.sliderHome').slick({
            dots: true,
            arrows: true,
            autoplay: true,
            infinite: true,
            autoplaySpeed: 5000,
            lazyLoad: 'ondemand',
            responsive: [
                {
                    breakpoint: 567,
                    settings: {
                        arrows: false,
                    }
                }
            ]
        });

        function setupSlick(){
            setTimeout(function(){
                $('.sliderHome .slick-prev').html('<i class="fa-solid fa-arrow-left-long"></i>');
                $('.sliderHome .slick-next').html('<i class="fa-solid fa-arrow-right-long"></i>');
                $('.sliderHome .slick-dots button').html('');
            }, 0);
        }

        // $(window).resize(function(){
        //     setHeightBox('js_lazyloadSliderDesktop_box', 0.3385);
        //     setHeightBox('js_lazyloadSliderMobile_box', 0.7333);
        //     setupSlick();
        // });
        function lazyloadSliderDesktop(){
            /* hiển thị ảnh */
            $('#js_lazyloadSliderDesktop_box').find('.sliderHome_item').each(function(){
                const image     = $(this).data('image');
                const link      = $(this).data('link');
                const title     = $(this).data('title');
                if(link!=''){
                    var xhtml   = '<a href="'+link+'" title="'+title+'" style="background:url('+image+')"></a>';
                }else {
                    var xhtml   = '<div style="background:url('+image+')"></div>';
                }
                $(this).html(xhtml);
            });
            /* setheight box */
            setHeightBox('js_lazyloadSliderDesktop_box', 0.3385);
        }
        function setHeightBox(idBox, ratio){
            const valueWidth    = $('#'+idBox).innerWidth();
            const valueHeight   = parseInt(valueWidth)*ratio;
            $('#'+idBox+' .sliderHome_item').css('height', valueHeight+'px');
        }
    </script>
@endpush