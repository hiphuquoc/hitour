<html>
    <!-- BEGIN: Head-->
    <head>
        @include('admin.auth.head')
    </head>
    <!-- END: Head-->

    <!-- BEGIN: Body-->
    <body class="pace-done vertical-layout vertical-menu-modern blank-page navbar-floating footer-static" data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
        <div class="pace pace-inactive">
            <div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
                <div class="pace-progress-inner"></div>
            </div>
            <div class="pace-activity"></div>
        </div>
        <!-- BEGIN: Content-->
        @yield('content')
        <!-- END: Content-->
        @include('admin.auth.scripts-default')
        
        @stack('scripts-custom')
    </body>
    <!-- END: Body-->
</html>