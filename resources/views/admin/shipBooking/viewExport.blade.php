@extends('admin.layouts.main')
@section('content')
    <!-- Thông báo các thao tác -->
    <div id="js_showMessage_elemt">
        <!-- JS ShowMessage -->
    </div>
    <!-- hidden ship_booking_id -->
    <input type="hidden" id="ship_booking_id" name="ship_booking_id" value="{{ $item->id }}" />
    <div class="columnBox">
        <!-- giao diện xác nhận -->
        <div class="columnBox_item" id="js_loadViewExport_idWrite">
            @include('admin.shipBooking.confirmBooking', compact('item'))
        </div>
        <!-- Thanh sidebar thao tác -->
        @if(!empty($item->status->relationAction))
            <div class="columnBox_item" style="flex:0 0 200px;">
                <div class="actionBookingBox">
                    <div class="actionBookingBox_item" style="text-align:center;font-size:1.1rem;background:{{ $item->status->color }};color:#fff;">
                        {{ $item->status->name }}
                    </div>
                    @foreach($item->status->relationAction as $action)
                        @php
                            switch ($action->action->name) {
                                case 'Gửi xác nhận Email':
                                    $xhtmlAction = '<div id="sendMailConfirm" class="actionBookingBox_item">
                                                        <span style="color:'.$action->action->color.';">'.$action->action->icon.'</span>'.$action->action->name.'
                                                    </div>';
                                    break;
                                case 'Gửi xác nhận Zalo':
                                    $xhtmlAction = '<a href="'.route('admin.shipBooking.viewExportHtml', ['id' => $item->id]).'" target="_blank" class="actionBookingBox_item">
                                                        <span style="color:'.$action->action->color.';">'.$action->action->icon.'</span>'.$action->action->name.'
                                                    </a>';
                                    break;
                                default:
                                    $xhtmlAction = '<a href="#" target="_blank" class="actionBookingBox_item">
                                                        <span style="color:'.$action->action->color.';">'.$action->action->icon.'</span>'.$action->action->name.'
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

        function validateFormModal(){
            let error       = [];
            $('#formCostMoreLess').find('input[required]').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }

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

        function showMessage(idWrite, message, type = 'success'){
            if(message!=''||message!=null){
                let htmlMessage = '<div class="alert alert-'+type+'"><div class="alert-body">'+message+'</div></div>';
                $('#'+idWrite).html(htmlMessage);
                setTimeout(() => {
                    $('#'+idWrite).html('');
                }, 5000);
            }
        }
        // // ALERT WITH AJAX REQUEST
        // $('#testAjaxRequest').on('click', function(){
        //     Swal.fire({
        //         title: 'test',
        //         input: '<input type="text" />',
        //         inputAttributes: {
        //             autocapitalize: 'off'
        //         },
        //         showCancelButton: true,
        //         confirmButtonText: 'Look up',
        //         showLoaderOnConfirm: true,
        //         // preConfirm: login => {
        //         // return fetch('//api.github.com/users/' + login)
        //         //     .then(response => {
        //         //     if (!response.ok) {
        //         //         throw new Error(response.statusText);
        //         //     }
        //         //     return response.json();
        //         //     })
        //         //     .catch(error => {
        //         //     Swal.showValidationMessage('Request failed:' + error);
        //         //     });
        //         // },
        //         // backdrop: true,
        //         // allowOutsideClick: () => !Swal.isLoading()
        //     }).then(result => {
        //         // if (result.isConfirmed) {
        //         // Swal.fire({
        //         //     title: result.value.login + "'s avatar",
        //         //     imageUrl: result.value.avatar_url,
        //         //     customClass: {
        //         //     confirmButtonText: 'Close me!',
        //         //     confirmButton: 'btn btn-primary'
        //         //     }
        //         // });
        //         // }
        //         console.log(123);
        //     });
        // });
        // ALERT WITH AJAX REQUEST
        $('#sendMailConfirm').on('click', function(){
            const shipBookingId     = $('#ship_booking_id').val();
            $.ajax({
                url         : '{{ route("admin.shipBooking.getExpirationAt") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    ship_booking_id     : shipBookingId
                },
                success     : function(data){
                    Swal.fire({
                        title: '<div style="font-weight:bold;"">Ngày hết hạn của Booking?</div>', 
                        html: '<div style="margin-top:1rem;"><input id="expiration_at" name="expiration_at" text="text" class="form-control flatpickr-basic flatpickr-input active" placeholder="Chọn giờ - ngày - tháng" value="'+data+'" readonly="readonly" required /></div>',  
                        confirmButtonText: "Xác nhận"
                    }).then(result => {
                        const shipBookingId     = $('#ship_booking_id').val();
                        const expirationAt      = $('#expiration_at').val();
                        if(expirationAt!=''){
                            $.ajax({
                                url         : '{{ route("admin.shipBooking.sendMailConfirm") }}',
                                type        : 'get',
                                dataType    : 'html',
                                data        : {
                                    ship_booking_id     : shipBookingId,
                                    expiration_at       : expirationAt
                                },
                                success     : function(flag){
                                    if(flag==true){
                                        /* thành công */
                                        showMessage('Thành công!', 'Đã gửi email xác nhận cho khách', 'success');
                                        /* tải lại trang để hiển thị mới ngày hết hạn booking */
                                        $.ajax({
                                            url         : '{{ route("admin.shipBooking.loadViewExport") }}',
                                            type        : 'get',
                                            dataType    : 'html',
                                            data        : {
                                                ship_booking_id     : $('#ship_booking_id').val()
                                            },
                                            success     : function(data){
                                                $('#js_loadViewExport_idWrite').html(data);
                                            }
                                        });
                                    }else {
                                        /* thất bại */
                                        // showMessage('Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                                    }
                                }
                            });
                        }else {
                            /* thông báo lỗi */
                        }
                    });
                    $('#expiration_at').flatpickr({
                        enableTime: true,
                        dateFormat: "Y-m-d H:i",
                        altInput: true,
                        altFormat: "D, j \\t\\h\\á\\n\\g m \\l\\ú\\c h:i K",
                    });
                }
            });
        });

        function showMessage(title, message, type = 'success'){
            if(message!=''||message!=null){
                let htmlMessage = '<div class="toastBox '+type+'"><div class="toastBox_title">'+title+'</div><div class="toastBox_content">'+message+'</div></div>';
                $('#js_showMessage_elemt').html(htmlMessage);
                setTimeout(() => {
                    $('#js_showMessage_elemt').html('');
                }, 5000);
            }
        }
    </script>
@endpush