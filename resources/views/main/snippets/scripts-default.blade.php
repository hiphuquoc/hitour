<!-- BEGIN: Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- END: Jquery -->

<!-- BEGIN: SLICK -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- END: SLICK -->

<script type="text/javascript">
        $(window).ready(function(){
            loadImage();

            /* fixed sidebar khi scroll */
            const elemt                 = $('.js_scrollFixed');
            const widthResponsive   = $(window).width();
            if(elemt.length>0&&widthResponsive>991){
                const widthElemt            = elemt.parent().width();
                const positionTopElemt      = elemt.offset().top;
                const heightFooter          = 500;
                $(window).scroll(function(){
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
                });
            }
        });

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
        function loadImage(){
            $(document).find('img[data-src]').each(function(){
                $(this).attr('src', $(this).attr('data-src'));
            });
        }

        function autoLoadTocContentWithIcon(idElement){
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
                url         : '{{ route("main.ship.loadTocContent") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'    : '{{ csrf_token() }}',
                    dataSend    : dataTocContent
                },
                success     : function(data){
                    /* tính toán chiều cao sidebar */
                    const heightW       = $(window).height();
                    const heightUsed    = $('#js_autoLoadTocContentWithIcon_idWrite').parent().outerHeight();
                    const height        = parseInt(heightW - heightUsed);
                    $('#js_autoLoadTocContentWithIcon_idWrite').css('max-height', 'calc('+height+'px - 3rem)').html(data);
                    // $('#js_autoLoadTocContentWithIcon_idWrite')
                }
            });
        }
</script>