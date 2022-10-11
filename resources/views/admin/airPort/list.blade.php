@extends('admin.layouts.main')
@section('content')

<!-- ===== Card Header ===== -->
<div class="titlePage">
    Danh sách Sân bay
</div>
<div class="card">
    <!-- ===== Table ===== -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="50px"></th>
                    <th class="text-center">Thông tin</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($list)&&$list->isNotEmpty())
                    @foreach($list as $item)
                    <tr id="item-{{ $item->id }}">
                        <td style="vertical-align:top;text-align:center">
                            {{ $loop->index + 1 }}
                        </td>
                        <td style="vertical-align:top;">
                            <div class="oneLine">
                                @php
                                    $tmp            = [
                                        $item->address, $item->district->district_name, $item->province->province_name
                                    ];
                                    $fullAddress    = implode(', ', $tmp);
                                @endphp 
                                <b>{{ $item->name }}</b> - Địa chỉ: {{ $fullAddress }}
                            </div>
                        </td>
                        <td style="vertical-align:top;display:flex;">
                            <div class="icon-wrapper iconAction">
                                <a href="{{ route('admin.airPort.view', ['id' => $item->id]) }}">
                                    <i data-feather='edit'></i>
                                    <div>Sửa</div>
                                </a>
                            </div>
                            <div class="icon-wrapper iconAction">
                                <a href="{{ route('admin.airPort.view', ['id' => $item->id, 'type' => 'copy']) }}">
                                    <i data-feather='copy'></i>
                                    <div>Chép</div>
                                </a>
                            </div>
                            <div class="icon-wrapper iconAction">
                                <div class="actionDelete" onclick="deleteItem('{{ $item->id }}');">
                                    <i data-feather='x-square'></i>
                                    <div>Xóa</div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">Không có dữ liệu phù hợp</td>
                    </tr>
                @endif
                
            </tbody>
        </table>
    </div>

</div>
<!-- Nút thêm -->
<a href="{{ route('admin.airPort.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.airPort.delete') }}",
                    type        : "GET",
                    dataType    : "html",
                    data        : { id : id }
                }).done(function(data){
                    if(data==true) $('#item-'+id).remove();
                });
            }
        }
    </script>
@endpush