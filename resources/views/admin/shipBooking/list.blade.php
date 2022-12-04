@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Booking Tàu</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.shipBooking.list') }}">
    <div class="searchBox">
        <div class="searchBox_item">
            <div class="input-group">
                <input type="text" class="form-control" name="search_customer" placeholder="Tìm theo Khách hàng" value="{{ $params['search_customer'] ?? null }}">
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tìm">Tìm</button>
            </div>
        </div>
        @if(!empty($shipPorts))
            <div class="searchBox_item">
                <select class="form-select select2" name="search_departure" onChange="submitForm('formSearch');">
                    <option value="0">- Tìm theo Cảng đi -</option>
                    @foreach($shipPorts as $port)
                        @php
                            $selected   = null;
                            if(!empty($params['search_departure'])&&$params['search_departure']==$port->name) $selected = 'selected';
                        @endphp
                        <option value="{{ $port->name }}" {{ $selected }}>{{ $port->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if(!empty($shipPorts))
            <div class="searchBox_item">
                <select class="form-select select2" name="search_location" onChange="submitForm('formSearch');">
                    <option value="0">- Tìm theo Cảng đến -</option>
                    @foreach($shipPorts as $port)
                        @php
                            $selected   = null;
                            if(!empty($params['search_location'])&&$params['search_location']==$port->name) $selected = 'selected';
                        @endphp
                        <option value="{{ $port->name }}" {{ $selected }}>{{ $port->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
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
                $xhtmlSettingView   = \App\Helpers\Setting::settingView('viewShipBooking', [20, 50, 100, 200, 500], $viewPerPage, $list->total());
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
                    <th class="text-center">Tàu</th>
                    <th class="text-center" style="width:340px;">Chi tiết</th>
                    <th class="text-center" style="width:180px;">Thanh toán</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($list)&&$list->isNotEmpty())
                    @foreach($list as $item)
                        <tr id="ship_booking_{{ $item->id }}">
                            <td class="text-center">{{ $loop->index+1 }}</td>
                            <td>
                                <div class="oneLine">
                                    {{ !empty($item->customer_contact->prefix_name) ? $item->customer_contact->prefix_name.' ' : null }}{{ $item->customer_contact->name ?? null }}
                                </div>
                                <div class="oneLine">
                                    {{ $item->customer_contact->phone ?? null }} - Zalo: {{ $item->customer_contact->zalo ?? null }}
                                </div>
                                <div class="oneLine">
                                    {{ $item->customer_contact->email ?? null }}
                                </div>
                            </td>
                            <td>
                                @if($item->infoDeparture->isNotEmpty())
                                    @foreach($item->infoDeparture as $departure)
                                        <div class="oneLine">
                                            <div><span style="font-weight:bold;">{{ $departure->departure }} - {{ $departure->location }}</span> ({{ date('d/m/Y', strtotime($departure->date)) }})</div>
                                            <div><span style="font-weight:bold;">{{ $departure->time_departure }} - {{ $departure->time_arrive }}</span> tàu {{ $departure->partner_name }} ({{ strtoupper($departure->type) }})</div>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @php
                                    $total                  = 0;
                                @endphp
                                @if($item->infoDeparture->isNotEmpty())
                                    @foreach($item->infoDeparture as $departure)
                                        <div class="rowBox">
                                            @if(!empty($departure->quantity_adult)&&!empty($departure->price_adult))
                                                <div class="rowBox_item">
                                                    <div class="rowBox_item_column">
                                                        Người lớn
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ $departure->quantity_adult }} * {{ number_format($departure->price_adult) }}
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ number_format($departure->quantity_adult*$departure->price_adult) }}
                                                    </div>
                                                </div>
                                                @php
                                                    $total  += $departure->quantity_adult*$departure->price_adult;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_child)&&!empty($departure->price_child))
                                                <div class="rowBox_item">
                                                    <div class="rowBox_item_column">
                                                        Trẻ em
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ $departure->quantity_child }} * {{ number_format($departure->price_child) }}
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ number_format($departure->quantity_child*$departure->price_child) }}
                                                    </div>
                                                </div>
                                                @php
                                                    $total  += $departure->quantity_child*$departure->price_child;
                                                @endphp
                                            @endif
                                            @if(!empty($departure->quantity_old)&&!empty($departure->price_old))
                                                <div class="rowBox_item">
                                                    <div class="rowBox_item_column">
                                                        Trẻ em
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ $departure->quantity_old }} * {{ number_format($departure->price_old) }}
                                                    </div>
                                                    <div class="rowBox_item_column" style="text-align:right;">
                                                        {{ number_format($departure->quantity_old*$departure->price_old) }}
                                                    </div>
                                                    @php
                                                        $total  += $departure->quantity_old*$departure->price_old;
                                                    @endphp
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
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
                                <div class="badge" style="font-size:0.95rem;background:{{ $item->status->color ?? null }}">{{ $item->status->name ?? null }}</div>
                            </td>
                            <td style="vertical-align:top;display:flex;">
                                <div class="icon-wrapper iconAction">
                                    <a href="{{ route('admin.shipBooking.viewExport', ['id' => $item->id]) }}">
                                        <i data-feather='eye'></i>
                                        <div>Xem</div>
                                    </a>
                                </div>
                                <div class="icon-wrapper iconAction">
                                    <a href="{{ route('admin.shipBooking.view', ['id' => $item->id]) }}">
                                        <i data-feather='edit'></i>
                                        <div>Sửa</div>
                                    </a>
                                </div>
                                <div class="icon-wrapper iconAction">
                                    <div class="actionDelete" onClick="deleteItem('{{ $item->id }}');">
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
<a href="{{ route('admin.shipBooking.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.shipBooking.delete') }}",
                    type        : "POST",
                    dataType    : "html",
                    data        : { 
                        '_token'    : '{{ csrf_token() }}',
                        id : id 
                    }
                }).done(function(data){
                    if(data==true) $('#ship_booking_'+id).remove();
                });
            }
        }

        function submitForm(idForm){
            $('#'+idForm).submit();
        }
    </script>
@endpush