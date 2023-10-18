@if(!empty($list)&&$list->isNotEmpty())
    <div id="js_filterTour_parent" class="tourList tourGrid {{ !empty($slick)&&$slick==true&&$list->count()>3 ? 'slickBox' : null }}">
        @foreach($list as $tour)
        @php
            $filterDay = ($tour->days>1) ? 'tour-nhieu-ngay' : 'tour-trong-ngay';
        @endphp
        <div class="tourList_item" data-filter-day="{{ $filterDay }}">
            <a href="/{{ $tour->seo->slug_full ?? null }}" class="tourList_item_gallery">
                <div class="tourList_item_gallery_top">
                    <img src="{{ config('main.svg.loading_main_nobg') }}" data-src="{{ $tour->seo->image ?? config('main.images.default_750x460') }}" alt="{{ $tour->name ?? null }}" title="{{ $tour->name ?? null }}" />
                    @if($tour->days>1)
                        <div class="tourList_item_gallery_top_time">
                            {{ $tour->days.'N'.$tour->nights.'Đ' }}
                        </div>
                    @else 
                        @if(!empty($tour->time_start)&&!empty($tour->time_end))
                            <div class="tourList_item_gallery_top_time">
                                {{ $tour->time_start.' - '.$tour->time_end }}
                            </div>
                        @endif
                    @endif
                </div>
                {{-- @php
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
                @endif --}}
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
                        <i class="fa-solid fa-person-walking-luggage"></i>{{ $tour->seo->description ?? null }}
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
    <div id="js_filterTour_hidden" style="display:none;">
        <!-- chứa phần tử tạm của filter => để hiệu chỉnh nth-child cho chính xác -->
    </div>
    @include('main.tourLocation.loadingGridBox')
@endif