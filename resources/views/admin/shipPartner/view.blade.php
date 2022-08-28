@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Đối tác Tàu mới';
        $submit         = 'admin.shipPartner.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Đối tác Tàu';
            $submit     = 'admin.shipPartner.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        @if(!empty($item->id))
            <input type="hidden" name="id" value="{{ $item->id }}" />
        @endif
        <div class="pageAdminWithRightSidebar withRightSidebar">
            <div class="pageAdminWithRightSidebar_header">
                {{ $titlePage }}
            </div>
            <!-- START:: Error -->
            @if ($errors->any())
                <ul class="errorList">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <!-- END:: Error -->
            
            <!-- MESSAGE -->
            @include('admin.template.messageAction')

            <div class="pageAdminWithRightSidebar_main">
                <div class="pageAdminWithRightSidebar_main_content">
                    <!-- START:: Main content -->
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <!-- Thông tin đối tác -->
                            @include('admin.shipPartner.formInfo')
                        </div>
                        <div class="card">
                            <!-- Danh sách liên hệ -->
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Danh sách liên hệ
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#modalContact" onClick="setValueFormInsert();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessage">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadPartnerContact">
                                    <!-- Load Ajax -->
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <!-- Thông tin Seo -->
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
                        <!-- Nội dung -->
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Nội dung</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.form.formContent')
                                
                            </div>
                        </div>
                    </div>
                    <!-- END:: Main content -->
                </div>
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <button type="button" class="btn btn-secondary waves-effect waves-float waves-light"  onClick="history.back();">Quay lại</button>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;">Lưu</button>
                    </div>
                    <div class="customScrollBar-y" style="height: calc(100% - 70px);border-top: 1px dashed #adb5bd;">
                        <!-- Form Upload -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.shipPartner.formLogo')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- ===== Modal ===== -->
    <form id="formModalContact" method="POST" action="">
    @csrf
        <!-- Input Hidden -->
        <input type="hidden" id="partner_id" name="partner_id" value="{{ !empty($item->id)&&$type=='edit' ? $item->id : null }}" />
        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormPartnerContact_header">Thêm liên hệ</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormPartnerContact_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateContactForPartner();">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts-custom')
    <script type="text/javascript">

        $(document).ready(function(){
            loadContactOfPartner('js_loadPartnerContact');
        })

        function submitForm(idForm){
            const elemt = $('#'+idForm);
            if(elemt.valid()) elemt.submit();
        }

        function setValueFormInsert(){
            const partnerId     = $('#partner_id').val();
            if(partnerId!=''){
                loadFormPartnerContact(0);
            }else {
                $('#js_loadFormPartnerContact_body').html('<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Đối tác trước khi tạo Liên hệ!</div>');
            }
            
        }

        function addAndUpdateContactForPartner(){
            const dataForm          = {};
            $('#formModalContact').find('input').each(function(){
                let keyInput        = $(this).attr('name');
                let valueInput      = $(this).val();
                if(valueInput!='') dataForm[keyInput]  = valueInput;
            })
            const tmp               = validateFormModal();
            if(tmp==''){
                /* không có trường required bỏ trống */
                if(dataForm['partner_contact_id']==null){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.shipPartner.createContact") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadContactOfPartner('js_loadPartnerContact');
                                showMessage('js_showMessage', 'Thêm mới Liên hệ thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }else {
                    /* update */
                    $.ajax({
                        url         : '{{ route("admin.shipPartner.updateContact") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            
                            if(data==true){
                                /* thành công */
                                loadContactOfPartner('js_loadPartnerContact');
                                showMessage('js_showMessage', 'Cập nhật Liên hệ thành công!', 'success');
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
                let messageError        = 'Không được bỏ trống trường ';
                tmp.forEach(function(value, index, array){
                    switch(value) {
                        case 'name':
                            messageError    += '<strong>Họ tên</strong>';
                            break;
                        case 'phone':
                            messageError    += '<strong>Điện thoại</strong>';
                            break;
                        case 'zalo':
                            messageError    += '<strong>Số Zalo</strong>';
                            break;
                        case 'email':
                            messageError    += '<strong>Email</strong>';
                            break;
                        default:
                    }
                    if(index!=parseInt(tmp.length-1)) messageError += ', ';
                })
                
                $('#js_validateFormModal_message').css('display', 'block').html(messageError);
            }
        }

        function loadContactOfPartner(idWrite){
            $.ajax({
                url         : '{{ route("admin.shipPartner.loadContact") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    partner_id  : '{{ $item->id ?? 0 }}',
                    type        : '{{ $type }}'
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                    replaceIcon();
                }
            });
        }

        function loadFormPartnerContact(id){
            $.ajax({
                url         : '{{ route("admin.shipPartner.loadFormContact") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    id          : id
                },
                success     : function(data){
                    $('#js_loadFormPartnerContact_header').html(data.header);
                    $('#js_loadFormPartnerContact_body').html(data.body);
                }
            });
        }

        function deletePartnerContact(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : '{{ route("admin.shipPartner.deleteContact") }}',
                    type        : 'post',
                    dataType    : 'html',
                    data        : {
                        '_token'    : '{{ csrf_token() }}',
                        id      : id
                    },
                    success     : function(data){
                        if(data==true){
                            /* thành công */
                            $('#partnerContact_'+id).remove();
                            loadContactOfPartner('js_loadPartnerContact');
                            showMessage('js_showMessage', 'Xóa Liên hệ thành công!', 'success');
                        }else {
                            /* thất bại */
                            showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                        }
                    }
                });
            }
        }

        function validateFormModal(){
            let error       = [];
            $('#formModalContact').find('input[required').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
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

        function replaceIcon() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        }
        
    </script>
@endpush