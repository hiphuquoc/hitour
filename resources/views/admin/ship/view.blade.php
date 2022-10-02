@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Chuyến tàu mới';
        $submit         = 'admin.ship.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Chuyến tàu';
            $submit     = 'admin.ship.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        <!-- input hidden -->
        <input type="hidden" id="ship_info_id" name="ship_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : null }}" />
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

                                @include('admin.ship.formPage')

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
                                    Lịch khởi hành và giá chi tiết
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormModal();"></i>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div id="js_showMessage">
                                    <!-- javascript:showMessage -->
                                </div>
                                <div id="js_loadTimeAndPrice">
                                    <!-- javascript:loadTimeAndPrice -->
                                    Không có dữ liệu phù hợp!
                                </div>
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
                        <button type="button" class="btn btn-secondary waves-effect waves-float waves-light"  onClick="history.back();">Quay lại</button>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;">Lưu</button>
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
    <form id="formModal" method="POST">
    @csrf
        <!-- Input Hidden -->
        <input type="hidden" id="tour_info_id" name="tour_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" />
        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormModal_header">Thêm giờ tàu và giá</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormModal_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <div id="js_validateFormModal_message" class="error" style="display:none;"><!-- Load Ajax --></div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateShipPriceAndTime();">Xác nhận</button>
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
            loadTimeAndPrice('js_loadTimeAndPrice');
            closeMessage();
            
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

        function addAndUpdateShipPriceAndTime(){
            const tmp                   = validateFormModal();
            /* không có trường required bỏ trống */
            if(tmp==''){
                /* data from_to */
                var string_from_to      = [];
                $('#formModal').find('[name*=string_from_to]').each(function(){
                    string_from_to.push($(this).val());
                });
                /* data time_departure */
                var time_departure      = [];
                $('#formModal').find('[name*=time_departure]').each(function(){
                    time_departure.push($(this).val());
                });
                /* data time_arrive */
                var time_arrive         = [];
                $('#formModal').find('[name*=time_arrive]').each(function(){
                    time_arrive.push($(this).val());
                });
                /* dataForm */
                var dataForm            = {
                    ship_price_id   : $('#ship_price_id').val(),
                    ship_info_id    : $('#ship_info_id').val(),
                    ship_partner_id : $('#ship_partner_id').val(),
                    time_departure  : $('#time_departure').val(),
                    time_arrive     : $('#time_arrive').val(),
                    date_range      : $('#date_range').val(),
                    price_adult     : $('#price_adult').val(),
                    price_child     : $('#price_child').val(),
                    price_old       : $('#price_old').val(),
                    price_vip       : $('#price_vip').val(),
                    profit_percent  : $('#profit_percent').val(),
                    string_from_to,
                    time_departure,
                    time_arrive
                };            
                if(typeof dataForm['ship_price_id']=='undefined' || dataForm['ship_price_id']==''){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.shipPrice.createPrice") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadTimeAndPrice('js_loadTimeAndPrice');
                                showMessage('js_showMessage', 'Thêm mới Option & Giá thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }else {
                    /* update */
                    $.ajax({
                        url         : '{{ route("admin.shipPrice.updatePrice") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadTimeAndPrice('js_loadTimeAndPrice');
                                showMessage('js_showMessage', 'Cập nhật Option & Giá thành công!', 'success');
                            }else {
                                /* thất bại */
                                showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                            }
                        }
                    });
                }
                $('#modalContact').modal('hide');
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

        function loadTimeAndPrice(idWrite){
            $.ajax({
                url         : '{{ route("admin.shipPrice.loadList") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    ship_info_id    : '{{ !empty($item->id)&&$type!="copy" ? $item->id : 0 }}'
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }

        function loadFormModal(shipPriceId = 0, typeAction = 'create'){
            const shipId    = $('#ship_info_id').val();
            $.ajax({
                url         : '{{ route("admin.shipPrice.loadFormModal") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    type            : typeAction,
                    ship_info_id    : shipId,
                    ship_price_id   : shipPriceId
                },
                success     : function(data){
                    $('#js_loadFormModal_header').html(data.header);
                    $('#js_loadFormModal_body').html(data.body);
                    console.log(data);
                }
            });
        }

        function deletePriceAndTime(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : '{{ route("admin.shipPrice.deletePrice") }}',
                    type        : 'post',
                    dataType    : 'html',
                    data        : {
                        '_token'    : '{{ csrf_token() }}',
                        ship_price_id   : id
                    },
                    success     : function(data){
                        console.log(data);
                        if(data==true){
                            /* thành công */
                            $('#priceAndTime_'+id).remove();
                            loadTimeAndPrice('js_loadTimeAndPrice');
                            showMessage('js_showMessage', 'Xóa Giá và Thời gian thành công!', 'success');
                        }else {
                            /* thất bại */
                            showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                        }
                    }
                });
            }
        }

        function validateFormModal(){
            let error       = [];
            /* input required không được bỏ trống */
            $('#formModal').find('input[required]').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            /* select không được bỏ trống */
            $('#formModal').find('select').each(function(){
                if($(this).val()==''||$(this).val()=='0'){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }
        
    </script>
@endpush