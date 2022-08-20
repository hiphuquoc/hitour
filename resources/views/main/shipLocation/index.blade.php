@extends('main.layouts.main')
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="container">
            <!-- title -->
            <h1 class="titlePage">Tàu cao tốc Phú Quốc - Vé tàu Phú Quốc</h1>
            <!-- rating -->
            <div class="ratingBox" style="margin-bottom:1rem;">
                <div class="ratingBox_star">
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                </div>
                <div class="ratingBox_text maxLine_1" style="margin-left:2px;font-size:14px;">
                        4.5 sao / <a href="/sp/dau-rua-mat-3s#product-reviews">213 đánh giá từ khách du lịch</a>
                </div>
            </div>
            <!-- ship box -->
            @include('main.shipLocation.shipGrid')

            <div class="pageBox spaceBetweenBox">
                {{-- <div class="pageBox_head">
                    <h2>Thông tin chi tiết tàu cao tốc Phú Quốc</h2>
                </div> --}}
                <div class="pageBox_body">
                    <div class="pageBox_body_content">
                        @include('main.shipLocation.content')
                    </div>
                    <div class="pageBox_body_sidebar">
                        @include('main.shipLocation.sidebar')
                    </div>
                </div>
            </div>

        </div>
    </div>

    

    
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(window).on('load', function () {
            setupSlick();
            $(window).resize(function(){
                setupSlick();
            })

            autoLoadTocContentWithIcon();

            /* fixed sidebar khi scroll */
            const elemt                 = $('.js_scrollFixed');
            const widthElemt            = elemt.parent().width();
            const positionTopElemt      = elemt.offset().top;
            $(window).scroll(function(){
                const positionScrollbar     = $(window).scrollTop();
                if(positionScrollbar>positionTopElemt){
                    elemt.addClass('scrollFixedSidebar').css({
                        'width'         : widthElemt,
                        'margin-top'    : '1.5rem'
                    });
                }else {
                    elemt.removeClass('scrollFixedSidebar').css({
                        'width'         : 'unset',
                        'margin-top'    : 0
                    });
                }
            });
        });

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

        function autoLoadTocContentWithIcon(){
            var dataTocContent      = {};
            var i                   = 0;
            $('body').find('[data-tocContent]').each(function(){
                const dataId        = $(this).attr('id');
                const dataIcon      = $('<div />').append($(this).find('i').clone()).html();
                const dataTitle     = $(this).find('h2').html();
                dataTocContent[i]   = {
                    id      : dataId,
                    icon    : dataIcon,
                    title   : dataTitle
                };
                ++i;
            });
            $.ajax({
                url         : '{{ route("main.ship.loadTocContent") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    dataSend    : dataTocContent
                },
                success     : function(data){
                    /* tính toán chiều cao sidebar */
                    const heightW       = $(window).outerHeight();
                    const heightUsed    = $('#js_autoLoadTocContentWithIcon_idWrite').parent().outerHeight();
                    const height        = parseInt(heightW - heightUsed);
                    console.log('heightW', heightW);
                    console.log('heightUsed', heightUsed);
                    console.log('height', height);

                    $('#js_autoLoadTocContentWithIcon_idWrite').css('max-height', 'calc('+height+'px - 3rem)').html(data);
                    // $('#js_autoLoadTocContentWithIcon_idWrite')
                }
            });
        }
    </script>
@endpush