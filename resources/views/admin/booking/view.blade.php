@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Booking mới';
        $submit         = 'admin.booking.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Booking';
            $submit     = 'admin.booking.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        <!-- input hidden -->
        <input type="hidden" id="booking_info_id" name="booking_info_id" value="{{ $item->id ?? null }}" />
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
            @if(!empty($message))
                <div class="js_message alert alert-{{ $message['type'] }}" style="display:inline-block;">
                    <div class="alert-body">{!! $message['message'] !!}</div>
                </div>
            @endif
            
            <div class="pageAdminWithRightSidebar_main">
                <!-- START:: Main content -->
                <div class="pageAdminWithRightSidebar_main_content">
                    
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin liên hệ</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.form.formCustomer')
                                
                            </div>
                        </div>
                        {{-- <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Thông tin hóa đơn
                                </h4>
                            </div>
                            <div class="card-body">
                                @include('admin.form.formVAT')
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Danh sách hành khách
                                </h4>
                            </div>
                            <div class="card-body">
                                @include('admin.form.formCustomerList')
                            </div>
                        </div> --}}
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin dịch vụ</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.booking.formBooking')

                            </div>
                        </div>
                        {{-- <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Phát sinh hoặc trừ lại
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#modalContact" onClick="loadFormCostMoreLess();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessage">
                                    <!-- javascript: showMessage -->
                                </div>
                                <div id="js_loadCostMoreLess_idWrite">
                                    <!-- javascript: loadCostMoreLess -->
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <!-- END:: Main content -->

                <!-- START:: Sidebar content -->
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <a href="{{ route('admin.booking.viewExport', ['id' => $item->id]) }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light" aria-label="Quay lại">Quay lại</a>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;" aria-label="Lưu">Lưu</button>
                    </div>
                    <div class="customScrollBar-y" style="height: calc(100% - 70px);border-top: 1px dashed #adb5bd;">
                        {{-- <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            
                        </div> --}}
                    </div>
                </div>
                <!-- END:: Sidebar content -->
            </div>
        </div>

    </form>
    <!-- ===== START:: Modal ===== -->
    {{-- <form id="formCostMoreLess" method="POST" action="{{ route('admin.tourOption.createOption') }}">
    @csrf
        <!-- Input Hidden -->
        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormOption_header">Thêm /Bớt chi phí</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormOption_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateCostMoreLess();" aria-label="Xác nhận">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </form> --}}
    <!-- ===== END:: Modal ===== -->
</div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            // loadCostMoreLess();
            $('.formBox_full').repeater();
        })

        function validateFormModal(){
            let error       = [];
            $('#formCostMoreLess').find('input[required]').each(function(){
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

    </script>
@endpush