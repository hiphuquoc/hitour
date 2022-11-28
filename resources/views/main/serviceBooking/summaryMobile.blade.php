<div id="js_showHideBox_element" class="summaryBoxMobile customScrollBar-y">
    <div class="contentWithViewMore">
        <div class="contentWithViewMore_title">
            <h2>Tóm tắt booking</h2>
            <div class="contentWithViewMore_title_close" onClick="showHideBox();"><i class="fa-sharp fa-solid fa-xmark"></i></div>
        </div>
        <div class="contentWithViewMore_content">
            <div id="js_loadBookingSummaryMobile_idWrite" class="shipBookingTotalBox">
                <!-- Load Ajax -->
            </div>
        </div>
    </div>
</div>
@push('scripts-custom')
    <script type="text/javascript">
        function showHideBox(){
            const elemt = $('#js_showHideBox_element');
            if(elemt.outerHeight()<10){
                elemt.css({
                    'height'    : '100%',
                    'padding'   : '1rem 1rem 70px 1rem'
                })
            }else {
                elemt.css({
                    'height'    : '0',
                    'padding'   : '0'
                })
            }
        }

    </script>
@endpush