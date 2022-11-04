@if(!empty($specialLocations)&&$specialLocations->isNotEmpty())
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
                        @php
                            if($loop->index==12) break;
                        @endphp
                    
                </div>
            </div>
        @endforeach
    </div>
@endif