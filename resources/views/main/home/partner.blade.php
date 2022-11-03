@if(!empty($list)&&$list->isNotEmpty())
    <div class="partnerBox">
        <div class="partnerBox_content">
            <div class="partnerBox_content_title">
                <h2 class="sectionBox_title">{{ $title ?? null }}</h2>
            </div>
            <div class="partnerBox_content_desc maxLine_5">
                {!! $description ?? null !!}
            </div>
        </div>
        <div class="partnerBox_list">
            @foreach($list as $item)
                <div class="partnerBox_list_item">
                    <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $item->seo->image_small ?? $item->seo->image ?? config('admin.images.default_750x460') }}" alt="{{ $item->name ?? $item->seo->title ?? $item->seo->seo_title ?? null }}" title="{{ $item->name ?? $item->seo->title ?? $item->seo->seo_title ?? null }}" />
                </div>
            @endforeach
        </div>
    </div>
@endif