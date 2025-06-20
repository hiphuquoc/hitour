@push('head-custom')
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush

<div class="container">
	<div class="bookFormSort" onClick="hideShowAround();">
		<div class="bookFormSort_head">
			<div {{ !empty($active)&&$active=='ship' ? 'class=active' : null }} data-tab="shipBookingForm" onClick="changeTab(this);">
				<div class="show-767 maxLine_1"><i class="fa-solid fa-ship"></i>Tàu</div>
				<div class="hide-767 maxLine_1"><i class="fa-solid fa-ship"></i>Tàu cao tốc</div>
			</div>
			<div {{ !empty($active)&&$active=='tour' ? 'class=active' : null }} data-tab="tourBookingForm" onClick="changeTab(this);">
				<div class="maxLine_1"><i class="fa-solid fa-suitcase-rolling"></i>Tour</div>
			</div>
			<div {{ !empty($active)&&$active=='combo' ? 'class=active' : null }} data-tab="comboBookingForm" onClick="changeTab(this);">
				<div class="maxLine_1"><i class="fa-solid fa-gift"></i>Combo</div>
			</div>
			<div {{ !empty($active)&&$active=='hotel' ? 'class=active' : null }} data-tab="hotelBookingForm" onClick="changeTab(this);">
				<div class="maxLine_1"><i class="fa-solid fa-bed"></i>Khách sạn</div>
			</div>
			<div {{ !empty($active)&&$active=='service' ? 'class=active' : null }} data-tab="ticketBookingForm" onClick="changeTab(this);">
				<div class="maxLine_1"><i class="fa-solid fa-ticket"></i>Vé vui chơi</div>
			</div>
		</div>
		<div class="bookFormSort_body">
			<!-- Ship booking form -->
			<div id="shipBookingForm" {{ !empty($active)&&$active!='ship' ? 'style=display:none;' : null }}>
				<form id="shipBookingSort" method="GET" action="{{ route('main.shipBooking.form') }}">
					@include('main.form.sortBooking.ship', compact('item'))
				</form>
			</div>
			<!-- Tour booking form -->
			<div id="tourBookingForm" {{ !empty($active)&&$active!='tour' ? 'style=display:none;' : null }}>
				<form id="tourBookingSort" method="GET" action="{{ route('main.tourBooking.form') }}">
					@include('main.form.sortBooking.tour', compact('item'))
				</form>
			</div>
			<!-- Combo booking form -->
			<div id="comboBookingForm" {{ !empty($active)&&$active!='combo' ? 'style=display:none;' : null }}>
				<form id="comboBookingSort" method="GET" action="{{ route('main.comboBooking.form') }}">
					@include('main.form.sortBooking.combo', compact('item'))
				</form>
			</div>
			<!-- Khách sạn -->
			<div id="hotelBookingForm" {{ !empty($active)&&$active!='hotel' ? 'style=display:none;' : null }}>
				<form id="hotelBookingSort" method="GET" action="{{ route('main.hotelBooking.search') }}">
					@include('main.form.sortBooking.hotel', compact('item'))
				</form>
			</div>
			<!-- Vé dịch vụ -->
			<div id="ticketBookingForm" {{ !empty($active)&&$active!='service' ? 'style=display:none;' : null }}>
				<form id="ticketBookingSort" method="GET" action="{{ route('main.serviceBooking.form') }}">
					@include('main.form.sortBooking.ticket')
				</form>
			</div>
		</div>
	</div>
</div>

@push('scripts-custom')
	<!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
	<!-- ===== -->
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
    <script type="text/javascript">

		$(document).ready(function(){
			loadShipLocationByShipDeparture($('#js_loadShipLocationByShipDeparture_element'), 'js_loadShipLocationByShipDeparture_idWrite', '{{ $item->portLocation->name ?? $item->ships[0]->portLocation->name ?? null }}');
		});

		function submitForm(idForm){
            // event.preventDefault();
            $('#'+idForm).submit();
        }

        function hideShowAround(action = 'on'){
			const elemt = $('#js_hideShowAround');
			if(elemt.length==0){
				$('<div id="js_hideShowAround" style="width:100%;height:100%;position:fixed;background-color:rgb(35, 42, 49);opacity: 0.8;top:0;left:0;z-index:100;" onClick="hideShowAround(\'off\');"></div>').appendTo('body');
			}else {
				if(action=='off') elemt.remove();
			}
		}

		function changeTab(element){
			/* active button */
			$(element).parent().children().each(function(){
				$(this).removeClass('active');
			});
			$(element).addClass('active');
			/* xử lý tab content */
			const idContent 		= $(element).data('tab');
			const elementContent 	= $('#'+idContent);
			/* ẩn tất cả tab */
			elementContent.parent().children().each(function(){
				$(this).css('display', 'none');
			});
			/* bật tab được chọn */
			elementContent.css('display', 'block');
		}
    </script>
@endpush