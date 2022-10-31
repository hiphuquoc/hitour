@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Chuyên mục</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.category.list') }}">
    <div class="searchBox">
        <div class="searchBox_item">
            <div class="input-group">
                <input type="text" class="form-control" name="search_name" placeholder="Tìm theo tên" value="{{ $params['search_name'] ?? null }}">
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tìm">Tìm</button>
            </div>
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
                    <th style="width:60px;"></th>
                    <th class="text-center">Ảnh</th>
                    <th class="text-center">Thông tin</th>
                    <th class="text-center" style="width:200px;">Khác</th>
                    <th class="text-center" width="60px">-</th>
                </tr>
            </thead>
            <tbody>
                @php
                if(!empty($list)){
                    $i          = 1;
                    foreach($list as $item){
                        echo view('admin.category.oneRow', ['item' => $item, 'no' => $i]);
                        if(!empty($item->child)){
                            $j  = 1;
                            foreach($item->child as $child1){
                                echo view('admin.category.oneRow', ['item' => $child1, 'no' => $i.'.'.$j]);
                                if(!empty($child1->child)){
                                    $k  = 1;
                                    foreach($child1->child as $child2){
                                        echo view('admin.category.oneRow', ['item' => $child2, 'no' => $i.'.'.$j.'.'.$k]);
                                        if(!empty($child2->child)){
                                            $h  = 1;
                                            foreach($child2->child as $child3){
                                                echo view('admin.category.oneRow', ['item' => $child3, 'no' => $i.'.'.$j.'.'.$k.'.'.$h]);
                                            }
                                            ++$h;
                                        }
                                        ++$k;
                                    }
                                }
                                ++$j;
                            }
                        }
                        ++$i;
                    }
                }else {
                    echo '<tr><td colspan="5">Không có dữ liệu phù hợp!</td></tr>';
                }
                @endphp
            </tbody>
        </table>
    </div>

</div>
<!-- Nút thêm -->
<a href="{{ route('admin.category.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            $.ajax({
                url         : "{{ route('admin.category.delete') }}",
                type        : "GET",
                dataType    : "html",
                data        : { id : id }
            }).done(function(data){
                if(data==true) $('#category_'+id).remove();
            });
        }
    </script>
@endpush