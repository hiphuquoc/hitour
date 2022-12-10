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

        // function loadFormCostMoreLess(idCost = 0){
        //     $.ajax({
        //         url         : '{{ route("admin.cost.loadFormCostMoreLess") }}',
        //         type        : 'post',
        //         dataType    : 'json',
        //         data        : {
        //             '_token'            : '{{ csrf_token() }}',
        //             tour_booking_id     : $('#tour_booking_id').val(),
        //             cost_more_less_id   : idCost
        //         },
        //         success     : function(data){
        //             $('#js_loadFormOption_header').html(data.header);
        //             $('#js_loadFormOption_body').html(data.body);
        //         }
        //     });
        // }

        // function loadCostMoreLess(){
        //     $.ajax({
        //         url         : '{{ route("admin.cost.loadCostMoreLess") }}',
        //         type        : 'post',
        //         dataType    : 'html',
        //         data        : {
        //             '_token'            : '{{ csrf_token() }}',
        //             tour_booking_id     : '{{ $item->id ?? 0 }}'
        //         },
        //         success     : function(data){
        //             $('#js_loadCostMoreLess_idWrite').html(data);
        //         }
        //     });
        // }

        // function addAndUpdateCostMoreLess(){
        //     /* dataForm */
        //     let dataForm            = {
        //         type                : $('#cost_type').val(),
        //         name                : $('#cost_name').val(),
        //         quantity            : $('#cost_quantity').val(),
        //         unit_price          : $('#cost_unit_price').val()
        //     };
        //     let costMoreLessId      = $('#cost_more_less_id').val();
        //     const tmp               = validateFormModal();
        //     if(tmp==''){
        //         /* không có trường required bỏ trống */
        //         if(costMoreLessId==null||costMoreLessId==''){
        //             /* insert */
        //             $.ajax({
        //                 url         : '{{ route("admin.cost.create") }}',
        //                 type        : 'post',
        //                 dataType    : 'html',
        //                 data        : {
        //                     '_token'    : '{{ csrf_token() }}',
        //                     tour_booking_id : $('#tour_booking_id').val(),
        //                     dataForm        : dataForm
        //                 },
        //                 success     : function(data){
        //                     if(data==true){
        //                         /* thành công */
        //                         loadCostMoreLess();
        //                         showMessage('js_showMessage', 'Thêm mới Chi phí thành công!', 'success');
        //                     }else {
        //                         /* thất bại */
        //                         showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                     }
        //                 }
        //             });
        //         }else {
        //             /* update */
        //             $.ajax({
        //                 url         : '{{ route("admin.cost.update") }}',
        //                 type        : 'post',
        //                 dataType    : 'html',
        //                 data        : {
        //                     '_token'            : '{{ csrf_token() }}',
        //                     tour_booking_id     : $('#tour_booking_id').val(),
        //                     cost_more_less_id   : costMoreLessId,
        //                     dataForm            : dataForm
        //                 },
        //                 success     : function(data){
        //                     if(data==true){
        //                         /* thành công */
        //                         loadCostMoreLess();
        //                         showMessage('js_showMessage', 'Cập nhật Chi phí thành công!', 'success');
        //                     }else {
        //                         /* thất bại */
        //                         showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                     }
        //                 }
        //             });
        //         }
        //         $('#modalContact').modal('hide');
        //     }else {
        //         /* có 1 vài trường required bị bỏ trống */
        //         let messageError        = 'Các trường bắt buộc không được để trống!';
        //         $('#js_validateFormModal_message').css('display', 'block').html(messageError);
        //     }
        // }

        // function deleteCostMoreLess(idCost){
        //     if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
        //         $.ajax({
        //             url         : '{{ route("admin.cost.delete") }}',
        //             type        : 'post',
        //             dataType    : 'html',
        //             data        : {
        //                 '_token'            : '{{ csrf_token() }}',
        //                 cost_more_less_id   : idCost
        //             },
        //             success     : function(data){
        //                 if(data==true){
        //                     /* thành công */
        //                     loadCostMoreLess();
        //                     showMessage('js_showMessage', 'Xóa Chi phí thành công!', 'success');
        //                 }else {
        //                     /* thất bại */
        //                     showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                 }
        //             }
        //         });
        //     }
        // }
        
    </script>
@endpush