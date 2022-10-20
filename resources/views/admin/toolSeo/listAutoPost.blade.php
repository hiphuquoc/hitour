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
                    <th class="text-center" style="width:100px;">Auto Post</th>
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

<!-- ===== START:: Modal Contentspin ===== -->
<form id="formContentspin"  class="needs-validation invalid" method="GET" action="#">
@csrf
    <!-- Input Hidden -->
    {{-- <input type="hidden" id="tour_info_id" name="tour_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" /> --}}
    <div class="modal fade" id="modalBox_contentspin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <h4>Thêm /Sửa Nội dung Spin</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="formBox">
                        <div id="js_loadFormContentspin_idWrite" class="formBox_full">
                            <!-- load AJAX:: loadFormContentspin -->
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

<!-- ===== START:: Modal Keyword ===== -->
<div class="modal fade" id="modalBox_keyword" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <h4>Thêm Từ khóa</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="formBox" style="padding:0.5rem 0;">
                    <div id="js_loadFormKeyword_idWrite" class="formBox_full">
                        <!-- load AJAX:: loadFormKeyword -->
                    </div>
                </div>
            </div>
            {{-- <div class="modal-footer">
                <div id="js_validateFormModal_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onClick="addBlogger();">Xác nhận</button>
            </div> --}}
        </div>
    </div>
</div>
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
                    /* tải lại form */
                    loadFormContentspin(id);
                    /* tải lại row */
                    loadRowAutoPost(id);
                }
            });
        });

        function changeAutoPost(element){
            const idSeo         = $(element).val();
            let autoPost        = 1;
            const statusElement = $(element).prop('checked');
            if(statusElement==false) autoPost = 0;
            $.ajax({
                url         : '{{ route("admin.toolSeo.changeAutoPost") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id          : idSeo,
                    auto_post   : autoPost
                },
                success     : function(data){
                    /*  */
                }
            });
        }

        function loadFormContentspin(idSeo){
            $.ajax({
                url         : '{{ route("admin.toolSeo.loadFormContentspin") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id    : idSeo
                },
                success     : function(data){
                    $('#js_loadFormContentspin_idWrite').html(data);
                }
            });
        }

        function loadFormKeyword(idSeo){
            $.ajax({
                url         : '{{ route("admin.toolSeo.loadFormKeyword") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id    : idSeo
                },
                success     : function(data){
                    $('#js_loadFormKeyword_idWrite').html(data);
                }
            });
        }

        function createKeyword(element){
            const strKeyword    = $(element).val();
            const idSeo         = $('[name=seo_id]').val();
            $.ajax({
                url         : '{{ route("admin.toolSeo.createKeyword") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id          : idSeo,
                    strKeyword
                },
                success     : function(data){
                    const contentOld    = $('#js_createKeyword_idWrite').html();
                    $('#js_createKeyword_idWrite').html(data + contentOld);
                    $(element).val('');
                    /* tải lại row */
                    loadRowAutoPost(idSeo);
                }
            });
        }

        function deleteKeyword(idKeyword){
            $.ajax({
                url         : '{{ route("admin.toolSeo.deleteKeyword") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    idKeyword
                },
                success     : function(data){
                    $('#keyword_'+idKeyword).hide();
                    /* tải lại row */
                    const idSeo     = $('[name=seo_id]').val();
                    loadRowAutoPost(idSeo);
                }
            });
        }

        function loadRowAutoPost(idSeo){
            $.ajax({
                url         : '{{ route("admin.toolSeo.loadRowAutoPost") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    id    : idSeo
                },
                success     : function(data){
                    $('#row_'+idSeo).replaceWith(data);
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