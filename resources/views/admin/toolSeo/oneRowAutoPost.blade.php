@if(!empty($item))
<tr id="row_{{ $item->id }}" style="{{ $style ?? null }}">
    <td class="text-center">{{ $no ?? '-' }}</td>
    <td>
        <div>
            <b>{{ $item->title }}</b> - {{ $item->slug_full }}
        </div>
        <div>
            <a href="https://{{ $item->url }}" target="_blank">{{ $item->url }}</a>
        </div>
    </td>
    <td>
        <div class="oneLine" data-bs-toggle="modal" data-bs-target="#modalBox_keyword" style="cursor:pointer;" onClick="loadFormKeyword({{ $item->id }});">
            <i class="fa-solid fa-font {{ !empty($item->keywords)&&$item->keywords->isNotEmpty() ? 'active' : null }}"></i>Bộ từ khóa
        </div>
        <div class="oneLine" data-bs-toggle="modal" data-bs-target="#modalBox_contentspin" style="cursor:pointer;" onClick="loadFormContentspin({{ $item->id }});">
            <i class="fa-solid fa-file-word {{ !empty($item->contentspin) ? 'active' : null }}"></i>Nội dung spin
        </div>
    </td>
    <td style="vertical-align:top;display:flex;">
        @php
            $toolTip        = 'data-toggle="tooltip" data-placement="top" data-bs-original-title="Để bật tính năng này phải cập nhật *Bộ từ khóa* và *Nội dung spin* trước!"';
            $disabled       = 'disabled';
            $checked        = null;
            $action         = null;
            if(!empty($item->auto_post)&&$item->auto_post==1){
                $toolTip    = null;
                $disabled   = null;
                $checked    = 'checked';
                $action     = 'onClick="changeAutoPost(this);"';
            }
            if(!empty($item->keywords)&&$item->keywords->isNotEmpty()&&!empty($item->contentspin)){
                $toolTip    = null;
                $disabled   = null;
                $action     = 'onClick="changeAutoPost(this);"';
            }
        @endphp
        <div class="form-check form-check-primary form-switch" {!! $toolTip !!} style="margin:0 auto;cursor:pointer;">
            <input type="checkbox" class="form-check-input" value="{{ $item->id }}" {{ $checked }} {!! $action !!} {{ $disabled }} style="cursor:pointer;" />
        </div>
    </td>
</tr>
@endif