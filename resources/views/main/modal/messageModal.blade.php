<div id="messageModal" class="modalBox">
    <div class="modalBox_bg js_toggleMessageModal"></div>
    <div class="modalBox_box">
        @include('main.modal.contentMessageModal', [
            'title' => $title ?? null,
            'content'   => $content ?? null
        ])
    </div>
</div>
@pushonce('scripts-custom')
    <script type="text/javascript">
        $('.js_toggleMessageModal').on('click', function(){
            openCloseModal('messageModal');
        })
        /* set content messgae Modal ajax */
        function setMessageModal(title = null, content = null){
            $.ajax({
                url         : '{{ route("ajax.setMessageModal") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    title, content
                },
                success     : function(response){
                    $('#messageModal .modalBox_box').html(response);
                    openCloseModal('messageModal');
                }
            });
        }
    </script>
@endpushonce