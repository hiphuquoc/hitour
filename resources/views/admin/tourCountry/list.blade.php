@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Quốc gia</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.tourCountry.list') }}">
    <div class="searchBox">
        <div class="searchBox_item">
            <div class="input-group">
                <input type="text" class="form-control" id="search_name" name="search_name" placeholder="Tìm theo tên" value="{{ $params['search_name'] ?? null }}">
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tìm">Tìm</button>
            </div>
        </div>
        @if(!empty($tourContinents)&&$tourContinents->isNotEmpty())
            <div class="searchBox_item">
                <select class="form-select" id="search_continent" name="search_continent" onChange="submitForm('formSearch');">
                    <option value="0">- Tìm  theo Châu lục -</option>
                    @foreach($tourContinents as $tourContinent)
                        @php
                            $selected   = null;
                            if(!empty($params['search_continent'])&&$params['search_continent']==$tourContinent['id']) $selected = 'selected';
                        @endphp
                        <option value="{{ $tourContinent['id'] }}" {{ $selected }}>{{ $tourContinent['name'] }}</option>
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
                        echo view('admin.tourCountry.oneRow', ['item' => $item, 'no' => $i]);
                        ++$i;
                    }
                }else {
                    echo '<tr><td colspan="6">Không có dữ liệu phù hợp!</td></tr>';
                }
                @endphp
            </tbody>
        </table>
    </div>

</div>
<!-- Nút thêm -->
<a href="{{ route('admin.tourCountry.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.tourCountry.delete') }}",
                    type        : "GET",
                    dataType    : "html",
                    data        : { id : id }
                }).done(function(data){
                    if(data==true) $('#tourCountry_'+id).remove();
                });
            }
        }

        function submitForm(idForm){
            $('#'+idForm).submit();
        }
    </script>
@endpush