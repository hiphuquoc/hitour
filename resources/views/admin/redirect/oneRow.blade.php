@if(!empty($item))
@php
    $no = $no ?? 0;
@endphp
<tr id="redirect_{{ $item->id }}">
    <td style="font-weight:700;text-align:center;">
        {{ $no }}
    </td>
    <td style="vertical-align:top;">
        <a href="{{ $item->url_old }}" target="_blank">{{ $item->url_old }}</a>
    </td>
    <td style="vertical-align:top;">
        <a href="{{ $item->url_new }}" target="_blank">{{ $item->url_new }}</a>
    </td>
    <td style="vertical-align:top;display:flex;">
        <div class="icon-wrapper iconAction">
            <a href="#" class="actionDelete" onClick="deleteItem('{{ $item->id }}');">
                <i data-feather='x-square'></i>
                <div>Xóa</div>
            </a>
        </div>
    </td>
</tr>
@endif