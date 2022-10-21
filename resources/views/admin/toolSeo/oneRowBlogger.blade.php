@if(!empty($item))
<tr id="row_{{ $item->id }}" style="{{ $style ?? null }}">
    <td class="text-center">{{ $no }}</td>
    <td>
        <div>
            <b>{{ $item->name }}</b> - {{ $item->email_manager }}
        </div>
        <div>
            <a href="https://{{ $item->url }}" target="_blank">{{ $item->url }}</a>
        </div>
    </td>
    <td style="vertical-align:top;display:flex;">
        <div class="icon-wrapper iconAction">
            <a href="https://{{ $item->url }}" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <div>Xem</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <div class="actionDelete" onclick="deleteItem({{ $item->id }});">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                <div>Xóa</div>
            </div>
        </div>
    </td>
</tr>
@endif