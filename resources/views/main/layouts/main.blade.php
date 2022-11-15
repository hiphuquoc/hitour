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
    <!-- Cache header main -->
    @php
        /* cache HTML */
        $nameCache              = 'header_main.'.config('main.cache.extension');
        $pathCache              = Storage::path(config('main.cache.folderSave')).$nameCache;
        $cacheTime    	        = 86400;
        if(file_exists($pathCache)&&$cacheTime>(time() - filectime($pathCache))){
            $xhtmlHeader = file_get_contents($pathCache);
            echo $xhtmlHeader;
        }else {
            $xhtmlHeader = view('main.snippets.headerMain')->render();
            echo $xhtmlHeader;
            Storage::put(config('main.cache.folderSave').$nameCache, $xhtmlHeader);
        }
    @endphp
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

    <div class="bottom">
        <div id="gotoTop" class="gotoTop" onclick="javascript:gotoTop();" style="display: block;">
            <i class="fas fa-chevron-up"></i>
        </div>
        @stack('bottom')
    </div>
    
    <!-- === START:: Scripts Default === -->
    @include('main.snippets.scripts-default')
    <!-- === END:: Scripts Default === -->

    <!-- === START:: Scripts Custom === -->
    @stack('scripts-custom')
    <!-- === END:: Scripts Custom === -->
</body>
<!-- === END:: Body === -->

</html>