<!-- BEGIN: Jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<!-- END: Jquery -->

<!-- BEGIN: SLICK -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- END: SLICK -->

<script type="text/javascript">
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

        $(window).ready(function(){
            $(window).ready(function(){
                // loadImage();
            });
        });
</script>