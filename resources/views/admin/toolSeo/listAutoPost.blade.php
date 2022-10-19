@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách Blog vệ tinh</div>
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
        $xhtmlSettingView   = \App\Helpers\Setting::settingView('viewAutoPost', [20, 50, 100, 200, 300, 400, 500], $viewPerPage, $list->total());
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
                    <th class="text-center">Thao tác</th>
                    <th class="text-center" width="60px">-</th>
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
                        @include('admin.toolSeo.oneRowAutoPost', ['item' => $item, 'no' => $count])
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
<!-- Nút thêm -->
<div class="addItemBox" data-bs-toggle="modal" data-bs-target="#modalBox">
    <i class="fa-regular fa-plus"></i>
    <span>Thêm</span>
</div>
<!-- ===== START:: Modal ===== -->
<form id="formAddBlogger" method="POST" action="#">
@csrf
    <!-- Input Hidden -->
    {{-- <input type="hidden" id="tour_info_id" name="tour_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" /> --}}
    <div class="modal fade" id="modalBox" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <h4 id="js_loadFormOption_header">Thêm Blog vệ tinh mới</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="js_loadFormOption_body" class="modal-body">
                    @include('admin.toolSeo.formAddBlogger', ['item' => null])
                </div>
                <div class="modal-footer">
                    <div id="js_validateFormModal_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onClick="addBlogger();">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- ===== END:: Modal ===== -->
<!-- ===== START:: Modal ===== -->
<form id="formContentspin"  class="needs-validation invalid" method="GET" action="#">
@csrf
    <!-- Input Hidden -->
    {{-- <input type="hidden" id="tour_info_id" name="tour_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" /> --}}
    <div class="modal fade" id="shareProject" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <h4 id="js_loadFormOption_header">Thêm /Sửa Nội dung Spin</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="formBox">
                        <div id="js_loadContentspin_idWrite" class="formBox_full">
                            <!-- load AJAX:: loadContentspin -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="js_showMessage" style="float:left;">
                        <!-- js_showMessage -->
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- ===== END:: Modal ===== -->
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">

        $("#formContentspin").on('submit', function(e) {
            e.preventDefault();
            var datastring = $(this).serializeArray();
            $.ajax({
                url         : '{{ route("admin.toolSeo.createContentspin") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    dataForm    : datastring
                },
                success     : function(id){
                    showMessage('js_showMessage', 'Cập nhật Contentspin thành công!', 'success');
                    loadContentspin(id);
                    $('#row_'+id).css('background', 'rgba(0,123,255,0.1)');
                }
            });
        });

        function loadContentspin(idSeo){
            $.ajax({
                url         : '{{ route("admin.toolSeo.loadContentspin") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id    : idSeo
                },
                success     : function(data){
                    $('#js_loadContentspin_idWrite').html(data);
                }
            });
        }

        function showMessage(idWrite, message, type = 'success'){
            if(message!=''||message!=null){
                let htmlMessage = '<div class="alert alert-'+type+'" style="margin:0;"><div class="alert-body">'+message+'</div></div>';
                $('#'+idWrite).html(htmlMessage);
                setTimeout(() => {
                    $('#'+idWrite).html('');
                }, 5000);
            }
        }
    </script>
@endpush