@if($list->isNotEmpty())
    <div class="faqBox">
        <div class="faqBox_title">
            <h2>Câu hỏi thường gặp {{ !empty($title) ? ' về '.$title : null }}</h2>
        </div>
        <div class="faqBox_content">
            @foreach($list as $faq)
                <div class="faqBox_content_item">
                    <div class="faqBox_content_item_question">
                        <div class="faqBox_content_item_question__icon"></div>
                        <h3>{{ $faq->question }}</h3>
                    </div>
                    <div class="faqBox_content_item_answer">
                        {{ $faq->answer }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif