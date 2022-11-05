@if(!empty($item->seo->video))
    <div class="sectionBox withBorder">
        <div class="container">
            <div style="text-align:center;">
                <h2 class="sectionBox_title">{!! $title ?? null !!}</h2>
            </div>
            <div class="videoYoutubeBox">
                <div class="videoYoutubeBox_video">
                    {!! $item->seo->video !!}
                </div>
            </div>
        </div>
    </div>
@endif