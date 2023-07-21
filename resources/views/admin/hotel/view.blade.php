@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Hotel mới <a href="#" data-bs-toggle="modal" data-bs-target="#formModalDownloadHotelInfo" style="color:#26cf8e;font-size:1.05rem;font-weight:normal;"><i class="fa-solid fa-download"></i> Tải tự động</a>';
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
                {!! $titlePage !!}
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
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#formModal" onClick="setValueFormHotelContact();"></i>
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
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#formModalHotelRoom" onclick="setValueFormHotelRoom();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessageHotelRoom">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadHotelRoom" class="hotelRoomBox">
                                    <!-- javascript:loadHotelRoom -->
                                    Không có dữ liệu phù hợp!
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item width100 repeater">
                        <div data-repeater-list="contents">
                            @include('admin.hotel.formContent', [
                                'contents' => old('contents') ?? $item['contents'] ?? null
                            ])
                        </div>
                        <div class="card">
                            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Thêm</span>
                            </button>
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
                        <a href="{{ route('admin.hotel.list') }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light">Quay lại</a>
                        <button type="button" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;" aria-label="Lưu">Lưu</button>
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
                        <!-- Form Image -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.hotel.formImage')
                        </div>
                    </div>
                </div>
                <!-- END:: Sidebar content -->
            </div>
        </div>

    </form>
    <!-- ===== START:: Modal ===== -->
    <!-- form modal tải ảnh khách sạn -->
    <form id="formDownloadImageHotelInfo" method="POST" action="#">
    @csrf
        <div class="modal fade" id="formModalDownloadImageHotelInfo" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="min-width:600px;">
                    <div class="modal-header bg-transparent">
                        <h4>Tải tự động ảnh khách sạn</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label" for="content_image">Thẻ html chứa đường dẫn ảnh (Mytour)</label>
                        <textarea class="form-control" id="content_image"  name="content_image" rows="2">{{ $item['content_image'] ?? null }}</textarea>
                    </div>
                    <div class="modal-footer">
                        <div id="js_validateFormModalHotelContact_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                        <button type="button" class="btn btn-primary" aria-label="Xác nhận" onClick="downloadImageHotelInfo();">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- form modal tải thông tin khách sạn -->
    <form id="formDownloadHotelInfo" method="POST" action="{{ route('admin.hotel.downloadHotelInfo') }}">
        @csrf
            <div class="modal fade" id="formModalDownloadHotelInfo" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="min-width:600px;">
                        <div class="modal-header bg-transparent">
                            <h4>Tải tự động khách sạn</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label" for="url_crawler">Url khách sạn (Mytour)</label>
                            <textarea class="form-control" id="url_crawler"  name="url_crawler" rows="2">{{ $item['url_crawler'] ?? null }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <div id="js_validateFormModalHotelContact_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                            <button type="submit" class="btn btn-primary" aria-label="Xác nhận">Xác nhận</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <!-- form modal liên hệ -->
    <form id="formHotelContact" method="POST" action="#">
    @csrf
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
                    <div id="js_loadFormModal_footer" class="modal-footer">
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
                    <div id="js_loadFormHotelRoom_footer" class="modal-footer">
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
<div id="loadingBox" class="loadingBox">
    <div class="loadingBox_icon">
        <div class="spinner-grow text-secondary me-1" role="status"></div>
    </div>
    <div class="loadingBox_bg"></div>
</div>
@endsection
@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            loadHotelRoom();
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
            if(elemt.valid()){
                elemt.submit();
            }
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

        function setValueFormHotelRoom(){
            const idHotelInfo     = $('#hotel_info_id').val();
            if(idHotelInfo!=''){
                loadFormHotelRoom(0);
            }else {
                $('#js_loadFormHotelRoom_body').html('Vui lòng tạo và lưu Đối tác trước khi tạo Phòng!');
                $('#js_loadFormHotelRoom_footer').css('display', 'none');
            }
        }

        function loadFormHotelRoom(hotelRoom = 0){
            addLoading('js_loadFormHotelRoom_body', 100);
            const idHotel       = $('#hotel_info_id').val();
            const type          = '{{ $type }}';
            $.ajax({
                url         : '{{ route("admin.hotelRoom.loadFormHotelRoom") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    hotel_info_id   : idHotel,
                    hotel_room_id   : hotelRoom
                },
                success     : function(data){
                    $('#js_loadFormHotelRoom_header').html(data.header);
                    $('#js_loadFormHotelRoom_body').html(data.body);
                    $('#js_loadFormHotelRoom_footer').css('display', 'flex');
                }
            });
        }

        function loadHotelRoom(){
            addLoading('js_loadHotelRoom', 60);
            const idHotel       = $('#hotel_info_id').val();
            $.ajax({
                url         : '{{ route("admin.hotelRoom.loadHotelRoom") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    hotel_info_id   : idHotel
                },
                success     : function(data){
                    $('#js_loadHotelRoom').html(data);
                }
            });
        }

        function deleteRoom(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : '{{ route("admin.hotelRoom.deleteRoom") }}',
                    type        : 'post',
                    dataType    : 'html',
                    data        : {
                        '_token'    : '{{ csrf_token() }}',
                        id      : id
                    },
                    success     : function(data){
                        if(data==true){
                            /* thành công */
                            $('#hotelRoom_'+id).remove();
                            loadHotelRoom();
                            showMessage('js_showMessage', 'Xóa Phòng thành công!', 'success');
                        }else {
                            /* thất bại */
                            showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                        }
                    }
                });
            }
        }

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
            addLoading(idWrite, 60);
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

        function setValueFormHotelContact(){
            const idHotelInfo     = $('#hotel_info_id').val();
            if(idHotelInfo!=''){
                loadFormHotelContact(0);
            }else {
                $('#js_loadFormModal_body').html('Vui lòng tạo và lưu Đối tác trước khi tạo Liên hệ!');
                $('#js_loadFormModal_footer').css('display', 'none');
            }
        }

        function loadFormHotelContact(idHotelContact){
            // const idHotelInfo = $('#hotel_info_id').val();
            $.ajax({
                url         : '{{ route("admin.hotel.loadFormContact") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    // hotel_info_id       : idHotelInfo,
                    hotel_contact_id    : idHotelContact
                },
                success     : function(data){
                    $('#js_loadFormModal_header').html(data.header);
                    $('#js_loadFormModal_body').html(data.body);
                    $('#js_loadFormModal_footer').css('display', 'flex');
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
            /* bật loadding toàn screen */
            loaddingFullScreen();
            /* kiểm tra trường required bỏ trống */
            const tmp                   = validateFormModal('formHotelRoom');
            if(tmp==''){
                const idHotel           = $('#hotel_info_id').val();
                var dataForm            = {};
                /* lấy id phòng */
                dataForm['hotel_room_id'] = $('#hotel_room_id').val();
                /* lấy tên phòng */ 
                dataForm['name']        = $('#formHotelRoom #name').val();
                /* lấy kích thước */ 
                dataForm['size']        = $('#formHotelRoom #size').val();
                /* lấy số người tối đa */ 
                dataForm['number_people']    = $('#formHotelRoom #number_people').val();
                /* lấy giá */ 
                dataForm['price']       = $('#formHotelRoom #price').val();
                /* lấy điều khoản */ 
                dataForm['condition']   = $('#formHotelRoom #condition').val();
                /* lấy ảnh */
                var images              = {};
                var count               = 0;
                $('#formHotelRoom').find('input[name*=images]').each(function(){
                    if($(this).val().length>0) {
                        images[count]   = $(this).val();
                        ++count;
                    }
                });
                dataForm['images']      = images;
                /* lấy chi tiết tiện nghi */
                var detail              = [];
                $('#formHotelRoom').find('[name^="details"]').filter(function() {
                    return /\[\d+\]\[name\]$/.test(this.name); // Lọc các input có dạng room_detail[0][name]
                }).each(function() {
                    var key = this.name.match(/\[(\d+)\]/)[1]; // Lấy số từ giá trị name
                    var value = $(this).val(); // Lấy giá trị của input
                    var contentName = 'details[' + key + '][detail]'; // Tạo name tương ứng với content
                    var contentValue = $('[name="' + contentName + '"]').val(); // Lấy giá trị của input content
                    var item = {
                        'name': value,
                        'detail': contentValue
                    };
                    detail[key] = item; // Gán giá trị vào mảng dataForm với key tương ứng
                });
                dataForm['details']  = detail;
                /* lấy tiện nghi chungg */
                dataForm['facilities'] = $('#formHotelRoom #facilities option:selected').map(function() {
                    return $(this).val();
                }).get();
                /* truyền ajax xử lý */
                if(dataForm['hotel_room_id']==''){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.hotelRoom.createRoom") }}',
                        type        : 'post',
                        dataType    : 'json',
                        data        : {
                            '_token'        : '{{ csrf_token() }}',
                            hotel_info_id   : idHotel,
                            dataForm        : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadHotelRoom();
                                showMessage('js_showMessageHotelRoom', 'Thêm mới Phòng thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessageHotelRoom', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                            loaddingFullScreen();
                            $('#formModalHotelRoom').modal('hide');
                        }
                    });
                }else {
                    /* update */
                    $.ajax({
                        url         : '{{ route("admin.hotelRoom.updateRoom") }}',
                        type        : 'post',
                        dataType    : 'json',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            hotel_info_id   : idHotel,
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadHotelRoom();
                                showMessage('js_showMessageHotelRoom', 'Cập nhật thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessageHotelRoom', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                            loaddingFullScreen();
                            $('#formModalHotelRoom').modal('hide');
                        }
                    });
                }
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