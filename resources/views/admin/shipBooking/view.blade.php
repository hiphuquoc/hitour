@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Booking Tàu mới';
        $submit         = 'main.shipBooking.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Booking Tàu';
            $submit     = 'admin.shipBooking.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        <!-- input hidden -->
        <input type="hidden" id="ship_booking_id" name="ship_booking_id" value="{{ $item->id ?? null }}" />
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
                        @include('admin.shipBooking.formBookingTicket')
                    </div>
                </div>
                <!-- END:: Main content -->

                <!-- START:: Sidebar content -->
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <a href="{{ route('admin.shipBooking.viewExport', ['id' => $item->id]) }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light">Quay lại</a>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;" aria-label="Lưu">Lưu</button>
                    </div>
                    <div class="customScrollBar-y" style="height: calc(100% - 70px);border-top:1px dashed #adb5bd;">
                        {{-- <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            
                        </div> --}}
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