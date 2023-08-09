@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Chỉnh sửa Comment: '.$item->name;
        $submit         = 'admin.hotelComment.update';
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
        
            <input type="hidden" name="hotel_info_id" value="{{ $item->id }}" />
            <div class="pageAdminWithRightSidebar_main repeater">
                <div class="pageAdminWithRightSidebar_main_content" data-repeater-list="comments">

                    @include('admin.form.formComment', [
                        'comments' => old('comments') ?? $item['comments'] ?? null
                    ])

                    <div class="pageAdminWithRightSidebar_main_content_item width100">
                        <div class="card">
                            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                <span>Thêm comment</span>
                            </button>
                        </div>
                    </div>

                </div>
                <!-- END:: Main content -->

                <!-- START:: Sidebar content -->
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <a href="{{ route('admin.hotel.view', ['id' => $item->id]) }}" type="button" class="btn btn-secondary waves-effect waves-float waves-light">Quay lại</a>
                        <button type="button" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;" aria-label="Lưu">Lưu</button>
                    </div>
                </div>
                <!-- END:: Sidebar content -->
            </div>
        </div>

    </form>
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

        $('.repeater').repeater();

        function submitForm(idForm){
            const elemt = $('#'+idForm);
            if(elemt.valid()){
                elemt.submit();
            }
        }
              
    </script>
@endpush