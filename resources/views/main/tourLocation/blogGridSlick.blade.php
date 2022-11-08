@if(!empty($list)&&$list->isNotEmpty())
    <div class="blogGridSlick">
        <div class="blogGridSlick_box">
            @foreach($list as $blog)
                <div class="blogGridSlick_box_item">
                    <div class="blogGridSlick_box_item_image">
                        <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}">
                            <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $blog->seo->image_small ?? $blog->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" />
                        </a>
                    </div>
                    <div class="blogGridSlick_box_item_content">
                        <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" class="blogGridSlick_box_item_content_title">
                            <h3 class="maxLine_2" id="randomIdTocContent_53">
                                {{ $blog->name ?? $blog->seo->title ?? null }}
                            </h3>
                        </a>
                        @if(!empty($blog->seo->updated_at))
                            <div class="blogGridSlick_box_item_content_time">
                                <i class="fa-regular fa-calendar-days"></i>{{ date('H:i\, d/m/Y', strtotime($blog->seo->updated_at)) }}
                            </div>
                        @endif
                        <div class="blogGridSlick_box_item_content_des maxLine_3">
                            {{ $blog->description ?? $blog->seo->description ?? null }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
{{-- @if(!empty($limit)&&$list->count()>$limit) --}}
    <div class="viewMore">
        <a href="/{{ $link ?? null }}" title="Xem thêm"><i class="fa-solid fa-arrow-down-long"></i>Xem thêm</a>
    </div>
{{-- @endif --}}
@pushonce('scripts-custom')
    <script type="text/javascript">
        $('.blogGridSlick_box').slick({
            infinite: false,
            slidesToShow: 3.6,
            slidesToScroll: 1,
            arrows: false,
            responsive: [
                {
                    breakpoint: 1023,
                    settings: {
                        infinite: false,
                        slidesToShow: 2.6,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        infinite: false,
                        slidesToShow: 1.7,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                },
                {
                    breakpoint: 577,
                    settings: {
                        infinite: false,
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        arrows: false,
                    }
                }
            ]
        });

    </script>

@endpushonce