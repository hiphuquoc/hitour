<!-- BEGIN: Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- END: Jquery -->

<!-- BEGIN: SLICK -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- END: SLICK -->

<script type="text/javascript">
    $(window).ready(function(){
        loadImage()
        addTableResponsive()
        /* fixed sidebar khi scroll */
        const elemt                         = $('.js_scrollFixed');
        const widthElemt                    = elemt.parent().width();
        const widthResponsive               = $(window).width();
        if(elemt.length>0&&widthResponsive>991){
            const positionTopElemt          = elemt.offset().top;
            $(window).scroll(function(){
                const flagScroll            = $('#js_scrollFixed_flag').val();
                if(flagScroll!='false'){
                    const heightFooter      = 500;
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
                }
            });
        }

        function addTableResponsive(){
            $(document).find('table:not(.noResponsive)').each(function(){
                $(this).wrap('<div class="customScrollBar-x"></div>');
            })

            // .wrap('<div class="tableResponsive"></div>')
        }
</script>