@if(!empty($specialLocations)&&$specialLocations->isNotEmpty())
    <!-- START:: specialLocation desktop -->
    <div class="hide-1199">
        <div class="specialLocationBox">
            @foreach($specialLocations as $specialLocation)
                <div class="specialLocationBox_item">
                    <div class="specialLocationBox_item_box">
                        <div class="specialLocationBox_item_box_image" style="background-image:url('{{ $specialLocation->seo->image ?? $specialLocation->seo->image_small ?? config('admin.images.default_750x460') }}');"></div>
                        <a href="/{{ $specialLocation->seo->slug_full ?? null }}" title="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}">
                            <div class="specialLocationBox_item_box_title">
                                <h3 class="maxLine_1">{{ $specialLocation->display_name ?? null }}</h3>
                            </div>
                        </a>
                    </div>
                </div>
                @php
                    if($loop->index==12) break;
                @endphp
            @endforeach
        </div>
    </div>
    <!-- END:: specialLocation desktop -->
    <!-- START:: specialLocation mobile -->
    <div class="show-1199">
        <div class="specialLocationBoxMobile">
            @foreach($specialLocations as $specialLocation)
                <div class="specialLocationBoxMobile_item">
                    <a href="{{ $specialLocation->seo->slug_full ?? null }}" title="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}">
                        <img src="{{ config('main.svg.loading_main') }}" data-src="{{ $specialLocation->seo->image ?? $specialLocation->seo->image_small ?? config('admin.images.default_750x460') }}" alt="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}" title="{{ $specialLocation->name ?? $specialLocation->seo->title ?? $specialLocation->seo->seo_title ?? null }}" />
                        <div class="specialLocationBoxMobile_item_content">
                            <h3>{{ $specialLocation->display_name ?? null }}</h3>
                            <div>{{ $specialLocation->seo->rating_aggregate_count ?? 0 }} đánh giá</div>
                        </div>
                    </a>
                </div>
                @php
                    if($loop->index==12) break;
                @endphp
            @endforeach
        </div>
        {{-- <div class="viewMore">
            <a href="/{{ $link ?? null }}" title="Xem thêm"><i class="fa-solid fa-arrow-down-long"></i>Xem thêm</a>
        </div> --}}
    </div>
    <!-- END:: specialLocation mobile -->
@endif