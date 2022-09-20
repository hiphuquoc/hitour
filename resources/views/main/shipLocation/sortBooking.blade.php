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
	$dataShipPort 		= \App\Models\ShipPort::all();
@endphp
<div class="bookOnline" style="background: {{ $imageSlider }}">
	{{-- <div class="container">

	</div> --}}
</div>

<div class="container">
	<form id="formBookingSort" method="get" action="{{ route('main.shipBooking.form') }}">
	@csrf
	<div class="bookFormSort" onClick="hideShowAround();">
		<div class="bookFormSort_head">
			<div class="active" data-tab="shipBookingForm" onClick="changeTab(this);">
				<i class="fa-solid fa-ship"></i>Vé tàu cao tốc
			</div>
			<div data-tab="tourBookingForm" onClick="changeTab(this);">
				<i class="fa-solid fa-suitcase-rolling"></i>Tour du lịch
			</div>
			<div data-tab="hotelBookingForm" onClick="changeTab(this);">
				<i class="fa-solid fa-hotel"></i>Khách sạn
			</div>
			<div data-tab="ticketBookingForm" onClick="changeTab(this);">
				<i class="fa-solid fa-ticket"></i>Vé Vinpearl
			</div>
		</div>
		<div class="bookFormSort_body">
			<!-- Ship booking form -->
			<div id="shipBookingForm">
				<div class="bookFormSort_body_item">
					<div class="flexBox">
						<div class="flexBox_item inputWithIcon location">
							<label for="ship_port_departure_id">Điểm đi</label>
							<select  id="js_loadShipLocationByShipDeparture_element" class="select2 form-select select2-hidden-accessible" name="ship_port_departure_id" onchange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite');" tabindex="-1" aria-hidden="true">
								{{-- <option value="">- Lựa chọn -</option> --}}
								@foreach($dataShipPort as $port)
									@php
										$selected	= null;
										/* kiểm tra cho trang ship_info */
										if(!empty($item->portDeparture->name)&&$item->portDeparture->name==$port->name) $selected = 'selected';
										/* kiểm tra cho trang ship_location */
										if(!empty($item->ships[0]->portDeparture->name)&&$item->ships[0]->portDeparture->name==$port->name) $selected = 'selected';
										$portName 	= \App\Helpers\Build::buildFullShipPort($port);
									@endphp
									<option value="{{ $port->id }}" {{ $selected }}>{{ $portName }}</option>
								@endforeach
							</select>
						</div>
						<div class="flexBox_item inputWithIcon location">
							<label for="ship_port_location_id">Điểm đến</label>
							<select id="js_loadShipLocationByShipDeparture_idWrite" class="select2 form-select select2-hidden-accessible" name="ship_port_location_id" tabindex="-1" aria-hidden="true">
								{{-- <option value="">- Lựa chọn -</option> --}}
							</select>
						</div>
					</div>
					<div class="inputWithIcon adult">
						<label for="bookFormSort_date">Số hành khách</label>
						<div class="inputWithForm">
							<input type="text" id="js_setValueQuantityAll_idWrite" class="form-control inputWithForm_input" name="date_1" value="1 Người lớn, 0 Trẻ em, 0 Cao tuổi" readonly="readonly" required>
							<div class="inputWithForm_form">
								<div class="formBox">
									<div class="formBox_labelOneRow">
										<div class="formBox_labelOneRow_item">
											<div>
												<label>Người lớn</label>
												<div style="font-size: 0.95rem;">Năm sinh từ {{ date('Y', time()) - 12 }} - {{ date('Y', time()) - 59 }}</div>
											</div>
											<div class="inputNumberCustom"> 
												<div class="inputNumberCustom_button" onClick="changeValueInput('js_changeValueInput_input_nguoilon', 'minus');">
													<i class="fa-solid fa-minus"></i>
												</div>
												<input id="js_changeValueInput_input_nguoilon" class="inputNumberCustom_input" type="text" name="adult" value="1" />
												<div class="inputNumberCustom_button" onClick="changeValueInput('js_changeValueInput_input_nguoilon', 'plus');">
													<i class="fa-solid fa-plus"></i>
												</div>
											</div>
										</div>
										<div class="formBox_labelOneRow_item">
											<div>
												<label>Trẻ em</label>
												<div style="font-size: 0.95rem;">Năm sinh từ {{ date('Y', time()) - 6 }} - {{ date('Y', time()) - 11 }}</div>
											</div>
											<div class="inputNumberCustom"> 
												<div class="inputNumberCustom_button" onClick="changeValueInput('js_changeValueInput_input_treem', 'minus');">
													<i class="fa-solid fa-minus"></i>
												</div>
												<input id="js_changeValueInput_input_treem" class="inputNumberCustom_input" type="text" name="child" value="0" />
												<div class="inputNumberCustom_button" onClick="changeValueInput('js_changeValueInput_input_treem', 'plus');">
													<i class="fa-solid fa-plus"></i>
												</div>
											</div>
										</div>
										<div class="formBox_labelOneRow_item">
											<div>
												<label>Cao tuổi</label>
												<div style="font-size: 0.95rem;">Năm sinh từ {{ date('Y', time()) - 60 }}</div>
											</div>
											<div class="inputNumberCustom"> 
												<div class="inputNumberCustom_button" onClick="changeValueInput('js_changeValueInput_input_caotuoi', 'minus');">
													<i class="fa-solid fa-minus"></i>
												</div>
												<input id="js_changeValueInput_input_caotuoi" class="inputNumberCustom_input" type="text" name="old" value="0" />
												<div class="inputNumberCustom_button" onClick="changeValueInput('js_changeValueInput_input_caotuoi', 'plus');">
													<i class="fa-solid fa-plus"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="bookFormSort_body_item">
					<div class="inputWithIcon date">
						<label for="bookFormSort_date">Ngày khởi hành</label>
						<input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date_1" value="{{ date('Y-m-d', time() + 86400) }}" readonly="readonly" onchange="loadDeparture(1);" required>
					</div>
					<div style="text-align:right;">
						<div class="buttonSecondary" onClick="submitForm('formBookingSort');">
							<i class="fa-solid fa-magnifying-glass"></i>Tìm chuyến tàu
						</div>
					</div>
				</div>
			</div>
			<!-- Tour booking form -->
			<div id="tourBookingForm" style="display: none;">
				<div class="bookFormSort_body_item">
					Form đặt Tour đang cập nhật
				</div>
			</div>
			<!-- Tour booking form -->
			<div id="hotelBookingForm" style="display: none;">
				<div class="bookFormSort_body_item">
					Form đặt Khách sạn đang cập nhật
				</div>
			</div>
			<!-- Tour booking form -->
			<div id="ticketBookingForm" style="display: none;">
				<div class="bookFormSort_body_item">
					Form đặt Vé Vinpearl đang cập nhật
				</div>
			</div>
		</div>
		
	</div>
	</form>
</div>
@push('scripts-custom')
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
				$('<div id="js_hideShowAround" style="width:100%;height:100%;position:fixed;background:rgba(0, 0, 0, 0.5);top:0;left:0;z-index:100;" onClick="hideShowAround(\'off\');"></div>').appendTo('body');
			}else {
				if(action=='off') elemt.remove();
			}
		}

		function loadShipLocationByShipDeparture(element, idWrite, namePortActive = null){
            const idShipPort = $(element).val();
            $.ajax({
                url         : '{{ route("main.shipBooking.loadShipLocation") }}',
                type        : 'post',
                dataType    : 'html',
                data        : {
                    '_token'        	: '{{ csrf_token() }}',
                    ship_port_id    	: idShipPort,
					name_port_active    : namePortActive
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }

		function changeValueInput(idInput, action){
			const valueInput 	= parseInt($('#'+idInput).val());
			if(action=='plus'){
				$('#'+idInput).val(parseInt(valueInput + 1));
			}else if(action=='minus'&&valueInput>0){
				$('#'+idInput).val(parseInt(valueInput - 1));
			}
			setValueQuantityAll();
		}

		function setValueQuantityAll(){
			const valueAdult 	= parseInt($('#js_changeValueInput_input_nguoilon').val());
			const valueChild 	= parseInt($('#js_changeValueInput_input_treem').val());
			const valueOld 		= parseInt($('#js_changeValueInput_input_caotuoi').val());
			const valueFull 	= valueAdult+' Người lớn, '+valueChild+' Trẻ em, '+valueOld+' Cao tuổi';
			$('#js_setValueQuantityAll_idWrite').val(valueFull);
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