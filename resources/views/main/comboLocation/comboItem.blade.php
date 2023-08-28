<div class="tourList tourGrid {{ !empty($slick)&&$slick==true ? 'slickBox' : null }}">
    @foreach($list as $tour)
    <div class="tourList_item">
        <a href="/{{ $tour->seo->slug_full ?? null }}" class="tourList_item_gallery">
            <div class="tourList_item_gallery_top">
                <img src="{{ config('main.svg.loading_main_nobg') }}" data-src="{{ $tour->seo->image ?? config('main.images.default_750x460') }}" alt="{{ $tour->name ?? null }}" title="{{ $tour->name ?? null }}" />
                {{-- <div class="tourList_item_gallery_top_time">
                    3N2Đ
                </div> --}}
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
        <div class="tourList_item_info">
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
            <div class="tourList_item_info_highlightTitle">
                <h4 class="maxLine_4">
                    <i class="fa-solid fa-check"></i>{{ $tour->seo->description ?? null }}
                </h4>
            </div>
            <div class="tourList_item_info_desc">
                @if(!empty($tour->pick_up))
                    <div>Đón tại {{ $tour->pick_up ?? null }} {{ $tour->tour_departure_name ?? null }}</div>
                @endif
                @if(!empty($tour->departure_schedule))
                    <div>Lịch {{ $tour->departure_schedule }}</div>
                @endif
            </div>
        </div>
        <div class="tourList_item_action">
            <div class="tourList_item_action_price">
                @if(!empty($tour->price_del)&&$tour->price_del>$tour->price_now)
                    <div class="tourList_item_action_price_old">
                        <div class="tourList_item_action_price_old_number">
                            {{ number_format($tour->price_del) }}<sup>đ</sup>
                        </div>
                        <div class="tourList_item_action_price_old_saleoff">
                            {{ \App\Helpers\Number::calculatorSaleoff($tour->price_show, $tour->price_del) }}%
                        </div>
                    </div>
                @endif
                <div class="tourList_item_action_price_now">
                    {{ number_format($tour->price_show) }}<sup>đ</sup>
                </div>
            </div>
            <a href="/{{ $tour->seo->slug_full ?? null }}" class="tourList_item_action_button">Chi tiết</a>
        </div>
    </div>
    @endforeach
</div>