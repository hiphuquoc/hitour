@if(!empty($blogRelates)&&$blogRelates->isNotEmpty())
    <div class="relatedBox">
        <div class="relatedBox_title">
            Bài viết liên quan
        </div>
        <div class="relatedBox_box">
            @foreach($blogRelates as $blog)
                <div class="relatedBox_box_item">
                    <div class="relatedBox_box_item_image">
                        <a href="{{ !empty($blog->seo->slug_full) ? url($blog->seo->slug_full) : url($blog->slug_full) }}">
                            <img src="/images/image-default-750x460.png" data-src="{{ $blog->seo->image_small ?? $blog->seo->image }}" alt="{{ $blog->name }}" title="{{ $blog->name }}">
                        </a>
                    </div>
                    <div class="relatedBox_box_item_content">
                        <a href="{{ !empty($blog->seo->slug_full) ? url($blog->seo->slug_full) : url($blog->slug_full) }}" class="relatedBox_box_item_content_title">
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
                        arrows: false,
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    infinite: false,
                                    slidesToShow: 2.4,
                                    slidesToScroll: 1,
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