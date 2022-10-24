@extends('main.layouts.main')
@push('head-custom')
<!-- ===== START:: SCHEMA ===== -->
@php
    $dataSchema = $item->seo ?? null;
@endphp

<!-- STRAT:: Title - Description - Social -->
@include('main.schema.social', ['data' => $dataSchema])
<!-- END:: Title - Description - Social -->

<!-- STRAT:: Organization Schema -->
@include('main.schema.organization')
<!-- END:: Organization Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.article', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.creativeworkseries', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @php
        $active = 'ship';
    @endphp
    @include('main.form.sortBooking', compact('item', 'active'))

    @include('main.snippets.breadcrumb')

    <div class="pageContent">
        <div class="sectionBox">
            <div class="container">
                <div class="pageContent_body">
                    <div class="pageContent_body_content">
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
                                {{ $item->seo->rating_aggregate_star }} sao / {{ $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch
                            </div>
                        </div>
                        <div class="contentShip">
                            <!-- Lịch tàu và Hãng tàu -->
                            @include('main.ship.headContent', ['keyWord' => $item->name])
                            <!-- Nội dung tùy biến -->
                            {!! $content ?? null !!}
                            <!-- faq -->
                            @include('main.snippets.faq', ['list' => $item->questions, 'title' => 'tàu '.$item->name])
                        </div>
                    </div>
                    <div class="pageContent_body_sidebar">
                        @include('main.ship.sidebar')
                    </div>
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
                const name          = $(this)[0].localName;
                const dataIcon      = $('<div />').append($(this).find('i').clone()).html();
                const dataTitle     = $(this).find('h2').html();
                dataTocContent[i]   = {
                    id      : dataId,
                    icon    : dataIcon,
                    name    : name,
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