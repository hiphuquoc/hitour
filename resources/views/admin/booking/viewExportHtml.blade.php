@include('admin.booking.confirmBooking', compact('item', 'infoStaff'))
<script type="text/javascript">
    window.addEventListener("load", function() {
        window.print();
        setTimeout(() => {
            window.location = document.referrer;
        }, 0);
    });
</script>