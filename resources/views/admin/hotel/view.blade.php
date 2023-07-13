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
                    <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Loại phòng và giá
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#formModalHotelRoom" onclick="loadFormHotelRoom();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessageHotelRoom">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadTypeRoom" class="hotelRoomBox">
                                    <!-- javascript:loadTypeRoom -->
                                    @foreach($item->rooms as $room)
                                        <div class="hotelRoomBox_item">
                                            <div class="hotelRoomBox_item_img">
                                                @foreach($room->images as $image)
                                                    <img src="{{ $image->image_small }}" />
                                                @endforeach
                                            </div>
                                            <div class="hotelRoomBox_item_info">
                                                <div class="hotelRoomBox_item_info_title highLight">{{ $room->name }}</div> 
                                                <div class="hotelRoomBox_item_info_facility">
                                                    @foreach($room->facilities as $facility)
                                                        <span>{!! !empty($facility->infoHotelRoomFacility->icon)&&!empty($facility->infoHotelRoomFacility->name) ? $facility->infoHotelRoomFacility->icon.$facility->infoHotelRoomFacility->name : null !!}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="hotelRoomBox_item_action">
                                                <div class="icon-wrapper iconAction">
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#formModalHotelRoom" onclick="loadFormHotelRoom({{ $room->id }})">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                        <div>Sửa</div>
                                                    </a>
                                                </div>
                                                <div class="icon-wrapper iconAction">
                                                    <div class="actionDelete" onclick="deleteItem('4');">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                                                        <div>Xóa</div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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
    <!-- form modal liên hệ -->
    <form id="formHotelContact" method="POST" action="#">
    @csrf
        <!-- Input Hidden -->
        {{-- <input type="hidden" id="hotel_info_id" name="hotel_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" /> --}}
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
                        <div id="js_validateFormModalHotelContact_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateHotelContact();" aria-label="Xác nhận">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- form modal phòng -->
    <form id="formHotelRoom" method="POST" action="#">
    @csrf
        <div class="modal fade" id="formModalHotelRoom" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="min-width:700px;">
                <div class="modal-content" style="width:100%;">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormHotelRoom_header">Thêm loại phòng</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormHotelRoom_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <div id="js_validateFormModalHotelRoom_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateHotelRoom();" aria-label="Xác nhận">Xác nhận</button>
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
            // loadTypeRoom('js_loadTypeRoom');
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

        function addAndUpdateHotelContact(){
            const dataForm          = {};
            $('#formHotelContact').find('input').each(function(){
                let keyInput        = $(this).attr('name');
                let valueInput      = $(this).val();
                if(valueInput!='') dataForm[keyInput]  = valueInput;
            })
            /* kiểm tra trường required bỏ trống */
            const tmp               = validateFormModal('formHotelContact');
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
                
                $('#js_validateFormModalHotelContact_message').css('display', 'block').html(messageError);
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

        function loadFormHotelRoom(hotelRoom = 0){
            const comboId       = $('#hotel_info_id').val();
            const type          = '{{ $type }}';
            $.ajax({
                url         : '{{ route("admin.hotelRoom.loadFormHotelRoom") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    hotel_info_id   : comboId,
                    hotel_room_id   : hotelRoom
                },
                success     : function(data){
                    $('#js_loadFormHotelRoom_header').html(data.header);
                    $('#js_loadFormHotelRoom_body').html(data.body);
                }
            });
        }

        // function deleteOptionPrice(id){
        //     if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
        //         $.ajax({
        //             url         : '{{ route("admin.hotelRoom.deleteRoom") }}',
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
        //                     loadTypeRoom('js_loadTypeRoom');
        //                     showMessage('js_showMessage', 'Xóa Option & Giá thành công!', 'success');
        //                 }else {
        //                     /* thất bại */
        //                     showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
        //                 }
        //             }
        //         });
        //     }
        // }

        function validateFormModal(idForm){
            let error       = [];
            $('#'+idForm).find('input[required]', 'textarea[required]').each(function(){
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

        function addAndUpdateHotelRoom(){
            /* kiểm tra trường required bỏ trống */
            const tmp               = validateFormModal('formHotelRoom');
            if(tmp==''){
                const idHotel       = $('#hotel_info_id').val();
                var dataForm            = {};
                /* lấy tên phòng */ 
                dataForm['room_name']   = $('#room_name').val();
                /* lấy kích thước */ 
                dataForm['room_size']   = $('#room_size').val();
                /* lấy số người tối đa */ 
                dataForm['room_number_people']    = $('#room_number_people').val();
                /* lấy giá */ 
                dataForm['room_price']  = $('#room_price').val();
                /* lấy ảnh */
                var images              = {};
                var count               = 0;
                $('#formHotelRoom').find('input[name*=room_images]').each(function(){
                    if($(this).val().length>0) {
                        images[count]   = $(this).val();
                        ++count;
                    }
                });
                dataForm['room_images']  = images;
                /* lấy chi tiết tiện nghi */
                var detail = [];
                $('#formHotelRoom').find('[name^="room_details"]').filter(function() {
                    return /\[\d+\]\[name\]$/.test(this.name); // Lọc các input có dạng room_detail[0][name]
                }).each(function() {
                    var key = this.name.match(/\[(\d+)\]/)[1]; // Lấy số từ giá trị name
                    var value = $(this).val(); // Lấy giá trị của input
                    var contentName = 'room_details[' + key + '][detail]'; // Tạo name tương ứng với content
                    var contentValue = $('[name="' + contentName + '"]').val(); // Lấy giá trị của input content
                    var item = {
                        'name': value,
                        'detail': contentValue
                    };
                    detail[key] = item; // Gán giá trị vào mảng dataForm với key tương ứng
                });
                dataForm['room_details']  = detail;
                /* lấy tiện nghi chungg */
                dataForm['room_facilities'] = $('#room_facilities option:selected').map(function() {
                    return $(this).val();
                }).get();
                /* truyền ajax xử lý */
                if(dataForm['hotel_room_id']==null){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.hotelRoom.createRoom") }}',
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
                                // loadContactOfHotel('js_loadHotelContact');
                                showMessage('js_showMessageHotelRoom', 'Thêm mới Phòng thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessageHotelRoom', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }else {
                    // /* update */
                    // $.ajax({
                    //     url         : '{{ route("admin.hotel.updateContact") }}',
                    //     type        : 'post',
                    //     dataType    : 'html',
                    //     data        : {
                    //         '_token'    : '{{ csrf_token() }}',
                    //         hotel_info_id   : idHotel,
                    //         dataForm    : dataForm
                    //     },
                    //     success     : function(data){
                            
                    //         if(data==true){
                    //             /* thành công */
                    //             loadContactOfHotel('js_loadHotelContact');
                    //             showMessage('js_showMessage', 'Cập nhật Liên hệ thành công!', 'success');
                    //         }else {
                    //             /* thất bại */
                    //             showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                    //         }
                    //     }
                    // });
                }
                $('#formModalHotelRoom').modal('hide');
            }else {
                /* có 1 vài trường required bị bỏ trống */
                let messageError        = 'Không được bỏ trống trường ';
                tmp.forEach(function(value, index, array){
                    switch(value) {
                        case 'room_name':
                            messageError    += '<strong>Tên phòng</strong>';
                            break;
                        case 'room_size':
                            messageError    += '<strong>Kích thước phòng</strong>';
                            break;
                        case 'room_price':
                            messageError    += '<strong>Giá phòng</strong>';
                            break;
                        case 'room_number_people':
                            messageError    += '<strong>Số người tối đa</strong>';
                            break;
                        default:
                    }
                    if(index!=parseInt(tmp.length-1)) messageError += ', ';
                })
                $('#js_validateFormModalHotelRoom_message').css('display', 'block').html(messageError);
            }
        }
        
    </script>
@endpush