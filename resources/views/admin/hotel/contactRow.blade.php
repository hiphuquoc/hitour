@if(!empty($item))
    <div id="hotelContact_{{ $item->id }}" class="flexBox">
        <div class="flexBox_item">
            <div class="oneLine">
                Họ và tên: {{ $item->name }}
            </div>
            <div class="oneLine">
                Điện thoại: {{ $item->phone }} {{ !empty($item->zalo) ? '('.$item->zalo.')' : null }}
            </div>
            <div class="oneLine">
                Email: {{ $item->email }}
            </div>
        </div>
        <div class="flexBox_item" style="display:flex;flex:0 0 65px;justify-content:space-between;">
            <div class="icon-wrapper iconAction">
                <a href="#" data-bs-toggle="modal" data-bs-target="#formModal" onClick="loadFormHotelContact('{{ $item->id }}')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    <div>Sửa</div>
                </a>
            </div>
            <div class="icon-wrapper iconAction">
                <a href="#" class="actionDelete" onClick="deleteHotelContact('{{ $item->id }}');">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                    <div>Xóa</div>
                </a>
            </div>
        </div>
    </div>
@endif