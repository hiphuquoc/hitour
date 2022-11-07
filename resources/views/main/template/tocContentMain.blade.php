<!-- TOC CONTENT -->
<div class="tocContentMain">
    <div class="tocContentMain_title">
        <span class="tocContentMain_title_icon"></span>
        <span class="tocContentMain_title_text">Mục lục</span>
    </div>
    <div class="tocContentMain_list" style="display: block;">
        @foreach($data as $item)
            <a href="#{{ $item['id'] ?? null }}" class="tocContentMain_list_item">
                {{ $item['title'] ?? null }}
            </a>
        @endforeach
    </div>
    <div class="tocContentMain_close"></div>
    </div>
<!-- TOC CONTENT FIXED ICON -->
<div class="tocFixedIcon"><div></div></div>
<!-- TOC CONTENT FIXED -->
<div class="tocContentMain tocFixed">
    <div class="tocContentMain_title">
        <span class="tocContentMain_title_icon"></span>
        <span class="tocContentMain_title_text">Mục lục</span>
    </div>
    <div class="tocContentMain_list customScrollBar-y">
        @foreach($data as $item)
            <a href="#{{ $item['id'] ?? null }}" class="tocContentMain_list_item">
                {{ $item['title'] ?? null }}
            </a>
        @endforeach
    </div>
    <div class="tocContentMain_close"></div>
</div>