@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Hotel mới';
        $submit         = 'admin.hotel.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Hotel';
            $submit     = 'admin.hotel.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate enctype="multipart/form-data">
    @csrf
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

                                @include('admin.hotel.formPage')

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
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin Hotel</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.hotel.formInfo')

                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <!-- Danh sách liên hệ -->
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Danh sách liên hệ
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#formModal" onClick="setValueFormInsert();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessage">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadHotelContact">
                                    <!-- Load Ajax -->
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Tùy chọn và Giá
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#formModal" onclick="loadFormOption();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessage">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadOptionPrice">
                                    <!-- javascript:loadOptionPrice -->
                                    Không có dữ liệu phù hợp!
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="pageAdminWithRightSidebar_main_content_item width100 repeater">
                        <div data-repeater-list="contents">
                            @include('admin.hotel.formContent', [
                                'contents' => $item->contents ?? null
                            ])
                        </div>
                        <div class="card">
                            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Thêm</span>
                            </button>
                        </div>
                    </div>
                    {{-- <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Câu hỏi thường gặp</h4>
                            </div>
                            <div class="card-body">
                                
                                @include('admin.form.formAnswer', compact('item'))
                                
                            </div>
                        </div>
                    </div> --}}
                </div>
                <!-- END:: Main content -->

                <!-- START:: Sidebar content -->
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <a href="{{ route('admin.hotel.list') }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light">Quay lại</a>
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
    <!-- ===== START:: Modal ===== -->
    <form id="formHotelContact" method="POST" action="{{ route('admin.hotel.createContact') }}">
    @csrf
        <!-- Input Hidden -->
        {{-- <input type="hidden" id="Combo_info_id" name="Combo_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" /> --}}
        <div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormModal_header">Thêm Option & Giá</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormModal_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <div id="js_validateFormModal_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateHotelContact();" aria-label="Xác nhận">Xác nhận</button>
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
            // loadOptionPrice('js_loadOptionPrice');
            closeMessage();
            loadContactOfHotel('js_loadHotelContact');
        })

        $('.repeater').repeater();

        function closeMessage(){
            setTimeout(() => {
                $('.js_message').css('display', 'none');
            }, 5000);
        }

        function submitForm(idForm){
            const elemt = $('#'+idForm);
            if(elemt.valid()) elemt.submit();
        }

        // function addAndUpdateHotelContact(){
        //     const tmp                   = validateFormModal();
        //     if(tmp==''){
        //         /* date range */
        //         let date_range          = [];
        //         $('#formHotelContact').find('input[name*=date_range]').each(function(){
        //             date_range.push($(this).val());
        //         });
        //         /* data apply_age */
        //         let apply_age           = [];
        //         $('#formHotelContact').find('input[name*=apply_age]').each(function(){
        //             apply_age.push($(this).val());
        //         });
        //         /* data price */
        //         let price               = [];
        //         $('#formHotelContact').find('input[name*=price]').each(function(){
        //             price.push($(this).val());
        //         });
        //         /* data profit */
        //         let profit              = [];
        //         $('#formHotelContact').find('input[name*=profit]').each(function(){
        //             profit.push($(this).val());
        //         });
        //         /* gộp dataForm đầy đủ */
        //         let dataForm            = {
        //             departure_id    : $('#departure_id').val(),
        //             days            : $('#days').val(),
        //             nights            : $('#nights').val(),
        //             combo_info_id    : $('#combo_info_id').val(),
        //             combo_option_id  : $('#combo_option_id').val(),
        //             name            : $('#combo_option').val(),
        //             date_range,
        //             apply_age,
        //             price,
        //             profit
        //         };
        //         /* không có trường required bỏ trống */
        //         if(dataForm['combo_option_id']==null||dataForm['combo_option_id']==''){
        //             /* insert */
        //             $.ajax({
        //                 url         : '{{ route("admin.hotelOption.createOption") }}',
        //                 type        : 'post',
        //                 dataType    : 'html',
        //                 data        : {
        //                     '_token'    : '{{ csrf_token() }}',
        //                     dataForm    : dataForm
        //                 },
        //                 success     : function(data){
        //                     if(data==true){
        //                         /* thành công */
        //                         loadOptionPrice('js_loadOptionPrice');
        //                         showMessage('js_showMessage', 'Thêm mới Option & Giá thành công!', 'success');
        //                     }else {
        //                         /* thất bại */
        //                         showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                     }
        //                 }
        //             });
        //         }else {
        //             /* update */
        //             $.ajax({
        //                 url         : '{{ route("admin.hotelOption.updateOption") }}',
        //                 type        : 'post',
        //                 dataType    : 'html',
        //                 data        : {
        //                     '_token'    : '{{ csrf_token() }}',
        //                     dataForm    : dataForm
        //                 },
        //                 success     : function(data){
        //                     if(data==true){
        //                         /* thành công */
        //                         loadOptionPrice('js_loadOptionPrice');
        //                         showMessage('js_showMessage', 'Cập nhật Option & Giá thành công!', 'success');
        //                     }else {
        //                         /* thất bại */
        //                         showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                     }
        //                 }
        //             });
        //         }
        //         $('#formModal').modal('hide');
        //     }else {
        //         /* có 1 vài trường required bị bỏ trống */
        //         let messageError        = 'Các trường bắt buộc không được để trống!';
        //         $('#js_validateFormModal_message').css('display', 'block').html(messageError);
        //     }
        // }

        function addAndUpdateHotelContact(){
            const dataForm          = {};
            $('#formHotelContact').find('input').each(function(){
                let keyInput        = $(this).attr('name');
                let valueInput      = $(this).val();
                if(valueInput!='') dataForm[keyInput]  = valueInput;
            })
            /* kiểm tra trường required bỏ trống */
            const tmp               = validateFormModal();
            if(tmp==''){
                const idHotel       = $('#hotel_info_id').val();
                if(dataForm['hotel_contact_id']==null){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.hotel.createContact") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'        : '{{ csrf_token() }}',
                            hotel_info_id   : idHotel,
                            dataForm        : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadContactOfHotel('js_loadHotelContact');
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
                        url         : '{{ route("admin.hotel.updateContact") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            hotel_info_id   : idHotel,
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            
                            if(data==true){
                                /* thành công */
                                loadContactOfHotel('js_loadHotelContact');
                                showMessage('js_showMessage', 'Cập nhật Liên hệ thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }
                $('#formModal').modal('hide');
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
                            messageError    += '<strong>Zalo</strong>';
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

        function showMessage(idWrite, message, type = 'success'){
            if(message!=''||message!=null){
                let htmlMessage = '<div class="alert alert-'+type+'"><div class="alert-body">'+message+'</div></div>';
                $('#'+idWrite).html(htmlMessage);
                setTimeout(() => {
                    $('#'+idWrite).html('');
                }, 5000);
            }
        }

        // function loadOptionPrice(idWrite){
        //     $.ajax({
        //         url         : '{{ route("admin.hotelOption.loadOptionPrice") }}',
        //         type        : 'post',
        //         dataType    : 'html',
        //         data        : {
        //             '_token'        : '{{ csrf_token() }}',
        //             combo_info_id    : '{{ !empty($item->id)&&$type!="copy" ? $item->id : 0 }}'
        //         },
        //         success     : function(data){
        //             $('#'+idWrite).html(data);
        //         }
        //     });
        // }

        // function loadFormOption(comboOption = 0){
        //     const comboId    = $('#combo_info_id').val();
        //     const type      = '{{ $type }}';
        //     $.ajax({
        //         url         : '{{ route("admin.hotelOption.loadFormOption") }}',
        //         type        : 'post',
        //         dataType    : 'json',
        //         data        : {
        //             '_token'        : '{{ csrf_token() }}',
        //             combo_info_id    : comboId,
        //             combo_option_id  : comboOption
        //         },
        //         success     : function(data){
        //             $('#js_loadFormOption_header').html(data.header);
        //             $('#js_loadFormOption_body').html(data.body);
        //         }
        //     });
        // }

        // function deleteOptionPrice(id){
        //     if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
        //         $.ajax({
        //             url         : '{{ route("admin.hotelOption.deleteOption") }}',
        //             type        : 'post',
        //             dataType    : 'html',
        //             data        : {
        //                 '_token'    : '{{ csrf_token() }}',
        //                 id      : id
        //             },
        //             success     : function(data){
        //                 if(data==true){
        //                     /* thành công */
        //                     $('#optionPrice_'+id).remove();
        //                     loadOptionPrice('js_loadOptionPrice');
        //                     showMessage('js_showMessage', 'Xóa Option & Giá thành công!', 'success');
        //                 }else {
        //                     /* thất bại */
        //                     showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                 }
        //             }
        //         });
        //     }
        // }

        function validateFormModal(){
            let error       = [];
            $('#formHotelContact').find('input[required').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }

        function loadContactOfHotel(idWrite){
            $.ajax({
                url         : '{{ route("admin.hotel.loadContact") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    hotel_info_id   : '{{ $item->id ?? 0 }}',
                    type            : '{{ $type }}'
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }

        function setValueFormInsert(){
            const partnerId     = $('#hotel_info_id').val();
            if(partnerId!=''){
                loadFormHotelContact(0);
            }else {
                $('#js_loadFormHotelContact_body').html('<div style="margin-top:1rem;font-weight:600;">Vui lòng tạo và lưu Đối tác trước khi tạo Liên hệ!</div>');
            }
        }

        function loadFormHotelContact(id){
            $.ajax({
                url         : '{{ route("admin.hotel.loadFormContact") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    id          : id
                },
                success     : function(data){
                    $('#js_loadFormModal_header').html(data.header);
                    $('#js_loadFormModal_body').html(data.body);
                }
            });
        }

        function deleteHotelContact(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : '{{ route("admin.hotel.deleteContact") }}',
                    type        : 'post',
                    dataType    : 'html',
                    data        : {
                        '_token'    : '{{ csrf_token() }}',
                        id      : id
                    },
                    success     : function(data){
                        if(data==true){
                            /* thành công */
                            $('#hotelContact_'+id).remove();
                            loadContactOfHotel('js_loadHotelContact');
                            showMessage('js_showMessage', 'Xóa Liên hệ thành công!', 'success');
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