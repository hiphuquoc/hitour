@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách khu vực Tour</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.tourDeparture.list') }}">
    <div class="searchBox">
        <div class="searchBox_item">
            <div class="input-group">
                <input type="text" class="form-control" id="search_name" name="search_name" placeholder="Tìm theo tên" value="{{ $params['search_name'] ?? null }}">
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit">Tìm</button>
            </div>
        </div>
        <div class="searchBox_item">
            <select class="form-select" id="search_region" name="search_region" onChange="submitForm('formSearch');">
                <option value="0">- Lựa chọn -</option>
                @foreach(config('admin.region') as $region)
                    @php
                        $selected   = null;
                        if(!empty($params['search_region'])&&$params['search_region']==$region['id']) $selected = 'selected';
                    @endphp
                    <option value="{{ $region['id'] }}" {{ $selected }}>{{ $region['name'] }}</option>
                @endforeach
            </select>
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
                    <th class="text-center">Ảnh</th>
                    <th class="text-center">Thông tin</th>
                    {{-- <th class="text-center">Thông tin SEO</th> --}}
                    <th class="text-center">Slider</th>
                    <th class="text-center" style="width:200px;">Khác</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @php
                if(!empty($list)){
                    $i          = 1;
                    foreach($list as $item){
                        echo view('admin.tourDeparture.oneRow', ['item' => $item, 'no' => $i]);
                        ++$i;
                    }
                }
                @endphp
            </tbody>
        </table>
    </div>

</div>
<!-- Nút thêm -->
<a href="{{ route('admin.tourDeparture.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.tourDeparture.delete') }}",
                    type        : "GET",
                    dataType    : "html",
                    data        : { id : id }
                }).done(function(data){
                    if(data==true) $('#tourLocation-'+id).remove();
                });
            }
        }

        function submitForm(idForm){
            $('#'+idForm).submit();
        }
    </script>
@endpush