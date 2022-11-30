@extends('admin.layouts.main')
@section('content')

    @include('admin.booking.confirmBooking', compact('item'))

    @if(!empty($item->status->relationAction))
    <div class="actionBookingBox">
        <div class="actionBookingBox_item" style="text-align:center;font-size:1.1rem;background:{{ $item->status->color }};color:#fff;">
            {{ $item->status->name }}
        </div>
        @foreach($item->status->relationAction as $action)
            <div class="actionBookingBox_item">
                <span style="color:{{ $action->action->color }};">{!! $action->action->icon !!}</span>{{ $action->action->name }}
            </div>
        @endforeach

    </div>
    @endif
</div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            loadCostMoreLess();
            $('.formBox_full').repeater();
        })
        

        function loadFormCostMoreLess(idCost = 0){
            $.ajax({
                url         : '{{ route("admin.cost.loadFormCostMoreLess") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    tour_booking_id : $('#tour_booking_id').val()
                },
                success     : function(data){
                    $('#js_loadFormOption_header').html(data.header);
                    $('#js_loadFormOption_body').html(data.body);
                }
            });
        }

        function validateFormModal(){
            let error       = [];
            $('#formCostMoreLess').find('input[required]').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }

        function loadCostMoreLess(){
            $.ajax({
                url         : '{{ route("admin.cost.loadCostMoreLess") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'            : '{{ csrf_token() }}',
                    tour_booking_id     : '{{ $item->id ?? 0 }}'
                },
                success     : function(data){
                    $('#js_loadCostMoreLess_idWrite').html(data);
                }
            });
        }

        function addAndUpdateCostMoreLess(){
            /* dataForm */
            let dataForm            = {
                type            : $('#cost_type').val(),
                name            : $('#cost_name').val(),
                quantity        : $('#cost_quantity').val(),
                unit_price      : $('#cost_unit_price').val()
            };
            const tmp               = validateFormModal();
            if(tmp==''){
                /* không có trường required bỏ trống */
                // if(dataForm['tour_option_id']==null||dataForm['tour_option_id']==''){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.cost.create") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            tour_booking_id : $('#tour_booking_id').val(),
                            dataForm        : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadCostMoreLess();
                                showMessage('js_showMessage', 'Thêm mới Chi phí thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                // }else {
                //     /* update */
                //     $.ajax({
                //         url         : '{{ route("admin.tourOption.updateOption") }}',
                //         type        : 'post',
                //         dataType    : 'html',
                //         data        : {
                //             '_token'    : '{{ csrf_token() }}',
                //             dataForm    : dataForm
                //         },
                //         success     : function(data){
                //             if(data==true){
                //                 /* thành công */
                //                 loadOptionPrice('js_loadOptionPrice');
                //                 showMessage('js_showMessage', 'Cập nhật Option & Giá thành công!', 'success');
                //             }else {
                //                 /* thất bại */
                //                 showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                //             }
                //         }
                //     });
                // }
                // $('#modalContact').modal('hide');
            }else {
                /* có 1 vài trường required bị bỏ trống */
                let messageError        = 'Các trường bắt buộc không được để trống!';
                $('#js_validateFormModal_message').css('display', 'block').html(messageError);
            }
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