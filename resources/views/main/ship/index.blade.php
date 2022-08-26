@extends('main.layouts.main')
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="container">
            <!-- content -->
            <div class="pageContent_header">
                <!-- title -->
                <h1 class="titlePage">Tàu cao tốc {{ $item->name ?? null }}</h1>
                <!-- rating -->
                <div class="ratingBox">
                    <div class="ratingBox_star">
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                        <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
                    </div>
                    <div class="ratingBox_text maxLine_1" style="margin-left:2px;font-size:14px;">
                        {{ $item->seo->rating_aggregate_star }} sao / <a href="{{ URL::current() }}">{{ $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch</a>
                    </div>
                </div>
            </div>
            <div class="pageContent_body">
                <div class="pageContent_body_content">
                    <div class="contentShip">
                        <!-- Lịch tàu và Hãng tàu -->
                        @include('main.ship.headContent', ['keyWord' => $item->name])
                        <!-- Nội dung tùy biến -->
                        {!! $content ?? null !!}
                    </div>
                </div>
                <div class="pageContent_body_sidebar">
                    @include('main.shipLocation.sidebar')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">

        $(window).on('load', function () {
            
            autoLoadTocContentWithIcon();

            /* fixed sidebar khi scroll */
            const elemt                 = $('.js_scrollFixed');
            const widthElemt            = elemt.parent().width();
            const positionTopElemt      = elemt.offset().top;
            const heightFooter          = 500;
            $(window).scroll(function(){
                const positionScrollbar = $(window).scrollTop();
                const scrollHeight      = $('body').prop('scrollHeight');
                const heightLimit       = parseInt(scrollHeight - heightFooter - elemt.outerHeight());
                if(positionScrollbar>positionTopElemt&&positionScrollbar<heightLimit){
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
                    $('#js_autoLoadTocContentWithIcon_idWrite').css('max-height', 'calc('+height+'px - 3rem)').html(data);
                    // $('#js_autoLoadTocContentWithIcon_idWrite')
                }
            });
        }
    </script>
@endpush