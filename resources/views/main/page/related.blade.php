@if(!empty($blogRelates)&&$blogRelates->isNotEmpty())
    <div class="relatedBox">
        <div class="relatedBox_title">
            Bài viết liên quan
        </div>
        <div class="relatedBox_box">
            @foreach($blogRelates as $blog)
                <div class="relatedBox_box_item">
                    <div class="relatedBox_box_item_image">
                        <a href="{{ !empty($blog->seo->slug_full) ? url($blog->seo->slug_full) : url($blog->slug_full) }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}">
                            <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $blog->seo->image_small ?? $blog->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" />
                        </a>
                    </div>
                    <div class="relatedBox_box_item_content">
                        <a href="{{ !empty($blog->seo->slug_full) ? url($blog->seo->slug_full) : url($blog->slug_full) }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" class="relatedBox_box_item_content_title">
                            <h3 class="maxLine_2">
                                {{ $blog->seo->title ?? $blog->name ?? null }}
                            </h3>
                        </a>
                        @if(!empty($blog->seo->updated_at))
                            <div class="relatedBox_box_item_content_time">
                                <i class="fa-regular fa-calendar-days"></i>{{ date('H:i\, d/m/Y', strtotime($blog->seo->updated_at)) }}
                            </div>
                        @endif
                        <div class="relatedBox_box_item_content_des maxLine_3">
                            {{ $blog->seo->description ?? $blog->description ?? null }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts-custom')
        @if($blogRelates->count()>=4)
            <script type="text/javascript">
                $(document).ready(function(){
                    $('.relatedBox_box').slick({
                        infinite: false,
                        slidesToShow: 2.7,
                        slidesToScroll: 3,
                        arrows: true,
                        prevArrow: '<button class="slick-arrow slick-prev"><i class="fa-solid fa-angle-left" aria-label="Slide trước"></i></button>',
                        nextArrow: '<button class="slick-arrow slick-next"><i class="fa-solid fa-angle-right" aria-label="Slide tiếp theo"></i></button>',
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    infinite: false,
                                    slidesToShow: 2.4,
                                    slidesToScroll: 2,
                                    arrows: false,
                                }
                            },
                            {
                                breakpoint: 568,
                                settings: {
                                    infinite: false,
                                    slidesToShow: 1.4,
                                    slidesToScroll: 1,
                                    arrows: false,
                                }
                            }
                        ]
                    });
                    // setupSlick();
                    // $(window).resize(function(){
                    //     setupSlick();
                    // })
                    // function setupSlick(){
                    //     setTimeout(function(){
                    //         $('.relatedBox_box .slick-prev').html('<i class="fas fa-chevron-left"></i>');
                    //         $('.relatedBox_box .slick-next').html('<i class="fas fa-chevron-right"></i>');
                    //     }, 0);
                    // }

                });

            </script>
        @endif
    @endpush
@endif