@if(!empty($item))
    <div id="partnerContact_{{ $item->id }}" class="flexBox">
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
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalContact" onClick="loadFormPartnerContact('{{ $item->id }}')">
                    <i data-feather='edit'></i>
                    <div>Sửa</div>
                </a>
            </div>
            <div class="icon-wrapper iconAction">
                <a href="#" class="actionDelete" onClick="deletePartnerContact('{{ $item->id }}');">
                    <i data-feather='x-square'></i>
                    <div>Xóa</div>
                </a>
            </div>
        </div>
    </div>
@endif