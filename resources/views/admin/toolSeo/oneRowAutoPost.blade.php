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
        <div class="form-check form-check-primary form-switch">
            <input type="checkbox" checked="" class="form-check-input" id="customSwitch3">
        </div>
    </td>
</tr>
@endif