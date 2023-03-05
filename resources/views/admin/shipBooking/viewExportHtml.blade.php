@include('admin.shipBooking.confirmBooking', compact('item', 'infoStaff'))
<script type="text/javascript">
    window.addEventListener("load", function() {
        setTimeout(() => {
            window.print();
            setTimeout(() => {
                window.location = document.referrer;
            }, 0)
        }, 100);
    });
</script>
