@push('head-custom')
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/vendors.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/css/plugins/forms/pickers/form-pickadate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('sources/admin/app-assets/vendors/css/forms/select/select2.min.css') }}">
@endpush
@php
	$imageSlider 		= 'linear-gradient(-180deg, rgba(0, 123, 255, 0.5), rgb(0, 90, 180))';
	foreach($item->files as $file){
		if($file->file_type=='slider') $imageSlider = 'url('.$file->file_path.') center center';
	}
@endphp
<div class="bookOnline" style="background: {{ $imageSlider }}">
	{{-- <div class="container">

	</div> --}}
</div>

<div class="container">
	<div class="bookFormSort" onClick="hideShowAround();">
		<div class="bookFormSort_head">
			<div class="active">
				<i class="fa-solid fa-ship"></i>Vé tàu cao tốc
			</div>
			<div>
				<i class="fa-solid fa-suitcase-rolling"></i>Tour du lịch
			</div>
			<div>
				<i class="fa-solid fa-hotel"></i>Khách sạn
			</div>
			<div>
				<i class="fa-solid fa-ticket"></i>Vé Vinpearl
			</div>
		</div>
		<div class="bookFormSort_body">
			<div class="bookFormSort_body_item">
				<div class="flexBox">
					<div class="flexBox_item inputWithIcon location">
						<label for="ship_port_departure_id_1">Điểm đi</label>
						<select class="select2 form-select select2-hidden-accessible" name="ship_port_departure_id_1" onchange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite_1');" tabindex="-1" aria-hidden="true">
							<option value="" data-select2-id="3">- Lựa chọn -</option>																									
						</select>
					</div>
					<div class="flexBox_item inputWithIcon location">
						<label for="ship_port_location_id_1">Điểm đến</label>
						<select class="select2 form-select select2-hidden-accessible" name="ship_port_location_id_1" onchange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite_1');" tabindex="-1" aria-hidden="true">
							<option value="" data-select2-id="3">- Lựa chọn -</option>																									
						</select>
					</div>
				</div>
				<div class="inputWithIcon adult">
					<label for="bookFormSort_date">Số hành khách</label>
					<input type="text" class="form-control" name="date_1" value="1 Người lớn, 2 Trẻ em, 1 Cao tuổi" readonly="readonly" onchange="loadDeparture(1);" required="">
				</div>
			</div>
			<div class="bookFormSort_body_item">
				<div class="inputWithIcon date">
					<label for="bookFormSort_date">Ngày khởi hành</label>
					<input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_1" value="2022-09-17" readonly="readonly" onchange="loadDeparture(1);" required="">
				</div>
				<div style="text-align:right;">
					<div class="buttonSecondary"><i class="fa-solid fa-magnifying-glass"></i>Tìm chuyến tàu</div>
				</div>
			</div>
		</div>
		
	</div>
</div>
@push('scripts-custom')
    <script src="{{ asset('sources/admin/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
    <script type="text/javascript">
        function hideShowAround(action = 'on'){
			const elemt = $('#js_hideShowAround');
			if(elemt.length==0){
				$('<div id="js_hideShowAround" style="width:100%;height:100%;position:fixed;background:rgba(0, 0, 0, 0.5);top:0;left:0;z-index:100;" onClick="hideShowAround(\'off\');"></div>').appendTo('body');
			}else {
				if(action=='off') elemt.remove();
			}
		}
    </script>
@endpush