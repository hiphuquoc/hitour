<!-- One Row -->
<div class="formBox_full_item">
    <input type="hidden" name="seo_id" value="{{ $item->id ?? null }}" />
    <input type="text" class="form-control" name="keywords" placeholder="Thêm từ khóa..." onChange="createKeyword(this);" />
</div>
<!-- One Row -->
<div id="js_createKeyword_idWrite" class="formBox_full_item">
    @if(!empty($item->keywords))
        @foreach($item->keywords as $keyword)
            <span id="keyword_{{ $keyword->id }}" class="bg-primary badgeKeyword">{{ $keyword->keyword }}<i class="fa-solid fa-xmark" onClick="deleteKeyword({{ $keyword->id }});"></i></span>
        @endforeach
    @endif
</div>