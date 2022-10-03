@php
    $dataShipPort 		= \App\Models\ShipPort::all();
@endphp
<form id="shipBookingSort" method="get" action="{{ route('main.shipBooking.form') }}">
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
                <input type="text" id="js_setValueQuantityShip_idWrite" class="form-control inputWithForm_input" name="quantity" value="1 Người lớn, 0 Trẻ em, 0 Cao tuổi" readonly="readonly" required>
                <div class="inputWithForm_form">
                    <div class="formBox">
                        <div class="formBox_labelOneRow">
                            <div class="formBox_labelOneRow_item">
                                <div>
                                    <label>Người lớn</label>
                                    <div style="font-size: 0.95rem;">Năm sinh từ {{ date('Y', time()) - 12 }} - {{ date('Y', time()) - 59 }}</div>
                                </div>
                                <div class="inputNumberCustom"> 
                                    <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_nguoilon', 'minus');">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <input id="js_changeValueInputShip_input_nguoilon" class="inputNumberCustom_input" type="text" name="adult_ship" value="1" />
                                    <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_nguoilon', 'plus');">
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
                                    <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_treem', 'minus');">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <input id="js_changeValueInputShip_input_treem" class="inputNumberCustom_input" type="text" name="child_ship" value="0" />
                                    <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_treem', 'plus');">
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
                                    <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_caotuoi', 'minus');">
                                        <i class="fa-solid fa-minus"></i>
                                    </div>
                                    <input id="js_changeValueInputShip_input_caotuoi" class="inputNumberCustom_input" type="text" name="old_ship" value="0" />
                                    <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_caotuoi', 'plus');">
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
            <div class="buttonSecondary" onClick="submitForm('shipBookingSort');">
                <i class="fa-solid fa-magnifying-glass"></i>Tìm chuyến tàu
            </div>
        </div>
    </div>
</form>

@push('scripts-custom')
    <script type="text/javascript">
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

        function setValueQuantityShip(){
			const valueAdult 	= parseInt($('#js_changeValueInputShip_input_nguoilon').val());
			const valueChild 	= parseInt($('#js_changeValueInputShip_input_treem').val());
			const valueOld 		= parseInt($('#js_changeValueInputShip_input_caotuoi').val());
			const valueFull 	= valueAdult+' Người lớn, '+valueChild+' Trẻ em, '+valueOld+' Cao tuổi';
			$('#js_setValueQuantityShip_idWrite').val(valueFull);
		}
        function changeValueInputShip(idInput, action){
			const valueInput 	= parseInt($('#'+idInput).val());
			if(action=='plus'){
				$('#'+idInput).val(parseInt(valueInput + 1));
			}else if(action=='minus'&&valueInput>0){
				$('#'+idInput).val(parseInt(valueInput - 1));
			}
			setValueQuantityShip();
		}
    </script>
@endpush