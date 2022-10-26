@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Chuyên mục</div>
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
                if(!empty($list)&&$list->isNotEmpty()){
                    $i          = 1;
                    foreach($list as $item){
                        echo view('admin.blog.oneRow', ['item' => $item, 'no' => $i]);
                        if(!empty($item->child)){
                            $j  = 1;
                            foreach($item->child as $child1){
                                echo view('admin.blog.oneRow', ['item' => $child1, 'no' => $i.'.'.$j]);
                                if(!empty($child1->child)){
                                    $k  = 1;
                                    foreach($child1->child as $child2){
                                        echo view('admin.blog.oneRow', ['item' => $child2, 'no' => $i.'.'.$j.'.'.$k]);
                                        if(!empty($child2->child)){
                                            $h  = 1;
                                            foreach($child2->child as $child3){
                                                echo view('admin.blog.oneRow', ['item' => $child3, 'no' => $i.'.'.$j.'.'.$k.'.'.$h]);
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
<a href="{{ route('admin.blog.view') }}" class="addItemBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</a>
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function deleteItem(id){
            $.ajax({
                url         : "{{ route('admin.blog.delete') }}",
                type        : "GET",
                dataType    : "html",
                data        : { id : id }
            }).done(function(data){
                if(data==true) $('#category_'+id).remove();
            });
        }
    </script>
@endpush