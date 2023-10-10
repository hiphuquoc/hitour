<!-- BEGIN: SLICK -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- END: SLICK -->

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-G9LSM1829M"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-G9LSM1829M');
</script>

<script type="text/javascript">
    $(window).ready(function(){
        loadImage()
        loadImageFromGoogleCloud()
        addTableResponsive()
        checkLoginAndSetShow()
        /* fixed sidebar khi scroll */
        const elemt                         = $('.js_scrollFixed');
        const widthElemt                    = elemt.parent().width();
        const widthResponsive               = $(window).width();
        if(elemt.length>0&&widthResponsive>991){
            const positionTopElemt          = elemt.offset().top;
            $(window).scroll(function(){
                const flagScroll            = $('#js_scrollFixed_flag').val();
                if(flagScroll!='false'){
                    const heightFooter      = 700;
                    const positionScrollbar = $(window).scrollTop();
                    const scrollHeight      = $('body').prop('scrollHeight');
                    const heightLimit       = parseInt(scrollHeight - heightFooter - elemt.outerHeight());
                    if(positionScrollbar>positionTopElemt&&positionScrollbar<heightLimit){
                        elemt.addClass('scrollFixedSidebar').css({
                            'width'         : widthElemt,
                            'margin-top'    : '1.5rem'
                        });
                    }else {
                        elemt.removeClass('scrollFixedSidebar').css({
                            'width'         : 'unset',
                            'margin-top'    : 0
                        });
                    }
                }
            });
        }
    });
    /* check đăng nhập */
    function checkLoginAndSetShow(){
        const language = $('#language').val();
        $.ajax({
            url         : '{{ route("ajax.checkLoginAndSetShow") }}',
            type        : 'get',
            dataType    : 'json',
            data        : {
                '_token'            : '{{ csrf_token() }}',
                language
            },
            success     : function(response){
                /* button desktop */
                $('#js_checkLoginAndSetShow_button').html(response.button);
                $('#js_checkLoginAndSetShow_button').css('display', 'flex');
                /* button mobile */
                $('#js_checkLoginAndSetShow_buttonMobile').html(response.button_mobile);
                /* modal chung */
                $('#js_checkLoginAndSetShow_modal').html(response.modal);
            }
        });
    }
    /* Go to top */
    mybutton 					    = document.getElementById("gotoTop");
    window.onscroll                 = function() {scrollFunction()};
    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display 	= "block";
        } else {
            mybutton.style.display 	= "none";
        }
    }
    function gotoTop() {
        document.documentElement.scrollTop          = 0;
    }
    /* load image */
    function loadImage(){
        $(document).find('img[data-src]').each(function(){
            $(this).attr('src', $(this).attr('data-src'));
        });
    }
    /* load image from goole cloud */
    function loadImageFromGoogleCloud(){
        $(document).find('img[data-google-cloud]').each(function(){
            var elementImg          = $(this);
            const urlGoogleCloud    = elementImg.attr('data-google-cloud');
            const size              = elementImg.attr('data-size');
            $.ajax({
                url         : '{{ route("ajax.loadImageFromGoogleCloud") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    url_google_cloud    : urlGoogleCloud,
                    size
                },
                success     : function(response){
                    elementImg.attr('src', response);
                }
            });
        });
    }
    /* toc content */
    function buildTocContentSidebar(idElement){
        var dataTocContent      = {};
        var i                   = 0;
        var indexToc            = 0;
        $('#'+idElement).find('h2').each(function(){
            let dataId        = $(this).attr('id');
            if(typeof dataId=='undefined'){
                dataId          = 'randomIdTocContent_'+i;
                $(this).attr('id', dataId);
                ++indexToc;
            }
            const name          = $(this)[0].localName;
            const dataTitle     = $(this).html();
            dataTocContent[i]   = {
                id      : dataId,
                name    : name,
                title   : dataTitle
            };
            ++i;
        });
        $.ajax({
            url         : '{{ route("main.buildTocContentSidebar") }}',
            type        : 'get',
            dataType    : 'html',
            data        : {
                dataSend    : dataTocContent
            },
            success     : function(data){
                /* tính toán chiều cao sidebar */
                const heightW       = $(window).height();
                const heightUsed    = $('#js_buildTocContentSidebar_idWrite').parent().outerHeight();
                const height        = parseInt(heightW - heightUsed);
                $('#js_buildTocContentSidebar_idWrite').css('max-height', 'calc('+height+'px - 3rem)').html(data);
                // $('#js_buildTocContentSidebar_idWrite')
            }
        });
    }

    function buildTocContentMain(idElement){
        var dataTocContent      = {};
        var i                   = 0;
        var indexToc            = 0;
        $('#'+idElement).find('h2').each(function(){
            let dataId        = $(this).attr('id');
            if(typeof dataId=='undefined'){
                dataId          = 'randomIdTocContent_'+i;
                $(this).attr('id', dataId);
                ++indexToc;
            }
            const name          = $(this)[0].localName;
            const dataTitle     = $(this).html();
            dataTocContent[i]   = {
                id      : dataId,
                name    : name,
                title   : dataTitle
            };
            ++i;
        });
        $.ajax({
            url         : '{{ route("main.buildTocContentMain") }}',
            type        : 'get', 
            dataType    : 'html',
            data        : {
                dataSend    : dataTocContent
            },
            success     : function(data){
                if(data!=''){
                    $('#tocContentMain').html(data);
                    fixedTocContentIcon();
                    setHeightTocFixed();

                    $(window).resize(function() {
                        fixedTocContentIcon();
                        setHeightTocFixed();
                    });

                    $('.tocFixedIcon, .tocContentMain.tocFixed .tocContentMain_close').click(function(){
                        let elementMenu = $('.tocContentMain.tocFixed');
                        let displayMenu = elementMenu.css('display');
                        if(displayMenu=='none'){
                            elementMenu.css('display', 'block');
                        }else {
                            elementMenu.css('display', 'none');
                        }
                        // fixedTocContentIcon();
                    });

                    $('.tocContentMain_title, .tocContentMain_close').click(function(){
                        let elemtMenu   = $('.tocContentMain .tocContentMain_list');
                        let displayMenu = elemtMenu.css('display');
                        if(displayMenu=='none'){
                            elemtMenu.css('display', 'block');
                            $('.tocContentMain_close').removeClass('hidden');
                        }else {
                            elemtMenu.css('display', 'none');
                            $('.tocContentMain_close').addClass('hidden');
                        }
                    });

                    function fixedTocContentIcon(){
                        let widthS      = $(window).width();
                        let widthC      = $('.container').outerWidth();
                        let leftE       = parseInt((widthS - widthC - 70) / 2);
                        if($(window).width() < 1200){
                            leftE       = parseInt((widthS - widthC + 20) / 2);
                        }
                        $('.tocFixedIcon').css('left', leftE);
                    }

                    function setHeightTocFixed(){
                        let heightToc   = parseInt($(window).height() - 210);
                        $('.tocContentMain.tocFixed .tocContentMain_list').css('height', heightToc+'px');
                    }

                    let element         = $('#tocContentMain');
                    let positionE       = element.offset().top;
                    let heightE         = element.outerHeight();
                    let boxContent      = $('#'+idElement);
                    let positionB       = boxContent.offset().top;
                    let heightB         = boxContent.outerHeight();
                    let heightFooter    = $('.footer').outerHeight();
                    $(document).scroll(function(){
                        let scrollNow   = $(document).scrollTop();
                        let minScroll   = parseInt(heightE + positionE);
                        let maxScroll   = parseInt(heightB + positionB - heightFooter);
                        if(scrollNow > minScroll && scrollNow < maxScroll){ 
                            $('.tocFixedIcon').css('display', 'block');
                        }else {
                            $('.tocFixedIcon').css('display', 'none');
                        }
                    });
                }else {
                    $('#tocContentMain').remove();
                }
                
            }
        });
    }

    function addTableResponsive(){
        $(document).find('table:not(.noResponsive)').each(function(){
            $(this).wrap('<div class="customScrollBar-x"></div>');
        })

        // .wrap('<div class="tableResponsive"></div>')
    }

    /* ===== START:: MENU */
    $(window).on('load', function () {
        /* fixed headerMobile khi scroll */
        const elemt                 = $('.header');
        const positionTopElemt      = elemt.offset().top;
        $(window).scroll(function(){
            const positionScrollbar = $(window).scrollTop();
            // const scrollHeight      = $('body').prop('scrollHeight');
            // const heightLimit       = parseInt(scrollHeight - heightFooter - elemt.outerHeight());
            if(positionScrollbar>parseInt(positionTopElemt+50)){
                elemt.css({
                    'top'       : '0',
                    'position'  : 'fixed',
                    'left'      : 0
                });
            }else {
                elemt.css({
                    'top'       : '0',
                    'position'  : 'relative',
                    'left'      : 0
                });
            }
        });
    });
    function showHideListMenuMobile(thisD){
        let elemtC      = $(thisD).parent().find('> ul');
        let displayC    = elemtC.css('display');
        if(displayC=='none'){
            elemtC.css('display', 'block');
            $(thisD).find('.nav-mobile_main__arrow').html('<i class="fas fa-chevron-down"></i>');
        }else {
            elemtC.css('display', 'none');
            $(thisD).find('.nav-mobile_main__arrow').html('<i class="fas fa-chevron-right"></i>');
        }
    }
    function openCloseElemt(idElemt){
        let displayE    = $('#' + idElemt).css('display');
        if(displayE=='none'){
            $('#' + idElemt).css('display', 'block');
            $('body').css('overflow', 'hidden');
        }else {
            $('#' + idElemt).css('display', 'none');
            $('body').css('overflow', 'unset');
        }
    }
    function openMegaMenu(id){
        var elemt	= $('#'+id);
        elemt.siblings().removeClass('selected');
        elemt.addClass('selected');
        $('[data-menu]').each(function(){
            var key	= $(this).attr('data-menu');
            if(key==id){
            $(this).css('display', 'flex');
            }else {
                $(this).css('display', 'none');
            }
        });
    }
    function openMegaMenuTourContinent(id){
        var elemt	= $('#'+id);
        elemt.siblings().removeClass('selected');
        elemt.addClass('selected');
        $('[data-menu-tourcontinent]').each(function(){
            var key	= $(this).attr('data-menu-tourcontinent');
            if(key==id){
            $(this).css('display', 'flex');
            }else {
                $(this).css('display', 'none');
            }
        });
    }
    /* ===== END:: MENU */

    /* ===== In Tour */
    function printContent(el) {
        var restorepage = document.body.innerHTML;
        var printableArea = document.getElementById(el);
        var notToPrints = document.getElementsByClassName("notPrint");
        // Thêm image header vào trang
        var header      = '<img src="https://hitour.vn/storage/images/upload/blue-modern-tour-and-travel-twitter-header-type-manager-upload.webp" style="width:100%;margin-bottom:20px;" />';
        document.body.innerHTML = header + printableArea.innerHTML;
        // Lọc các phần tử có thuộc tính position: fixed và xóa chúng khỏi nội dung in ra
        $(printableArea).find("*").filter(function() {
            return $(this).css("position") === "fixed";
        }).remove();

        while (notToPrints.length > 0) {
            notToPrints[0].parentNode.removeChild(notToPrints[0]);
        }

        window.print();

        // // Đặt lại nội dung trang
        // document.body.innerHTML = restorepage;

        // print xong không thực hiện được các chức năng => reload() lại
        location.reload();
    }
    /* validate form khi nhập */
    function validateWhenType(elementInput, type = 'empty'){
        const idElement         = $(elementInput).attr('id');
        const parent            = $(document).find('[for*="'+idElement+'"]').parent();
        /* validate empty */
        if(type=='empty'){
            const valueElement  = $.trim($(elementInput).val());
            if(valueElement!=''&&valueElement!='0'){
                parent.removeClass('validateErrorEmpty');
                parent.addClass('validateSuccess');
            }else {
                parent.removeClass('validateSuccess');
                parent.addClass('validateErrorEmpty');
            }
        }
        /* validate phone */ 
        if(type=='phone'){
            const valueElement = $.trim($(elementInput).val());
            if(valueElement.length>=10&&/^\d+$/.test(valueElement)){
                parent.removeClass('validateErrorPhone');
                parent.addClass('validateSuccess');
            }else {
                parent.removeClass('validateSuccess');
                parent.addClass('validateErrorPhone');
            }
        }
        /* validate email */ 
        if(type=='email'){
            const valueElement = $.trim($(elementInput).val());
            /* check empty (nếu required) */
            if($(elementInput).prop('required')){
                if(valueElement==''){
                    parent.removeClass('validateSuccess');
                    parent.removeClass('validateErrorEmail');
                    parent.addClass('validateErrorEmpty');
                    return false;
                }
                /* check email hợp lệ */
                if(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valueElement)){
                    parent.removeClass('validateErrorEmail');
                    parent.removeClass('validateErrorEmpty');
                    parent.addClass('validateSuccess');
                }else {
                    parent.removeClass('validateSuccess');
                    parent.removeClass('validateErrorEmpty');
                    parent.addClass('validateErrorEmail');
                }
            }else {
                /* check email hợp lệ */
                if(/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valueElement)){
                    parent.removeClass('validateErrorEmail');
                    parent.removeClass('validateErrorEmpty');
                    parent.addClass('validateSuccess');
                }
            }
        }
    }
    /* tính năng registry email ở footer */
    function submitFormRegistryEmail(idForm){
        event.preventDefault();
        const inputEmail = $('#'+idForm).find('[name*=registry_email]');
        const valueEmail = inputEmail.val();
        if(isValidEmail(valueEmail)){
            $.ajax({
                url         : '{{ route("ajax.registryEmail") }}',
                type        : 'get',
                dataType    : 'json',
                data        : {
                    registry_email : valueEmail
                },
                success     : function(response){
                    /* bật thông báo */
                    setMessageModal(response.title, response.content);
                    /* clear value input */
                    inputEmail.val('');
                }
            });
        }else {
            inputEmail.val('');
            inputEmail.attr('placeholder', 'Email không hợp lệ!');
        }
    }
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    /* hiện thông báo modal thành công */
    function openCloseModal(idModal, action = null){
        const elementModal  = $('#'+idModal);
        const flag          = elementModal.css('display');
        /* tooggle */
        if(action==null){
            if(flag=='none'){
                elementModal.css('display', 'flex');
                $('#js_openCloseModal_blur').addClass('blurBackground');
                $('body').css('overflow', 'hidden');
            }else {
                elementModal.css('display', 'none');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
                $('body').css('overflow', 'unset');
            }
        }
        /* đóng */
        if(action=='close'){
            elementModal.css('display', 'none');
            $('#js_openCloseModal_blur').removeClass('blurBackground');
            $('body').css('overflow', 'unset');
        }
        /* mở */
        if(action=='open'){
            elementModal.css('display', 'flex');
            $('#js_openCloseModal_blur').addClass('blurBackground');
            $('body').css('overflow', 'hidden');
        }
    }
</script>