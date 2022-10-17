@extends('admin.layouts.main')
@section('content')
    @php
        $titlePage      = 'Thêm Cảng tàu mới';
        $submit         = 'admin.shipPort.create';
        $checkImage     = 'required';
        if(!empty($type)&&$type=='edit'){
            $titlePage  = 'Chỉnh sửa Cảng tàu';
            $submit     = 'admin.shipPort.update';
            $checkImage = null;
        }
    @endphp

    <form id="formAction" class="needs-validation invalid" action="{{ route($submit) }}" method="POST" novalidate="" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="ship_port_id" value="{{ $item->id ?? null }}" />
        <div class="pageAdminWithRightSidebar withRightSidebar">
            <div class="pageAdminWithRightSidebar_header">
                {{ $titlePage }}
            </div>
            <!-- START:: Error -->
            @if ($errors->any())
                <ul class="errorList">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            <!-- END:: Error -->
            
            <!-- MESSAGE -->
            @include('admin.template.messageAction')

            <div class="pageAdminWithRightSidebar_main">
                <div class="pageAdminWithRightSidebar_main_content">
                    <div class="pageAdminWithRightSidebar_main_content_item">
                        <!-- Thông tin -->
                        <div class="card">
                            @include('admin.shipPort.formInfo')
                        </div>
                    </div>
                    <!-- END:: Main content -->
                </div>
                <div class="pageAdminWithRightSidebar_main_rightSidebar">
                    <!-- Button Save -->
                    <div class="pageAdminWithRightSidebar_main_rightSidebar_item buttonAction" style="padding-bottom:1rem;">
                        <button type="button" class="btn btn-secondary waves-effect waves-float waves-light"  onClick="history.back();">Quay lại</button>
                        <button type="submit" class="btn btn-success waves-effect waves-float waves-light" onClick="javascript:submitForm('formAction');" style="width:100px;">Lưu</button>
                    </div>
                    <div class="customScrollBar-y" style="height: calc(100% - 70px);border-top: 1px dashed #adb5bd;">
                        {{-- <!-- Form Upload -->
                        <div class="pageAdminWithRightSidebar_main_rightSidebar_item">
                            @include('admin.shipPort.formLogo')
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts-custom')
    <script type="text/javascript">

        $(document).ready(function(){
            
        })
        
    </script>
@endpush