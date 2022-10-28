@if(!empty($item->seo->rating_aggregate_star)&&!empty($item->seo->rating_aggregate_count))
    <div class="ratingBox">
        <div class="ratingBox_star">
            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
            <span class="ratingBox_star_on"><i class="fas fa-star"></i></span>
        </div>
        <div class="ratingBox_text maxLine_1">
            {{ $item->seo->rating_aggregate_star }} sao / {{ $item->seo->rating_aggregate_count }} đánh giá từ khách du lịch
        </div>
    </div>
@endif