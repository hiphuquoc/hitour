<head>
  <meta name="robots" content="noindex,nofollow">
  {{-- <meta name="csrf-token" content="LdjJ2w20tGUL3y60AsF4ToCMKWgnRVrAypJ4E78v"> --}}
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
  <meta name="description" content="Trang quản trị nội dung {{ config('company.webname') }}">
  <meta name="keywords" content="Trang quản trị nội dung {{ config('company.webname') }}">
  <meta name="author" content="{{ config('company.webname') }}">
  <title>Trang quản trị nội dung {{ config('company.webname') }}</title>
  
  <!-- BEGIN: Vendor CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/vendors.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/forms/select/select2.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/form-validation.css') }}">
  <!-- END: Vendor CSS-->
  <!-- BEGIN: Theme CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/bootstrap.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/bootstrap-extended.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/colors.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/components.css') }}">
  <!-- BEGIN: Page CSS-->
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/form-validation.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/extensions/ext-component-sweet-alerts.min.css') }}">
  {{-- <link rel="stylesheet" href="assets/vendor/libs/animate-css/animate.css" />
  <link rel="stylesheet" href="assets/vendor/libs/sweetalert2/sweetalert2.css" /> --}}
  <!-- END: Page CSS-->

  <!-- BEGIN: Custom CSS-->
  @vite(['resources/sources/admin/style.scss'])
  <!-- END: Custom CSS-->
  <style type="text/css">
    @font-face{
        font-family:'SVN-Gilroy';
        font-style:normal;
        font-weight:500;
        src:url("/fonts/svn-gilroy_medium.ttf")
    }
    @font-face{
        font-family:'SVN-Gilroy Bold';
        font-style:normal;
        font-weight:700;
        src:url("/fonts/svn-gilroy_semibold.ttf")
    }
</style>

  <!-- BEGIN: FONT AWESOME -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
  <!-- END: FONT AWESOME -->
  <!-- BEGIN: Custom CSS-->

  <!-- BEGIN: SLICK -->
  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
  <!-- END: SLICK -->

  <!-- Google tag (gtag.js) GOOGLE ADS -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16473804050">
  </script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'AW-16473804050');
  </script>

 </head>