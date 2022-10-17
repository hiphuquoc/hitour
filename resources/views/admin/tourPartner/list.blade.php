@extends('admin.layouts.main')
@section('content')

<!-- ===== Card Header ===== -->
<div class="titlePage">
    Danh sách Đối tác Tour
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
                    <th class="text-center">Liên hệ</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($list)&&$list->isNotEmpty())
                    @foreach($list as $item)
                    {{-- @php
                        dd($item);
                        $listContact            = null;
                        if(!empty($item->contacts)){
                            foreach($item->contacts as $contact){
                                $listContact    .= 
                            }
                        }
                    @endphp --}}
                    <tr id="item-{{ $item->id }}">
                        <td style="vertical-align:top;text-align:center">
                            {{ $loop->index + 1 }}
                        </td>
                        <td style="vertical-align:top;text-align:center;">
                            @if(!empty($item->company_logo))
                                <img src="{{ $item->company_logo }}?{{ time() }}" style="width:50px;border-radius:3px;" />
                            @endif
                        </td>
                        <td style="vertical-align:top;">
                            <div class="oneLine">
                                {{ $item->company_name }} - {{ $item->company_code }}
                            </div>
                            <div class="oneLine">
                                {{ $item->company_hotline }} - {{ $item->company_email }} {!! !empty($item->company_website) ? ' - <a href="'.$item->company_website.'">'.$item->company_website.'</a>' : null !!}
                            </div>
                            <div class="oneLine">
                                
                            </div>
                        </td>
                        <td style="vertical-align:top;">
                            @if(!empty($item->contacts))
                                @foreach($item->contacts as $contact)
                                    <div class="oneLine">
                                        <div>{{ $contact['name'] }} - {{ $contact['phone'] }} ({{ $contact['zalo'] }})</div>
                                        <div>{{ $contact['email'] }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </td>
                        <td style="vertical-align:top;display:flex;">
                            <div class="icon-wrapper iconAction">
                                <a href="{{ route('admin.tourPartner.view', ['id' => $item->id]) }}">
                                    <i data-feather='edit'></i>
                                    <div>Sửa</div>
                                </a>
                            </div>
                            <div class="icon-wrapper iconAction">
                                <a href="{{ route('admin.tourPartner.view', ['id' => $item->id, 'type' => 'copy']) }}">
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
                    <tr><td colspan="5">Không có dữ liệu phù hợp!</td></tr>
                @endif
                
            </tbody>
        </table>
    </div>

</div>
<!-- Nút thêm -->
<a href="{{ route('admin.tourPartner.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.tourPartner.delete') }}",
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