@if(!empty($blogs)&&$blogs->isNotEmpty())
<div class="articleBox">
    @foreach($blogs as $blog)
        <div class="articleBox_item">
            <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" class="articleBox_item_image">
                <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $blog->seo->image_small ?? $blog->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" />
            </a>
            <div class="articleBox_item_content">
                <a href="/{{ $blog->seo->slug_full ?? null }}" title="{{ $blog->name ?? $blog->seo->title ?? $blog->seo->seo_title ?? null }}" class="articleBox_item_content_title">
                    <h2 class="maxLine_2">{{ $blog->name ?? $blog->title ?? null }}</h2>
                </a>
                <div class="articleBox_item_content_subtitle">
                    @if(!empty($blog->seo->updated_at))
                    <span>
                        <i class="fa-regular fa-clock"></i>
                        {{ date('H:i\, d/m/Y', strtotime($blog->seo->updated_at)) }}
                    </span>
                    @endif
                    <span class="articleBox_item_content_subtitle_author">
                        <i class="fa-solid fa-user-pen"></i>
                        Hitour.vn
                    </span>
                </div>
                <div class="articleBox_item_content_des maxLine_3">
                    {{ $blog->description ?? $blog->seo->description ?? null }}
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif