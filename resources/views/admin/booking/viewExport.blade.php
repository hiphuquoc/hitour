@extends('admin.layouts.main')
@section('content')
    <!-- Thông báo các thao tác -->
    @include('admin.template.toast')
    <!-- hidden booking_info_id -->
    <input type="hidden" id="booking_info_id" name="booking_info_id" value="{{ $item->id }}" />
    <div class="columnBox">
        <!-- giao diện xác nhận -->
        <div class="columnBox_item" id="js_loadViewExport_idWrite">
            @include('admin.booking.confirmBooking', compact('item'))
        </div>
        <!-- Thanh sidebar thao tác -->
        @if(!empty($item->status->actions))
            <div class="columnBox_item" style="flex:0 0 200px;">
                <div class="actionBookingBox">  
                    <div class="actionBookingBox_item" style="text-align:center;font-size:1.1rem;background:{{ $item->status->color }};color:#fff;">
                        {{ $item->status->name }}
                    </div>
                    @foreach($item->status->actions as $action)
                        @php
                            switch ($action->infoAction->name) {
                                case 'Gửi xác nhận Email':
                                    $xhtmlAction = '<div id="sendMailConfirm" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </div>';
                                    break;
                                case 'Gửi xác nhận Zalo':
                                    $xhtmlAction = '<div id="sendZaloConfirm" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </div>';
                                    break;
                                case 'Gia hạn thanh toán':
                                    $xhtmlAction = '<div id="paymentExtension" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </div>';
                                    break;
                                case 'Hủy booking':
                                    $xhtmlAction = '<div id="cancelBooking" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </div>';
                                    break;
                                case 'Khôi phục booking':
                                    $xhtmlAction = '<div id="restoreBooking" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </div>';
                                    break;
                                case 'Chỉnh sửa':
                                    $xhtmlAction = '<a href="'.route('admin.booking.view', ['id' => $item->id]).'" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </a>';
                                    break;
                                default:
                                    $xhtmlAction = '<a href="#" target="_blank" class="actionBookingBox_item">
                                                        <span style="color:'.$action->infoAction->color.';">'.$action->infoAction->icon.'</span>'.$action->infoAction->name.'
                                                    </a>';
                                    break;
                            }
                            echo $xhtmlAction;
                        @endphp
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            // loadCostMoreLess();
            $('.formBox_full').repeater();
        })

        /* Xác nhận Email */
        $('#sendMailConfirm').on('click', function(){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("admin.booking.getExpirationAt") }}',
                type        : 'get',
                dataType    : 'json',
                data        : {
                    booking_info_id     : idBooking
                },
                success     : function(data){
                    /* trường hợp booking chưa có yêu cầu cọc thì lấy tổng tiền */
                    let total       = data['required_deposit'];
                    if(total==null||total==''){
                        total       = $('#total').val();
                    }
                    Swal.fire({
                        title: '<div style="font-weight:bold;">Ngày hết hạn của Booking?</div>', 
                        html: '<label for="expiration_at" style="margin:1rem 0 0.5rem 0;font-size:1.1rem;font-weight:bold;float:left;">Hạn booking</label><div><input id="expiration_at" name="expiration_at" text="text" class="form-control flatpickr-basic flatpickr-input active" placeholder="Chọn giờ - ngày - tháng" value="'+data['expiration_at']+'" readonly="readonly" required /></div><label for="required_deposit" style="margin:1rem 0 0.5rem 0;font-size:1.1rem;font-weight:bold;float:left;">Yêu cầu cọc</label><div><input id="required_deposit" name="required_deposit" text="number" class="form-control" placeholder="0" value="'+total+'" required /></div>',  
                        confirmButtonText: "Xác nhận"
                    }).then(result => {
                        if (result.isConfirmed) {
                            const idBooking         = $('#booking_info_id').val();
                            const expirationAt      = $('#expiration_at').val();
                            const requiredDeposit   = $('#required_deposit').val();
                            if(expirationAt!=''&&typeof expirationAt!='undefined'){
                                $.ajax({
                                    url         : '{{ route("admin.booking.sendMailConfirm") }}',
                                    type        : 'get',
                                    dataType    : 'html',
                                    data        : {
                                        booking_info_id     : idBooking,
                                        expiration_at       : expirationAt,
                                        required_deposit    : requiredDeposit
                                    },
                                    success     : function(data){
                                        /* tải lại trang */
                                        location.reload();
                                    }
                                });
                            }
                        }
                    });
                    $('#expiration_at').flatpickr({
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        altInput: true,
                        altFormat: "D, j \\t\\h\\á\\n\\g m Y \\l\\ú\\c h:i K",
                    });
                }
            });
        });
        /* Xác nhận Zalo */
        $('#sendZaloConfirm').on('click', function(){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("admin.booking.getExpirationAt") }}',
                type        : 'get',
                dataType    : 'json',
                data        : {
                    booking_info_id     : idBooking
                },
                success     : function(data){
                    /* trường hợp booking chưa có yêu cầu cọc thì lấy tổng tiền */
                    let total       = data['required_deposit'];
                    if(total==null||total==''){
                        total       = $('#total').val();
                    }
                    Swal.fire({
                        title: '<div style="font-weight:bold;">Ngày hết hạn của Booking?</div>', 
                        html: '<label for="expiration_at" style="margin:1rem 0 0.5rem 0;font-size:1.1rem;font-weight:bold;float:left;">Hạn booking</label><div><input id="expiration_at" name="expiration_at" text="text" class="form-control flatpickr-basic flatpickr-input active" placeholder="Chọn giờ - ngày - tháng" value="'+data['expiration_at']+'" readonly="readonly" required /></div><label for="required_deposit" style="margin:1rem 0 0.5rem 0;font-size:1.1rem;font-weight:bold;float:left;">Yêu cầu cọc</label><div><input id="required_deposit" name="required_deposit" text="number" class="form-control" placeholder="0" value="'+total+'" required /></div>',  
                        confirmButtonText: "Xác nhận"
                    }).then(result => {
                        if (result.isConfirmed) {
                            const idBooking         = $('#booking_info_id').val();
                            const expirationAt      = $('#expiration_at').val();
                            const requiredDeposit   = $('#required_deposit').val();
                            if(expirationAt!=''&&typeof expirationAt!='undefined'){
                                $.ajax({
                                    url         : '{{ route("admin.booking.createPdfConfirm") }}',
                                    type        : 'get',
                                    dataType    : 'html',
                                    data        : {
                                        booking_info_id     : idBooking,
                                        expiration_at       : expirationAt,
                                        required_deposit    : requiredDeposit
                                    },
                                    success     : function(data){
                                        window.location.href = "{{ route('admin.booking.viewExportHtml', ['id' => $item->id]) }}";
                                    }
                                });
                            }
                        }
                    });
                    $('#expiration_at').flatpickr({
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        altInput: true,
                        altFormat: "D, j \\t\\h\\á\\n\\g m Y \\l\\ú\\c h:i K",
                    });
                }
            });
        });
        /* Gia hạn thanh toán */
        $('#paymentExtension').on('click', function(){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("admin.booking.getExpirationAt") }}',
                type        : 'get',
                dataType    : 'json',
                data        : {
                    booking_info_id     : idBooking
                },
                success     : function(data){
                    Swal.fire({
                        title: '<div style="font-weight:bold;">Ngày hết hạn của Booking?</div>', 
                        html: '<div style="margin-top:1rem;"><input id="expiration_at" name="expiration_at" text="text" class="form-control flatpickr-basic flatpickr-input active" placeholder="Chọn giờ - ngày - tháng" value="'+data['expiration_at']+'" readonly="readonly" required /></div>',  
                        confirmButtonText: "Xác nhận"
                    }).then(result => {
                        if (result.isConfirmed) {
                            const idBooking         = $('#booking_info_id').val();
                            const expirationAt      = $('#expiration_at').val();
                            if(expirationAt!=''&&typeof expirationAt!='undefined'){
                                $.ajax({
                                    url         : '{{ route("admin.booking.paymentExtension") }}',
                                    type        : 'get',
                                    dataType    : 'html',
                                    data        : {
                                        booking_info_id     : idBooking,
                                        expiration_at       : expirationAt
                                    },
                                    success     : function(data){
                                        location.reload();
                                    }
                                });
                            }
                        }
                    });
                    $('#expiration_at').flatpickr({
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        altInput: true,
                        altFormat: "D, j \\t\\h\\á\\n\\g m Y \\l\\ú\\c h:i K",
                    });
                }
            });
        });
        /* Hủy booking */
        $('#cancelBooking').on('click', function(){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("admin.booking.cancelBooking") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    booking_info_id     : idBooking
                },
                success     : function(data){
                    location.reload();
                }
            });
        });
        /* Khôi phục booking */
        $('#restoreBooking').on('click', function(){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("admin.booking.restoreBooking") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    booking_info_id     : idBooking
                },
                success     : function(data){
                    location.reload();
                }
            });
        });
        

        // function loadFormCostMoreLess(idCost = 0){
        //     $.ajax({
        //         url         : '{{ route("admin.cost.loadFormCostMoreLess") }}',
        //         type        : 'post',
        //         dataType    : 'json',
        //         data        : {
        //             '_token'        : '{{ csrf_token() }}',
        //             tour_booking_id : $('#tour_booking_id').val()
        //         },
        //         success     : function(data){
        //             $('#js_loadFormOption_header').html(data.header);
        //             $('#js_loadFormOption_body').html(data.body);
        //         }
        //     });
        // }

        // function validateFormModal(){
        //     let error       = [];
        //     $('#formCostMoreLess').find('input[required]').each(function(){
        //         if($(this).val()==''){
        //             error.push($(this).attr('name'));
        //         }
        //     })
        //     return error;
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
        //         type            : $('#cost_type').val(),
        //         name            : $('#cost_name').val(),
        //         quantity        : $('#cost_quantity').val(),
        //         unit_price      : $('#cost_unit_price').val()
        //     };
        //     const tmp               = validateFormModal();
        //     if(tmp==''){
        //         /* không có trường required bỏ trống */
        //         // if(dataForm['tour_option_id']==null||dataForm['tour_option_id']==''){
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
        //         // }else {
        //         //     /* update */
        //         //     $.ajax({
        //         //         url         : '{{ route("admin.tourOption.updateOption") }}',
        //         //         type        : 'post',
        //         //         dataType    : 'html',
        //         //         data        : {
        //         //             '_token'    : '{{ csrf_token() }}',
        //         //             dataForm    : dataForm
        //         //         },
        //         //         success     : function(data){
        //         //             if(data==true){
        //         //                 /* thành công */
        //         //                 loadOptionPrice('js_loadOptionPrice');
        //         //                 showMessage('js_showMessage', 'Cập nhật Option & Giá thành công!', 'success');
        //         //             }else {
        //         //                 /* thất bại */
        //         //                 showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //         //             }
        //         //         }
        //         //     });
        //         // }
        //         // $('#modalContact').modal('hide');
        //     }else {
        //         /* có 1 vài trường required bị bỏ trống */
        //         let messageError        = 'Các trường bắt buộc không được để trống!';
        //         $('#js_validateFormModal_message').css('display', 'block').html(messageError);
        //     }
        // }

        // function showMessage(idWrite, message, type = 'success'){
        //     if(message!=''||message!=null){
        //         let htmlMessage = '<div class="alert alert-'+type+'"><div class="alert-body">'+message+'</div></div>';
        //         $('#'+idWrite).html(htmlMessage);
        //         setTimeout(() => {
        //             $('#'+idWrite).html('');
        //         }, 5000);
        //     }
        // }
    </script>
@endpush