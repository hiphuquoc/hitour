@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Tour mới';
        $submit         = 'admin.tour.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Tour';
            $submit     = 'admin.tour.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        <!-- input hidden -->
        <input type="hidden" id="tour_info_id" name="tour_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : null }}" />
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
                                <h4 class="card-title">Thông tin trang</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.tour.formPage')

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
                                <h4 class="card-title">Mô tả Tour</h4>
                            </div>
                            <div class="card-body">

                                @include('admin.tour.formDescription')

                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">
                                    Tùy chọn và Giá
                                    <i class="fa-regular fa-circle-plus" data-bs-toggle="modal" data-bs-target="#modalContact" onclick="loadFormOption();"></i>
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
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h4 class="card-title">Thông tin Tour</h4>
                            </div>
                            <div class="card-body">
                                
                                @include('admin.tour.formInfo', compact('item'))
                                
                            </div>
                        </div>
                    </div>
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <div data-repeater-list="timetable">
                            @if($item->timetables->isNotEmpty())
                                @foreach($item->timetables as $timetable)
                                    <div class="card" data-repeater-item="">
                                        <div class="card-header border-bottom">
                                            <h4 class="card-title">
                                                Lịch trình Tour
                                                <i class="fa-solid fa-circle-xmark" data-repeater-delete=""></i>
                                            </h4>
                                        </div>
                                        <div class="card-body">
                
                                            @include('admin.tour.formTimetable', ['item' => $timetable])
                                            
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="card" data-repeater-item="">
                                    <div class="card-header border-bottom">
                                        <h4 class="card-title">
                                            Lịch trình Tour
                                            <i class="fa-solid fa-circle-xmark" data-repeater-delete=""></i>
                                        </h4>
                                    </div>
                                    <div class="card-body">
            
                                        @include('admin.tour.formTimetable')
                                        
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card">
                            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" data-repeater-create>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Thêm</span>
                            </button>
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
    <form id="formTourOption" method="POST" action="{{ route('admin.tourOption.createOption') }}">
    @csrf
        <!-- Input Hidden -->
        <input type="hidden" id="tour_info_id" name="tour_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : 0 }}" />
        <div class="modal fade" id="modalContact" tabindex="-1" aria-labelledby="addNewCardTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <h4 id="js_loadFormOption_header">Thêm Option & Giá</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="js_loadFormOption_body" class="modal-body">
                        <!-- load Ajax -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="button" class="btn btn-primary" onClick="addAndUpdateTourOption();">Xác nhận</button>
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
            loadOptionPrice('js_loadOptionPrice');
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

        function addAndUpdateTourOption(){
            /* data apply_age */
            let apply_age           = [];
            $('#formTourOption').find('input[name*=apply_age]').each(function(){
                apply_age.push($(this).val());
            });
            /* data price */
            let price               = [];
            $('#formTourOption').find('input[name*=price]').each(function(){
                price.push($(this).val());
            });
            /* data profit */
            let profit              = [];
            $('#formTourOption').find('input[name*=profit]').each(function(){
                profit.push($(this).val());
            });
            /* gộp dataForm đầy đủ */
            let dataForm            = {
                tour_info_id    : $('#tour_info_id').val(),
                tour_option_id  : $('#tour_option_id').val(),
                option          : $('#tour_option').val(),
                apply_day       : $('#tour_apply_day').val(),
                apply_age,
                price,
                profit
            };
            const tmp               = validateFormModal();
            if(tmp==''){
                /* không có trường required bỏ trống */
                if(dataForm['tour_option_id']==null||dataForm['tour_option_id']==''){
                    /* insert */
                    $.ajax({
                        url         : '{{ route("admin.tourOption.createOption") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadOptionPrice('js_loadOptionPrice');
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
                        url         : '{{ route("admin.tourOption.updateOption") }}',
                        type        : 'post',
                        dataType    : 'html',
                        data        : {
                            '_token'    : '{{ csrf_token() }}',
                            dataForm    : dataForm
                        },
                        success     : function(data){
                            if(data==true){
                                /* thành công */
                                loadOptionPrice('js_loadOptionPrice');
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

        function loadOptionPrice(idWrite){
            $.ajax({
                url         : '{{ route("admin.tourOption.loadOptionPrice") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    tour_info_id    : '{{ !empty($item->id)&&$type!="copy" ? $item->id : 0 }}'
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }

        function loadFormOption(tourOption = 0){
            const tourId    = $('#tour_info_id').val();
            const type      = '{{ $type }}';
            $.ajax({
                url         : '{{ route("admin.tourOption.loadFormOption") }}',
                type        : 'post',
                dataType    : 'json',
                data        : {
                    '_token'        : '{{ csrf_token() }}',
                    tour_info_id    : tourId,
                    tour_option_id  : tourOption
                },
                success     : function(data){
                    $('#js_loadFormOption_header').html(data.header);
                    $('#js_loadFormOption_body').html(data.body);
                }
            });
        }

        function deleteOptionPrice(id){
            $.ajax({
                url         : '{{ route("admin.tourOption.deleteOption") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    id      : id
                },
                success     : function(data){
                    if(data==true){
                        /* thành công */
                        $('#optionPrice_'+id).remove();
                        loadOptionPrice('js_loadOptionPrice');
                        showMessage('js_showMessage', 'Xóa Option & Giá thành công!', 'success');
                    }else {
                        /* thất bại */
                        showMessage('js_showMessage', 'Có lỗi xảy ra, vui lòng thử lại!', 'danger');
                    }
                }
            });
        }

        function validateFormModal(){
            let error       = [];
            $('#formTourOption').find('input[required').each(function(){
                if($(this).val()==''){
                    error.push($(this).attr('name'));
                }
            })
            return error;
        }
        
    </script>
@endpush