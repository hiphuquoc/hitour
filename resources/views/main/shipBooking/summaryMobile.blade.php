<div id="js_showHideBox_element" class="summaryBoxMobile" onClick="showHideBox();">
    <div class="contentWithViewMore">
        <div class="contentWithViewMore_title">
            <h2>Tóm tắt booking</h2>
            {{-- <div class="contentWithViewMore_title_close" onClick="showHideBox();"><i class="fa-sharp fa-solid fa-xmark"></i></div> --}}
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
            const elemt         = $('#js_showHideBox_element');
            const displayElemt  = elemt.css('display');
            if(displayElemt=='none'){
                elemt.css({
                    'display'    : 'flex'
                })
            }else {
                elemt.css({
                    'display'    : 'none'
                })
            }
        }
    </script>
@endpush