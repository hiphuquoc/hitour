<!DOCTYPE html>
<html lang="vi">

<!-- === START:: Head === -->
<head>
    @include('main.snippets.head')
</head>
<!-- === END:: Head === -->

<!-- === START:: Body === -->
<body>
    <!-- === START:: Header === -->
    @include('main.snippets.headerTop')
    @include('main.snippets.headerMain')
    <!-- === END:: Header === -->

    <!-- === START:: Breadcrumb === -->
    {{-- @if(Route::current()->uri!=='/')
        @include('snippets.breadcrumb')
    @endif --}}
    <!-- === END:: Breadcrumb === -->

    <!-- === START:: Content === -->
    <div class="app-content content">
        <div class="content-overlay"></div>
        @yield('content')
    </div>

    <!-- === START:: Footer === -->
    @include('main.snippets.footer')
    <!-- === END:: Footer === -->
    
    <!-- === START:: Scripts Default === -->
    @include('main.snippets.scripts-default')
    <!-- === END:: Scripts Default === -->

    <!-- === START:: Scripts Custom === -->
    @stack('scripts-custom')
    <!-- === END:: Scripts Custom === -->
</body>
<!-- === END:: Body === -->

</html>