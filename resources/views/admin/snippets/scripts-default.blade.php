<!-- === START:: Scripts Default === -->
<script src="{{ asset('sources/admin/app-assets/vendors/js/vendors.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/core/app-menu.js') }}"></script>
<!-- === END:: Scripts Default === -->
<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-repeater.min.js') }}"></script>
<!-- BEGIN: SWEET ALERT -->
<script src="{{ asset('sources/admin/app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/extensions/ext-component-sweet-alerts.js') }}"></script>
<!-- END: SWEET ALERT -->
<!-- BEGIN: SLICK -->
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<!-- END: SLICK -->
<!-- BEGIN: MENU -->
<script src="{{ asset('sources/admin/app-assets/js/core/app.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/customizer.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/core/app-menu.min.js') }}"></script>
<!-- END: MENU -->

<!-- END: Page Vendor JS-->
<script defer>
    $(window).on('load', function () {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }

        $('[data-bs-toggle="tooltip"]').tooltip();
    })

    $(function () {
        $('[data-toggle="tooltip"]').tooltip({ html : true })
    })
    /* COUNT CHARACTOR */
    $('input, textarea').on('input', function(){
        const idElemt           = $(this).attr('id');
        if(idElemt){
            const lengthInput   = $(this).val().length;
            const elemtShow     = $(document).find("[data-charactor='" + idElemt + "']");
            elemtShow.html(lengthInput);
        }
    })
    /* Setting view */
    function settingView(name, valDefault){
        $.ajax({
            url         : '{{ route("admin.setting.settingView") }}',
            type        : 'get',
            dataType    : 'html',
            data        : {
                name,
                default : valDefault
            },
            success     : function(result){
                location.reload();
            }
        });
    }
    
    function addLoading(idBox, heightBox = 300){
        const htmlLoadding  = '<div style="display:flex;align-items:center;justify-content:center;height:'+heightBox+'px;width:100%;"><div class="spinner-grow text-primary me-1" role="status"><span class="visually-hidden">Loading...</span></div></div>';
        $('#'+idBox).html(htmlLoadding);
    }

    function loaddingFullScreen(){
        const loadingBox    = $('#loadingBox');
        const flag          = loadingBox.css('display');
        if(flag=='none'){
            loadingBox.css('display', 'flex');
            $('body').css('overflow', 'hidden');
        }else {
            loadingBox.css('display', 'none');
            $('body').css('overflow', 'auto');
        }
    }
</script>