@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Vé dịch vụ mới';
        $submit         = 'admin.service.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Vé dịch vụ';
            $submit     = 'admin.service.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        <!-- input hidden -->
        <input type="hidden" id="service_info_id" name="service_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : null }}" />
        <div class="pageAdminWithRightSidebar withRightSidebar">
            <div class="pageAdminWithRightSidebar_header">
                {{ $titlePage }}
            </div>
            <!-- Error -->
            @if ($errors->any())
                <ul class="errorList">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <!-- MESSAGE -->
            @include('admin.template.messageAction')
            
            <div class="pageAdminWithRightSidebar_main">
                <!-- START:: Main content -->
                <div class="pageAdminWithRightSidebar_main_content">
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin trang</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.service.formPage')

                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin SEO</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.form.formSeo')
                                
                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Bảng giá chi tiết
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormModal();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessage">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadPrice">
                                    <!-- javascript:loadPrice -->
                                    Không có dữ liệu phù hợp!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Nội dung
                                </h4>
                            </div>
                            <div class="card-body">
                                @include('admin.form.formContent')
                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Câu hỏi thường gặp</h4>
                            </div>
                            <div class="card-body">
                                
                                @include('admin.form.formAnswer', compact('item'))
                                
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END:: Main content -->

                <!-- START:: Sidebar content -->
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <a href="{{ route('admin.service.list') }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light">Quay lại</a>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;" aria-label="Lưu">Lưu</button>
                    </div>
                    <div class="customScrollBar-y" style="height: calc(100% - 70px);border-top: 1px dashed #adb5bd;">
                        <!-- Form Upload -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formImage')
                        </div>
                        <!-- Form Video -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formVideo')
                        </div>
                        <!-- Form Slider -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formSlider')
                        </div>
                        <!-- Form Gallery -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.form.formGallery')
                        </div>
                    </div>
                </div>
                <!-- END:: Sidebar content -->
            </div>
        </div>

    </form>
    <!-- ===== START:: Modal ===== -->
    <form id="formModal" method="POST">
    @csrf
        <!-- Input Hidden -->
        {{-- <input type="hidden" id="service_info_id" name="service_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" /> --}}
        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormModal_header">Thêm giá</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormModal_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <div id="js_validateFormModal_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateServicePrice();" aria-label="Xác nhận">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- ===== END:: Modal ===== -->
</div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            // closeMessage();
            loadPrice('js_loadPrice');
        })

        $('.pageAdminWithRightSidebar_main_content').repeater();

        function closeMessage(){
            setTimeout(() => {
                $('.js_message').css('display', 'none');
            }, 5000);
        }

        function submitForm(idForm){
            const elemt = $('#'+idForm);
            if(elemt.valid()) elemt.submit();
        }

        function showMessage(idWrite, message, type = 'success'){
            if(message!=''||message!=null){
                let htmlMessage = '<div class="alert alert-'+type+'"><div class="alert-body">'+message+'</div></div>';
                $('#'+idWrite).html(htmlMessage);
                setTimeout(() => {
                    $('#'+idWrite).html('');
                }, 5000);
            }
        }

        function validateFormModal(){
            let error       = [];
            $('#formModal').find('input[required').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }

        function addAndUpdateServicePrice(){
            const tmp                   = validateFormModal();
            /* không có trường required bỏ trống */
            if(tmp==''){
                /* apply_age */
                var apply_age           = [];
                $('#formModal').find('[name*=apply_age]').each(function(){
                    apply_age.push($(this).val());
                });
                /* price */
                var price               = [];
                $('#formModal').find('[name*=name_fix_bug]').each(function(){
                    price.push($(this).val());
                });
                /* profit */
                var profit              = [];
                $('#formModal').find('[name*=profit]').each(function(){
                    profit.push($(this).val());
                });
                /* dataForm */
                var dataForm            = {
                    service_info_id     : $('#service_info_id').val(),
                    service_option_id   : $('#service_option_id').val(),
                    promotion           : $('#promotion').val(),
                    date_range          : $('#date_range').val(),
                    apply_age,
                    price,
                    profit
                };         
                if(typeof dataForm['service_option_id']=='undefined' || dataForm['service_option_id']==''){
                    /* insert - copy */
                    $.ajax({
                        url         : '{{ route("admin.servicePrice.create") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadPrice('js_loadPrice');
                                showMessage('js_showMessage', 'Thêm mới Giá thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }else {
                    /* update */
                    $.ajax({
                        url         : '{{ route("admin.servicePrice.update") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadPrice('js_loadPrice');
                                showMessage('js_showMessage', 'Cập nhật Giá thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }
                $('#modalContact').modal('hide');
            }else {
                /* có 1 vài trường required bị bỏ trống */
                let messageError        = 'Các trường bắt buộc không được để trống!';
                $('#js_validateFormModal_message').css('display', 'block').html(messageError);
            }
        }

        function loadPrice(idWrite){
            $.ajax({
                url         : '{{ route("admin.servicePrice.loadPrice") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'            : '{{ csrf_token() }}',
                    service_info_id     : '{{ !empty($item->id)&&$type!="copy" ? $item->id : 0 }}'
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }

        function loadFormModal(serviceOptionId = 0, typeAction = 'create'){
            const serviceId    = $('#service_info_id').val();
            $.ajax({
                url         : '{{ route("admin.servicePrice.loadFormPrice") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'            : '{{ csrf_token() }}',
                    type                : typeAction,
                    service_info_id     : serviceId,
                    service_option_id   : serviceOptionId
                },
                success     : function(data){
                    $('#js_loadFormModal_header').html(data.header);
                    $('#js_loadFormModal_body').html(data.body);
                }
            });
        }

        function deletePrice(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : '{{ route("admin.servicePrice.delete") }}',
                    type        : 'post',
                    dataType    : 'html',
                    data        : {
                        '_token'            : '{{ csrf_token() }}',
                        service_option_id   : id
                    },
                    success     : function(data){
                        if(data==true){
                            /* thành công */
                            $('#servicePrice_'+id).remove();
                            loadPrice('js_loadPrice');
                            showMessage('js_showMessage', 'Xóa Giá và Thời gian thành công!', 'success');
                        }else {
                            /* thất bại */
                            showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                        }
                    }
                });
            }
        }
        
    </script>
@endpush