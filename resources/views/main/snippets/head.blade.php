
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="index,follow">
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="fragment" content="!" />

@stack('head-custom')

<!-- BEGIN: Custom CSS-->
@vite(['resources/sources/main/style.scss'])
<!-- END: Custom CSS-->

<link rel="icon" href="{{ Storage::url('images/svg/favicon-hitour.ico') }}" type="image/x-icon">

<!-- BEGIN: FONT AWESOME -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<!-- END: FONT AWESOME -->

<!-- BEGIN: SLICK -->
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<!-- END: SLICK -->

<style type="text/css">
    /* @font-face {
        font-family: 'Segoe-UI Light';
        font-style: normal;
        font-weight: 400;
        src: url('/fonts/SegoeUI-Light.ttf');
        font-display: swap;
    } */

    @font-face {
        font-family: 'Segoe-UI';
        font-style: normal;
        font-weight: 500;
        src: url('/fonts/SegoeUI.ttf');
        font-display: swap;
    }

    @font-face {
        font-family: 'Segoe-UI Semi';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/fonts/SegoeUI-SemiBold.ttf');
    }

    @font-face {
        font-family: 'Segoe-UI Bold';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/fonts/SegoeUI-Bold.ttf');
    }

    /* @font-face {
        font-family: 'SVN-Gilroy Thin';
        font-style: normal;
        font-weight: 400;
        src: url(//bizweb.dktcdn.net/100/438/408/themes/919724/assets/svn-gilroy_regular.ttf?1698220622470);
        font-display: 'Swap';
    }

    @font-face {
        font-family: 'SVN-Gilroy Light';
        font-style: normal;
        font-weight: 400;
        font-display: swap;
        src: url(//bizweb.dktcdn.net/100/438/408/themes/919724/assets/svn-gilroy_regular.ttf?1698220622470);
    } */

    @font-face {
        font-family: 'SVN-Gilroy';
        font-style: normal;
        font-display: swap;
        font-weight: 500;
        src: url('/fonts/svn-gilroy_medium.ttf');
    }

    @font-face {
        font-family: 'SVN-Gilroy Med';
        font-style: normal;
        font-display: swap;
        font-weight: 700;
        src: url('/fonts/svn-gilroy_med.ttf');
    }

    @font-face {
        font-family: 'SVN-Gilroy Semi';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/fonts/svn-gilroy_semibold.ttf');
    }

    @font-face {
        font-family: 'SVN-Gilroy Bold';
        font-style: normal;
        font-weight: 700;
        font-display: swap;
        src: url('/fonts/svn-gilroy_semibold.ttf');
    }
</style>

<!-- BEGIN: Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- END: Jquery -->