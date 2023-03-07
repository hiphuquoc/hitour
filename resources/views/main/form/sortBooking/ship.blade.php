@php
    $dataShipPort 		= \App\Models\ShipPort::all();
@endphp
<div class="bookFormSortShip">
    <div class="bookFormSortShip_column">
        <!-- One column -->
        <div class="bookFormSortShip_column_item">
            <div class="inputWithIconBetween">
                <div class="inputWithIconBetween_item inputWithLabelInside">
                    <label for="js_loadShipLocationByShipDeparture_element">Điểm đi</label>
                    <select id="js_loadShipLocationByShipDeparture_element" class="select2 form-select select2-hidden-accessible" name="ship_port_departure_id" onchange="loadShipLocationByShipDeparture(this, 'js_loadShipLocationByShipDeparture_idWrite');" tabindex="-1" aria-hidden="true">
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
                <div class="inputWithIconBetween_icon">
                    <img src="/images/main/svg/icon-round.svg" alt="đặt vé tàu cao tốc" title="đặt vé tàu cao tốc" />
                </div>
                <div class="inputWithIconBetween_item inputWithLabelInside">
                    <label for="js_loadShipLocationByShipDeparture_idWrite">Điểm đến</label>
                    <select id="js_loadShipLocationByShipDeparture_idWrite" class="select2 form-select select2-hidden-accessible" name="ship_port_location_id" tabindex="-1" aria-hidden="true">
                        {{-- <option value="">- Lựa chọn -</option> --}}
                    </select>
                </div>
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortShip_column_item">
            <!-- One column -->
            <div class="bookFormSortShip_input_item">
                <div class="inputWithLabelInside date">
                    <label for="input_date_ship_1">Ngày khởi hành</label>
                    <input type="text" class="form-control flatpickr-basic flatpickr-input active" id="input_date_ship_1" name="date_1" value="{{ date('Y-m-d', time() + 86400) }}" aria-label="Ngày đi tàu cao tốc" readonly="readonly" required>
                </div>
            </div>
        </div>
    </div>
    <div class="bookFormSortShip_column">
        <!-- One column -->
        <div class="bookFormSortShip_column_item">
            <div class="inputWithLabelInside adult inputWithForm">
                <label for="bookFormSort_date">Số hành khách</label>
                {{-- <div class="inputWithForm"> --}}
                    <input type="text" id="js_setValueQuantityShip_idWrite" class="form-control inputWithForm_input" name="quantity" value="1 Người lớn, 0 Trẻ em, 0 Cao tuổi" readonly="readonly" aria-label="Số khách đặt vé tàu cao tốc" required>
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
                                        <input id="js_changeValueInputShip_input_nguoilon" class="inputNumberCustom_input" type="number" name="adult_ship" value="1" aria-label="Số người lớn đặt vé tàu cao tốc" onkeyup="setValueQuantityShip()" />
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
                                        <input id="js_changeValueInputShip_input_treem" class="inputNumberCustom_input" type="number" name="child_ship" value="0" aria-label="Số trẻ em đặt vé tàu cao tốc" onkeyup="setValueQuantityShip()" />
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
                                        <input id="js_changeValueInputShip_input_caotuoi" class="inputNumberCustom_input" type="number" name="old_ship" value="0" aria-label="Số người cao tuổi đặt vé tàu cao tốc" onkeyup="setValueQuantityShip()" />
                                        <div class="inputNumberCustom_button" onClick="changeValueInputShip('js_changeValueInputShip_input_caotuoi', 'plus');">
                                            <i class="fa-solid fa-plus"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {{-- </div> --}}
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortShip_column_item button">
            <div class="buttonSecondary" onClick="submitForm('shipBookingSort');" style="padding-top:0.5rem !important;padding-bottom:0.5rem !important;">
                <i class="fa-solid fa-magnifying-glass"></i>Tìm chuyến tàu
            </div>
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function loadShipLocationByShipDeparture(element, idWrite, namePortActive = null){
            const idShipPort = $(element).val();
            $.ajax({
                url         : '{{ route("main.shipBooking.loadShipLocation") }}',
                type        : 'get',
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
            console.log(123);
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