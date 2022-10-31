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
</div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            // closeMessage();
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
            $('#formTourOption').find('input[required').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }
        
    </script>
@endpush