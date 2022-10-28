@foreach($infoCategoryChilds as $infoCategory)
    @if(!empty($infoCategory->childs)&&$infoCategory->childs->isNotEmpty())
        <div class="blogListLeftRight">
            <div class="blogListLeftRight_title">
                <a href="/{{ $infoCategory->seo->slug_full ?? null }}"><h2>{{ $infoCategory->name ?? null }}</h2></a>
            </div>
            <div class="blogListLeftRight_box">
                <div class="blogListLeftRight_box_left">
                    <a href="/{{ $infoCategory->childs[0]->seo->slug_full ?? null }}" class="blogListLeftRight_box_left_image">
                        <img src="" data-src="{{ $infoCategory->childs[0]->seo->image ?? $infoCategory->childs[0]->seo->image_small ?? config('admin.images.default_750x460') }}" alt="{{ $infoCategory->childs[0]->name ?? $infoCategory->childs[0]->seo->title ?? $infoCategory->childs[0]->seo->seo_title ?? null }}" title="{{ $infoCategory->childs[0]->name ?? $infoCategory->childs[0]->seo->title ?? $infoCategory->childs[0]->seo->seo_title ?? null }}" />
                    </a>
                    <div class="blogListLeftRight_box_left_content">
                        <a href="/" class="blogListLeftRight_box_left_content_title">
                            <h3 class="maxLine_2">{{ $infoCategory->childs[0]->name ?? $infoCategory->childs[0]->seo->title ?? $infoCategory->childs[0]->seo->seo_title ?? null }}</h3>
                        </a>
                        @if(!empty($infoCategory->childs[0]->seo->updated_at))
                            <div class="blogListLeftRight_box_left_content_time">
                                <i class="fa-regular fa-calendar-days"></i>{{ date('H:i\, d/m/Y', strtotime($infoCategory->childs[0]->seo->updated_at)) }}
                            </div>
                        @endif
                        <div class="blogListLeftRight_box_left_content_desc maxLine_5">
                            {{ $infoCategory->childs[0]->description ?? $infoCategory->childs[0]->seo->description ?? $infoCategory->childs[0]->seo->seo_description ?? null }}
                        </div>
                    </div>
                </div>
                <div class="blogListLeftRight_box_right">
                    @for($i=1;$i<$infoCategory->childs->count();++$i)
                        <div class="blogListLeftRight_box_right_item">
                            <a href="/{{ $infoCategory->childs[$i]->slug_full ?? null }}" class="blogListLeftRight_box_right_item_image">
                                <img src="" data-src="{{ $infoCategory->childs[$i]->seo->image ?? $infoCategory->childs[$i]->seo->image_small ?? config('admin.images.default_750x460') }}" alt="{{ $infoCategory->childs[$i]->name ?? $infoCategory->childs[$i]->seo->title ?? $infoCategory->childs[$i]->seo->seo_title ?? null }}" title="{{ $infoCategory->childs[$i]->name ?? $infoCategory->childs[$i]->seo->title ?? $infoCategory->childs[$i]->seo->seo_title ?? null }}" />
                            </a>
                            <div class="blogListLeftRight_box_right_item_content">
                                <h3 class="maxLine_2">{{ $infoCategory->childs[$i]->name ?? $infoCategory->childs[$i]->seo->title ?? $infoCategory->childs[$i]->seo->seo_title ?? null }}</h3>
                                @if(!empty($infoCategory->childs[$i]->seo->updated_at))
                                    <div class="blogListLeftRight_box_right_item_content_time">
                                        <i class="fa-regular fa-calendar-days"></i>{{ date('H:i\, d/m/Y', strtotime($infoCategory->childs[$i]->seo->updated_at)) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        @php
                            if($i==5) break;
                        @endphp
                    @endfor
                </div>
            </div>
            {{-- @if($infoCategory->childs->count()>=6) --}}
                <div class="blogListLeftRight_footer">
                    <div class="blogListLeftRight_footer_text">
                        Chuyên mục Đặc sản Phú Quốc có <span class="highLight">{{ $infoCategory->childs->count() }}</span> bài
                    </div>
                    <a href="/{{ $infoCategory->seo->slug_full ?? null }}" class="blogListLeftRight_footer_button"><i class="fa-solid fa-arrow-down-long"></i>Xem tất cả</a>
                </div>
            {{-- @endif --}}
        </div>
    @endif
@endforeach

@for($j=0;$j<2;++$j)
{{-- <div class="blogListLeftRight">
    <div class="blogListLeftRight_title">
        <a href="/"><h2>Đặc sản Phú Quốc</h2></a>
    </div>
    <div class="blogListLeftRight_box">
        <div class="blogListLeftRight_box_left">
            <a href="/" class="blogListLeftRight_box_left_image">
                <img src="/storage/images/upload/anh-dep-phu-quoc-083-750x460-type-manager-upload.webp" alt="" title="" />
            </a>
            <div class="blogListLeftRight_box_left_content">
                <a href="/" class="blogListLeftRight_box_left_content_title">
                    <h3 class="maxLine_2">Bài viết đặc sản Phú Quốc - Nấm Tràm Phú Quốc</h3>
                </a>
                <div class="blogListLeftRight_box_left_content_time">
                    <i class="fa-regular fa-calendar-days"></i> 12:27, 26/12/2022
                </div>
                <div class="blogListLeftRight_box_left_content_desc maxLine_5">
                    HDV đón quý khách tại khách sạn đến điểm tập trung và khởi hành đi Nam đảo Phú Quốc. Xe và HDV đưa quý khách đến cảng An Thới tận cùng phía Nam Phú Quốc bắt đầu chương trình tour.
                </div>
            </div>
        </div>
        <div class="blogListLeftRight_box_right">
            @for($i=0;$i<5;++$i)
                <div class="blogListLeftRight_box_right_item">
                    <a href="/" class="blogListLeftRight_box_right_item_image">
                        <img src="/storage/images/upload/anh-dep-phu-quoc-083-750x460-type-manager-upload.webp" alt="" title="" />
                    </a>
                    <div class="blogListLeftRight_box_right_item_content">
                        <h3 class="maxLine_2">Bài viết đặc sản Phú Quốc - Nấm Tràm Phú Quốc</h3>
                        <div class="blogListLeftRight_box_right_item_content_time">
                            <i class="fa-regular fa-calendar-days"></i> 12:27, 26/12/2022
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>
    <div class="blogListLeftRight_footer">
        <div class="blogListLeftRight_footer_text">
            Chuyên mục Đặc sản Phú Quốc có <span class="highLight">20</span> bài
        </div>
        <div class="blogListLeftRight_footer_button"><i class="fa-solid fa-arrow-down-long"></i>Xem tất cả</div>
    </div>
</div> --}}

@endfor