@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Điểm đến Tour</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.tourLocation.list') }}">
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
        <div class="searchBox_item" style="margin-left:auto;text-align:right;">
        <?php
            $xhtmlSettingView   = \App\Helpers\Setting::settingView('viewTourLocation', [20, 50, 100, 200, 500], $viewPerPage, $list->total());
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
                if(!empty($list)&&$list->isNotEmpty()){
                    $i          = 1;
                    foreach($list as $item){
                        echo view('admin.tourLocation.oneRow', ['item' => $item, 'no' => $i]);
                        ++$i;
                    }
                }else {
                    echo '<tr><td colspan="6">Không có dữ liệu phù hợp!</td></tr>';
                }
                @endphp
            </tbody>
        </table>
    </div>
    {{ !empty($list&&$list->isNotEmpty()) ? $list->appends(request()->query())->links('admin.template.paginate') : '' }}
</div>


<!-- Nút thêm -->
<a href="{{ route('admin.tourLocation.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.tourLocation.delete') }}",
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