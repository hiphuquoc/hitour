@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Chuyến tàu</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.ship.list') }}">
<div class="searchBox">
    <div class="searchBox_item">
        <div class="input-group">
            <input type="text" class="form-control" name="search_name" placeholder="Tìm theo tên" value="{{ $params['search_name'] ?? null }}">
            <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tìm">Tìm</button>
        </div>
    </div>
    @if(!empty($shipLocations)&&$shipLocations->isNotEmpty())
        <div class="searchBox_item">
            <select class="form-select select2" name="search_location" onChange="submitForm('formSearch');">
                <option value="0">- Tìm theo Điểm đến -</option>
                @foreach($shipLocations as $location)
                    @php
                        $selected   = null;
                        if(!empty($params['search_location'])&&$params['search_location']==$location->id) $selected = 'selected';
                    @endphp
                    <option value="{{ $location->id }}" {{ $selected }}>{{ $location->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    @if(!empty($partners)&&$partners->isNotEmpty())
        <div class="searchBox_item">
            <select class="form-select select2" name="search_partner" onChange="submitForm('formSearch');">
                <option value="0">- Tìm theo Hãng tàu -</option>
                @foreach($partners as $partner)
                    @php
                        $selected   = null;
                        if(!empty($params['search_partner'])&&$params['search_partner']==$partner->id) $selected = 'selected';
                    @endphp
                    <option value="{{ $partner->id }}" {{ $selected }}>{{ $partner->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    @if(!empty($staffs)&&$staffs->isNotEmpty())
        <div class="searchBox_item">
            <select class="form-select select2" name="search_staff" onChange="submitForm('formSearch');">
                <option value="0">- Tìm theo Nhân viên -</option>
                @foreach($staffs as $staff)
                    @php
                        $selected   = null;
                        if(!empty($params['search_staff'])&&$params['search_staff']==$staff->id) $selected = 'selected';
                    @endphp
                    <option value="{{ $staff->id }}" {{ $selected }}>{{ $staff->firstname }} {{ $staff->lastname }}</option>
                @endforeach
            </select>
        </div>
    @endif
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
                    <th class="text-center">Ảnh</th>
                    <th class="text-center">Thông tin</th>
                    <th class="text-center">Gallery</th>
                    <th class="text-center">Slider</th>
                    <th class="text-center" style="width:200px;">Khác</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @php
                if(!empty($list)&&$list->isNotEmpty()){
                    $i          = 1;
                    foreach($list as $item){
                        echo view('admin.ship.oneRow', ['item' => $item, 'no' => $i]);
                        ++$i;
                    }
                }else {
                    echo '<tr><td colspan="7">Không có dữ liệu phù hợp!</td></tr>';
                }
                @endphp
            </tbody>
        </table>
    </div>
</div>
<!-- Nút thêm -->
<a href="{{ route('admin.ship.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.ship.delete') }}",
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