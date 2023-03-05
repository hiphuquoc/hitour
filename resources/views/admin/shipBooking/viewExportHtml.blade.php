@include('admin.shipBooking.confirmBooking', compact('item', 'infoStaff'))
<script type="text/javascript">
    window.addEventListener("load", function() {
        // if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        //     /* nếu sử dụng điện thoại thì không tự gọi tính năng in */
        // } else {
        //     window.print();
        // }
        window.print();
        /* sự kiện chuyển hướng sau khi in */
        window.addEventListener("afterprint", function(event) {
            window.location = document.referrer;
        })
    });
</script>
