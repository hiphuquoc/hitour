@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Trang SEO</div>
<!-- ===== START: SEARCH FORM ===== -->
<form id="formSearch" method="get" action="{{ route('admin.toolSeo.listAutoPost') }}">
<div class="searchBox" style="justify-content:space-between;">
    <div class="searchBox_item">
        <div class="input-group">
            <input type="text" class="form-control" name="search_name" placeholder="Tìm theo tên" value="{{ $params['search_name'] ?? null }}">
            <button class="btn btn-primary waves-effect" id="button-addon2" type="submit">Tìm</button>
        </div>
    </div>

    <?php
        $xhtmlSettingView   = \App\Helpers\Setting::settingView('viewCheckSeo', [20, 50, 100, 200, 300, 400, 500], $viewPerPage, $list->total());
        echo $xhtmlSettingView;
    ?>
</div>
</form>
<!-- ===== END: SEARCH FORM ===== -->
<div class="card">
    <!-- ===== Table ===== -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" style="width:70px;"></th>
                    <th class="text-center">Thông tin</th>
                    <th class="text-center" style="width:180px;">Dữ liệu</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($list)&&$list->isNotEmpty())
                    @php
                        $count = 1;
                        if(!empty(request('page'))) {
                            if(request('page')==1){
                                $count  = 1;
                            }else {
                                $count  = (request('page')-1)*$viewPerPage + 1;
                            }
                        }
                    @endphp
                    @foreach($list as $item)
                        @include('admin.toolSeo.oneRowCheckSeo', ['item' => $item, 'no' => $count])
                    @php
                        ++$count;
                    @endphp
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
{{ !empty($list&&$list->isNotEmpty()) ? $list->appends(request()->query())->links('admin.template.paginate') : '' }}
<!-- ===== START:: Modal ===== -->
<div class="modal fade" id="modalBox" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="position:absolute;top:0;">
            <div class="modal-body">
                <div class="formBox">
                    <div id="js_loadDetailCheckSeo_idWrite" class="formBox_full">
                        <!-- load AJAX:: loadDetailCheckSeo -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ===== END:: Modal ===== -->
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        function loadDetailCheckSeo(idSeo, tabActive){
            $.ajax({
                url         : '{{ route("admin.toolSeo.loadDetailCheckSeo") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id          : idSeo,
                    tab_active  : tabActive
                },
                success     : function(data){
                    $('#js_loadDetailCheckSeo_idWrite').html(data);
                }
            });
        }
    </script>
@endpush