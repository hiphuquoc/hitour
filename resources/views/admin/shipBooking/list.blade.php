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
        <table class="table table-bordered" style="min-width:900px;">
            <thead>
                <tr>
                    <th style="width:60px;"></th>
                    <th class="text-center" style="width:180px;">Trạng thái</th>
                    <th class="text-center">Thông tin</th>
                    <th class="text-center">Chuyến tàu</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($list)&&$list->isNotEmpty())
                    @foreach($list as $item)
                        <tr id="ship_booking_{{ $item->id }}">
                            <td class="text-center">{{ $loop->index+1 }}</td>
                            <td style="text-align:center;">
                                <div style="margin-bottom:0.25rem;">{{ date('H:i d/m/Y', strtotime($item->created_at)) }}</div>
                                <div class="badge" style="font-size:0.95rem;background:{{ $item->status->color ?? null }}">{{ $item->status->name ?? null }}</div>
                            </td>
                            <td>
                                <div class="oneLine">
                                    {{ !empty($item->customer_contact->prefix_name) ? $item->customer_contact->prefix_name.' ' : null }}{{ $item->customer_contact->name ?? null }} - {{ $item->customer_contact->phone ?? null }}
                                </div>
                                @php
                                    $arrayCustomer = [];
                                    if(!empty($item->customer_contact->zalo)) $arrayCustomer[] = 'Zalo: '.$item->customer_contact->zalo;
                                    if(!empty($item->customer_contact->email)) $arrayCustomer[] = 'Email: '.$item->customer_contact->email;
                                @endphp
                                @if(!empty($arrayCustomer))
                                    <div class="oneLine">
                                        {!! implode(' - ', $arrayCustomer) !!}
                                    </div>
                                @endif
                                <!-- số lượng -->
                                <div class="oneLine">
                                    @php
                                        $arrayQuantity = [];
                                        if(!empty($item->infoDeparture[0]->quantity_adult)) $arrayQuantity[] = '<span class="highLight">'.$item->infoDeparture[0]->quantity_adult.'</span> người lớn';
                                        if(!empty($item->infoDeparture[0]->quantity_child)) $arrayQuantity[] = '<span class="highLight">'.$item->infoDeparture[0]->quantity_child.'</span> trẻ em 6-11 tuổi';
                                        if(!empty($item->infoDeparture[0]->quantity_old)) $arrayQuantity[] = '<span class="highLight">'.$item->infoDeparture[0]->quantity_old.'</span> trên 60 tuổi';
                                    @endphp
                                    Số lượng: {!! implode(', ', $arrayQuantity) !!}
                                </div>
                                <!-- tính tiền -->
                                @php
                                    $total      = 0;
                                    foreach($item->infoDeparture as $departure){
                                        $total  += $departure->quantity_adult*$departure->price_adult;
                                        $total  += $departure->quantity_child*$departure->price_child;
                                        $total  += $departure->quantity_old*$departure->price_old;
                                    }
                                    /* Chi phí phát sinh */
                                    foreach($item->costMoreLess as $cost) $total  += $cost->value;
                                @endphp
                                <div class="oneLine">
                                    Thành tiền: <span class="highLight">{{ number_format($total) }}</span>
                                </div>
                            </td>
                            <td>
                                @if($item->infoDeparture->isNotEmpty())
                                    @foreach($item->infoDeparture as $departure)
                                        <div class="oneLine">
                                            <div><span style="font-weight:bold;">{{ $departure->departure }} - {{ $departure->location }}</span> ({{ $departure->partner_name }})</div>
                                            <div>{{ $departure->time_departure }} - {{ date('d/m/Y', strtotime($departure->date)) }} <span class="highLight">{{ $departure->type }}</span></div>
                                        </div>
                                    @endforeach
                                @endif
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
                    type        : "get",
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