@if($item[0]->prices[0])
<table class="table table-bordered">
    <thead>
        @php
            $styleHeadTable     = 'background:none !important;color:#345 !important;text-align:center;';
        @endphp
        <tr>
            <th style="{{ $styleHeadTable }}">Ngày áp dụng /Khuyến mãi</th>
            <th style="{{ $styleHeadTable }}">Giá vé</th>
            <th style="{{ $styleHeadTable }}width:60px;">-</th>
        </tr>
    </thead>
    <tbody>
        @foreach($item as $option)
            <tr id="servicePrice_{{ $option->id }}">
                <td>
                    {{-- <div class="oneLine" style="font-weight:bold;">{{ $price->partner->name }}</div> --}}
                    <div class="oneLine" style="color:rgb(0, 123, 255);">
                        @php
                            $dateStart  = date('d/m/Y', strtotime($option->prices[0]['date_start']));
                            $dateEnd    = date('d/m/Y', strtotime($option->prices[0]['date_end']));
                        @endphp
                        @if($dateStart==$dateEnd)
                            <div style="font-weight:700;">{{ $dateStart }}</div>
                        @else 
                            <div style="font-weight:700;">{{ $dateStart }} - {{ $dateEnd }}</div>
                        @endif
                    </div>
                    @if(!empty($option->prices[0]->promotion))
                        <div class="oneLine">
                            {{ $option->prices[0]->promotion }}
                        </div>
                    @endif
                </td>
                <td>
                    @foreach($option->prices as $price)
                        <div class="oneLine">
                            {{ number_format($price->price).config('main.unit_currency') }} /{{ $price->apply_age }}
                        </div>
                    @endforeach
                </td>
                <td style="display:flex;justify-content:space-between;">
                    <div class="icon-wrapper iconAction">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormModal({{ $option->id }}, 'update')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            <div>Sửa</div>
                        </a>
                    </div>
                    <div class="icon-wrapper iconAction">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormModal({{ $option->id }}, 'copy')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            <div>Chép</div>
                        </a>
                    </div>
                    <div class="icon-wrapper iconAction">
                        <a href="#" class="actionDelete" onclick="deletePrice({{ $option->id }});">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                            <div>Xóa</div>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif