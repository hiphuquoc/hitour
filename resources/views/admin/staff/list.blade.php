@extends('admin.layouts.main')
@section('content')

 <div class="titlePage">
    Danh sách Nhân viên tư vấn
</div>
<div class="card">
    <!-- ===== Table ===== -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="50px"></th>
                    <th class="text-center" width="120px">Avatar</th>
                    <th class="text-center">Thông tin</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // dd($list);
                @endphp
                @if(!empty($list))
                    @foreach($list as $item)
                    <tr id="item-{{ $item->id }}">
                        <td style="vertical-align:top;text-align:center">
                            {{ $loop->index + 1 }}
                        </td>
                        <td style="vertical-align:top;text-align:center;">
                            @if(!empty($item->avatar))
                                <img src="{{ $item->avatar }}?{{ time() }}" style="width:50px;border-radius:3px;" />
                            @endif
                        </td>
                        <td style="vertical-align:top;">
                            <div class="oneLine">
                                Họ và tên: {{ $item->firstname }} {{ $item->lastname }} ({{ $item->prefix_name }}. {{ $item->lastname }})
                            </div>
                            <div class="oneLine">
                                Điện thoại: {{ $item->phone }} ({{ $item->zalo }}) - Email: {{ $item->email }}
                            </div>
                        </td>
                        <td style="vertical-align:top;display:flex;">
                            <div class="icon-wrapper iconAction">
                                <a href="{{ route('admin.staff.viewEdit', ['id' => $item->id]) }}">
                                    <i data-feather='edit'></i>
                                    <div>Sửa</div>
                                </a>
                            </div>
                            <div class="icon-wrapper iconAction">
                                <a href="{{ route('admin.staff.viewEdit', ['id' => $item->id, 'type' => 'copy']) }}">
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
                @endif
                
            </tbody>
        </table>
    </div>

</div>
<!-- Nút thêm -->
<a href="{{ route('admin.staff.viewInsert') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.staff.delete') }}",
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