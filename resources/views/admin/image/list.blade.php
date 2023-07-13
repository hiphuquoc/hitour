@extends('admin.layouts.main')
@section('content')

<div class="titlePage">Danh sách ảnh</div>
<!-- ===== START: SEARCH FORM ===== -->

<div class="searchBox">
    <div class="searchBox_item">
        <form id="formSearch" method="get" action="{{ route('admin.image.list') }}">
            <div class="input-group">
                <input type="text" class="form-control" name="search_name" placeholder="Tìm theo tên" value="{{ $params['search_name'] ?? null }}">
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tìm">Tìm</button>
            </div>
        </form>
    </div>
    <div class="searchBox_item">
        <form id="formUpload" method="get" enctype="multipart/form-data" multiple>
        @csrf
            <div class="input-group">
                <input class="form-control" type="file" name="image_upload[]" multiple>
                <button class="btn btn-primary waves-effect" id="button-addon2" type="submit" aria-label="Tải lên">Tải lên</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== END: SEARCH FORM ===== -->
<div id="js_uploadImage_idWrite" class="imageBox" style="padding-bottom:2rem;">
    @if(!empty($list))
    @foreach($list as $item)
        @include('admin.image.oneRow', compact('item'))
    @endforeach
    @endif
</div>

<!-- ===== START: MODAL ===== -->
<form id="formModal" method="post" enctype="multipart/form-data">
@csrf
    <!-- Input Hidden -->
    <div id="modalImage" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <h4 id="js_loadModal_head">Sửa tên ảnh</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="js_loadModal_message" class="error" style="margin-bottom:1rem;display:none;">Các trường bắt buộc không được để trống!</div>
                    <div id="js_loadModal_body">
                        <!-- load Ajax -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Đóng">Đóng</button>
                    <button id="js_loadModal_action" type="button" class="btn btn-primary" tableindex="0" aria-label="Xác nhận">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- ===== END:: MODAL ===== -->
    
@endsection
@push('scripts-custom')
    <script type="text/javascript">

        function deleteItem(id){
            if(confirm('{{ config("admin.alert.confirmRemove") }}')) {
                $.ajax({
                    url         : "{{ route('admin.tour.delete') }}",
                    type        : "GET",
                    dataType    : "html",
                    data        : { id : id }
                }).done(function(data){
                    if(data==true) $('#tour_'+id).remove();
                });
            }
        }

        function submitForm(idForm){
            $('#'+idForm).submit();
        }

        function loadModal(type, basename){
            $.ajax({
                url         : "{{ route('admin.image.loadModal') }}",
                type        : "post",
                dataType    : "json",
                data        : { 
                    '_token'    : '{{ csrf_token() }}', 
                    type,
                    basename 
                }
            }).done(function(data){
                $('#js_loadModal_head').html(data.head);
                $('#js_loadModal_body').html(data.body);
                const buttonAction  = $('#js_loadModal_action');
                if(type==='changeName'){
                    buttonAction.attr({
                        'onClick'   : 'changeName();',
                        'type'      : 'button'
                    });
                }else if(type==='changeImage'){
                    $('#js_loadModal_action').attr('type', 'submit').removeAttr('onClick');
                }
            });
        }

        function changeName(){
            const basenameOld   = $('#basename_old').val();
            const nameNew       = $('#name_new').val();
            const idImageBox    = basenameOld.slice(0, basenameOld.lastIndexOf('.'));
            $.ajax({
                url         : "{{ route('admin.image.changeName') }}",
                type        : "post",
                dataType    : "json",
                data        : { 
                    '_token'        : '{{ csrf_token() }}', 
                    basename_old    : basenameOld,
                    name_new        : nameNew 
                }
            }).done(function(data){
                if(data.flag==true){
                    /* cập nhật lại id của imageBox */
                    $('#'+idImageBox).attr('id', nameNew);
                    /* tải lại imageBox */
                    loadImageBox(nameNew);
                    /* tắt modal */
                    $('#modalImage').modal('hide');
                }else {
                    $('#js_loadModal_message').html(data.message).css('display', 'block');
                    setTimeout(() => {
                        $('#js_loadModal_message').css('display', 'none');
                    }, 3000);
                }
            });
        }

        /* ChangeImage submit files */
        $("#formModal").on('submit', function(e) {
            e.preventDefault();
            const filenameImage     = $('#filename_image').val();
            $.ajax({
                url             : "{{ route('admin.image.changeImage') }}",
                type            : "POST",
                dataType        : 'json',
                data            : new FormData(this),
                contentType     : false,
                cache           : false,
                processData     : false,
                success         : function(data){
                    setTimeout(() => {
                        if(data.flag==true){
                            /* tải lại imageBox */
                            loadImageBox(filenameImage);
                            /* tắt modal */
                            $('#modalImage').modal('hide');
                            return true;
                        }else {
                            $('#js_loadModal_message').html(data.message).css('display', 'block');
                            setTimeout(() => {
                                $('#js_loadModal_message').css('display', 'none');
                            }, 3000);
                            return false;
                        }
                    });
                }
            });
        });

        function loadImageBox(idBox){
            const heightBox = $('#'+idBox).outerHeight();
            addLoading(idBox, heightBox);
            $.ajax({
                url         : "{{ route('admin.image.loadImage') }}",
                type        : "post",
                dataType    : "html",
                data        : { 
                    '_token'        : '{{ csrf_token() }}', 
                    image_name    : idBox
                }
            }).done(function(data){
                setTimeout(() => {
                    $('#'+idBox).replaceWith(data);
                }, 500);
            });
        }

        /* Upload and append thêm ảnh */
        $("#formUpload").on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url             : "{{ route('admin.image.uploadImages') }}",
                type            : "POST",
                dataType        : 'json',
                data            : new FormData(this),
                contentType     : false,
                cache           : false,
                processData     : false,
                success         : function(data){
                    const elementWrite  = $('#js_uploadImage_idWrite');
                    let contentOld      = elementWrite.html();
                    elementWrite.html(data.content+contentOld);
                    document.getElementById("formUpload").reset();
                }
            });
        });

        function removeImage(idBox, basenameImage){
            const heightBox = $('#'+idBox).outerHeight();
            addLoading(idBox, heightBox);
            $.ajax({
                url         : "{{ route('admin.image.removeImage') }}",
                type        : "post",
                dataType    : "html",
                data        : { 
                    '_token'        : '{{ csrf_token() }}', 
                    basename_image  : basenameImage
                }
            }).done(function(data){
                setTimeout(() => {
                    if(data==true) $('#'+idBox).hide();
                }, 500)
            });
        }

    </script>
@endpush