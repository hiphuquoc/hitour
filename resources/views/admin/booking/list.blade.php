@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Booking Tour</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.booking.list') }}">
    <div class="searchBox">
        <div class="searchBox_item">
            <div class="input-group">
                <input type="text" class="form-control" name="search_customer" placeholder="Tìm theo Khách hàng" value="{{ $params['search_customer'] ?? null }}">
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tìm">Tìm</button>
            </div>
        </div>
        <div class="searchBox_item">
            <select class="form-select" name="search_type" onChange="submitForm('formSearch');">
                <option value="0">- Tìm theo Loại -</option>
                <option value="tour_info" {{ !empty($params['search_type'])&&$params['search_type']=='tour_info' ? 'selected' : null }}>Tour du lịch</option>
                <option value="service_info" {{ !empty($params['search_type'])&&$params['search_type']=='service_info' ? 'selected' : null }}>Vé vui chơi</option>
            </select>
        </div>
        @if(!empty($status))
            <div class="searchBox_item">
                <select class="form-select select2" name="search_status" onChange="submitForm('formSearch');">
                    <option value="0">- Tìm theo Trạng thái -</option>
                    @foreach($status as $s)
                        @php
                            $selected   = null;
                            if(!empty($params['search_status'])&&$params['search_status']==$s->id) $selected = 'selected';
                        @endphp
                        <option value="{{ $s->id }}" {{ $selected }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="searchBox_item" style="margin-left:auto;text-align:right;">
            <?php
                $xhtmlSettingView   = \App\Helpers\Setting::settingView('viewBooking', [20, 50, 100, 200, 500], $viewPerPage, $list->total());
                echo $xhtmlSettingView;
            ?>
        </div>
    </div>
</form>
<!-- ===== END: SEARCH FORM ===== -->
<div class="card">
    <!-- ===== Table ===== -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th class="text-center">Khách hàng</th>
                    <th class="text-center">Tour</th>
                    <th class="text-center" style="width:340px;">Chi tiết</th>
                    <th class="text-center" style="width:180px;">Thanh toán</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($list)&&$list->isNotEmpty())
                    @foreach($list as $item)
                        <tr>
                            <td class="text-center">{{ $loop->index+1 }}</td>
                            <td>
                                <div class="oneLine">
                                    {{ $item->customer_contact->prefix_name ?? null }} {{ $item->customer_contact->name ?? null }}
                                </div>
                                <div class="oneLine">
                                    {{ $item->customer_contact->phone ?? null }} - Zalo: {{ $item->customer_contact->zalo ?? null }}
                                </div>
                                <div class="oneLine">
                                    {{ $item->customer_contact->email ?? null }}
                                </div>
                            </td>
                            <td>
                                <div class="oneLine">
                                    <span style="font-weight:bold;">{{ $item->tour->name ?? $item->service->name ?? null }}</span>
                                </div>
                                <div class="oneLine">
                                    {{ $item->quantityAndPrice[0]->option_name }}
                                </div>
                                @if(!empty($item->date_from))
                                    <div class="oneLine">
                                        @if($item->date_from==$item->date_to)
                                            {{ date('d/m/Y', strtotime($item->date_from)) }}
                                        @else 
                                            {{ date('d/m/Y', strtotime($item->date_from)) }} - {{ date('d/m/Y', strtotime($item->date_to)) }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $total                  = 0;
                                @endphp
                                @if(!empty($item->quantityAndPrice))
                                    <div class="rowBox">
                                            @foreach($item->quantityAndPrice as $quantity)
                                                @php
                                                    $total  += $quantity->quantity*$quantity->price;
                                                @endphp
                                                <div class="oneLine rowBox_item">
                                                    <div class="rowBox_item_column">
                                                        {{ $quantity->option_age }}
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ $quantity->quantity }} * {{ number_format($quantity->price) }}
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ number_format($quantity->quantity*$quantity->price) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="rowBox">
                                    <div class="oneLine rowBox_item">
                                        <div class="rowBox_item_column">
                                            Tổng:
                                        </div>
                                        <div class="rowBox_item_column" style="text-align:right;">
                                            {{ number_format($total) }}
                                        </div>
                                    </div>
                                    <div class="oneLine rowBox_item">
                                        <div class="rowBox_item_column">
                                            Đã cọc:
                                        </div>
                                        <div class="rowBox_item_column" style="text-align:right;">
                                            {{ number_format($item->paid) }}
                                        </div>
                                    </div>
                                    <div class="oneLine rowBox_item">
                                        <div class="rowBox_item_column">
                                            Còn lại:
                                        </div>
                                        <div class="rowBox_item_column" style="text-align:right;color:#E74C3C;font-weight:600;">
                                            {{ number_format($total-$item->paid) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;vertical-align:middle;">
                                <div>{{ date('H:i d/m/Y', strtotime($item->created_at)) }}</div>
                                <div class="badge" style="font-size:0.95rem;background:{{ $item->status->color }}">{{ $item->status->name }}</div>
                            </td>
                            <td style="vertical-align:top;display:flex;">
                                <div class="icon-wrapper iconAction">
                                    <a href="{{ route('admin.booking.viewExport', ['id' => $item->id]) }}">
                                        <i data-feather='eye'></i>
                                        <div>Xem</div>
                                    </a>
                                </div>
                                <div class="icon-wrapper iconAction">
                                    <a href="{{ route('admin.booking.viewEdit', ['id' => $item->id]) }}">
                                        <i data-feather='edit'></i>
                                        <div>Sửa</div>
                                    </a>
                                </div>
                                <div class="icon-wrapper iconAction">
                                    <div class="actionDelete" onClick="deleteItem('{{ 0 }}');">
                                        <i data-feather='x-square'></i>
                                        <div>Xóa</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else 
                    <tr><td colspan="7">Không có dữ liệu phù hợp!</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    {{ !empty($list&&$list->isNotEmpty()) ? $list->appends(request()->query())->links('admin.template.paginate') : '' }}
</div>
<!-- Nút thêm -->
{{-- <a href="{{ route('admin.booking.viewInsert') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a> --}}
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.tour.delete') }}",
                    type        : "GET",
                    dataType    : "html",
                    data        : { id : id }
                }).done(function(data){
                    if(data==true) $('#tour_'+id).remove();
                });
            }
        }

        function submitForm(idForm){
            $('#'+idForm).submit();
        }
    </script>
@endpush