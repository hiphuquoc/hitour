@if(!empty($list)&&$list->isNotEmpty())
<div class="tourList tourGrid {{ !empty($slick)&&$slick==true&&$list->count()>3 ? 'slickBox' : null }}">
    @foreach($list as $tour)
    <div class="tourList_item">
        <a href="/{{ $tour->seo->slug_full ?? null }}" class="tourList_item_gallery">
            <div class="tourList_item_gallery_top">
                <img src="{{ config('main.svg.loading_main_nobg') }}" data-src="{{ $tour->seo->image ?? config('main.images.default_750x460') }}" alt="{{ $tour->name ?? null }}" title="{{ $tour->name ?? null }}" />
                @if(!empty($tour->serviceLocation->display_name))
                    <div class="tourList_item_gallery_top_time">
                        <i class="fa-solid fa-location-dot"></i>{{ $tour->serviceLocation->display_name }}
                    </div>
                @endif
            </div>
            @php
                $imagesFile = [];
                if(!empty($tour->files)&&$tour->files->isNotEmpty()){
                    foreach($tour->files as $file){
                        if($file->file_type=='gallery'){
                            $imagesFile[] = $file->file_name;
                        }
                    }
                }
                $imagesFile     = \App\Helpers\Orther::getRandomInArray($imagesFile);
            @endphp
            @if(!empty($imagesFile))
            <div class="tourList_item_gallery_bottom">
                @foreach($imagesFile as $file)
                    <div class="tourList_item_gallery_bottom_item">
                        <img src="{{ config('main.svg.loading_main') }}" data-src="{{ Storage::url($file) }}" alt="{{ $tour->name ?? null }}" title="{{ $tour->name ?? null }}" />
                    </div>
                @endforeach
            </div>
            @endif
        </a>
        <div class="tourList_item_info" style="paddig-bottom:0.75rem;">
            <div class="tourList_item_info_title">
                <a href="/{{ $tour->seo->slug_full ?? null }}">
                    <h3>{{ $tour->name ?? $tour->seo->title ?? null}}</h3>
                </a>
            </div>
            @if(!empty($tour->comments)&&$tour->comments->isNotEmpty())
                @php
                    $rating         = 0;
                    $ratingCount    = 0;
                    if(!empty($item->comments)&&$item->comments->isNotEmpty()){
                        $tmpTotal   = 0;
                        foreach($item->comments as $comment){
                            $tmpTotal += $comment->rating;
                            $ratingCount += 1;
                        }
                        $rating     = number_format($tmpTotal/$ratingCount, 1);
                    }
                    $ratingText     = \App\Helpers\Rating::getTextRatingByRule($rating);
                @endphp
                @if(!empty($ratingCount))
                    <div class="tourList_item_info_rating">
                        <div class="tourList_item_info_rating_number">
                            <img src="/storage/images/svg/icon-comment.svg" alt="Đánh giá khách sạn" title="Đánh giá khách sạn">
                            <span>{{ $rating }}</span> ({{ $ratingCount }})
                        </div>
                        <div class="tourList_item_info_rating_text">
                            {{ $ratingText }}
                        </div>
                    </div>
                @endif
            @endif
            {{-- <div class="tourList_item_info_highlightTitle">
                <h4 class="maxLine_4">
                    <i class="fa-solid fa-check"></i>{{ $tour->seo->description ?? null }}
                </h4>
            </div> --}}

            @if(!empty($tour->location->display_name)&&!empty($tour->departure->display_name))
            <div class="shipGrid_item_content_table_row" style="align-items:center !important;margin-top:auto;">
                <div class="shipGrid_item_content_table_row__dp maxLine_1" style="flex:unset !important;background:none;">
                    {{ $tour->departure->display_name }}
                </div>
                <div style="text-align:center;flex: 0 0 40px;">
                    <i class="fa-solid fa-plane-departure" style="vertical-align:middle;"></i>
                </div>
                <div class="shipGrid_item_content_table_row__dp maxLine_1" style="flex:unset !important;background:none;">
                    {{ $tour->location->display_name }}
                </div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif