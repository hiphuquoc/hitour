@if(!empty($list)&&$list->isNotEmpty())
    <div class="blogGrid">
        <div class="blogGrid_box">
            @foreach($list as $blog)
                <div class="blogGrid_box_item">
                    <div class="blogGrid_box_item_image">
                        <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}">
                            <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $blog->seo->image_small ?? $blog->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" />
                        </a>
                    </div>
                    <div class="blogGrid_box_item_content">
                        <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" class="blogGrid_box_item_content_title">
                            <h3 class="maxLine_2" id="randomIdTocContent_53">
                                {{ $blog->name ?? $blog->seo->title ?? null }}
                            </h3>
                        </a>
                        @if(!empty($blog->seo->updated_at))
                            <div class="blogGrid_box_item_content_time">
                                <i class="fa-regular fa-calendar-days"></i>{{ date('H:i\, d/m/Y', strtotime($blog->seo->updated_at)) }}
                            </div>
                        @endif
                        <div class="blogGrid_box_item_content_des maxLine_3">
                            {{ $blog->description ?? $blog->seo->description ?? null }}
                        </div>
                    </div>
                </div>
                <div class="blogGrid_box_item">
                    <div class="blogGrid_box_item_image">
                        <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}">
                            <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $blog->seo->image_small ?? $blog->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" />
                        </a>
                    </div>
                    <div class="blogGrid_box_item_content">
                        <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" class="blogGrid_box_item_content_title">
                            <h3 class="maxLine_2" id="randomIdTocContent_53">
                                {{ $blog->name ?? $blog->seo->title ?? null }}
                            </h3>
                        </a>
                        @if(!empty($blog->seo->updated_at))
                            <div class="blogGrid_box_item_content_time">
                                <i class="fa-regular fa-calendar-days"></i>{{ date('H:i\, d/m/Y', strtotime($blog->seo->updated_at)) }}
                            </div>
                        @endif
                        <div class="blogGrid_box_item_content_des maxLine_3">
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