<input type="hidden" id="hotel_booking_adults" name="adults" value="{{ $dataForm['adults'] ?? 1 }}" /> 
<input type="hidden" id="hotel_booking_childs" name="childs" value="{{ $dataForm['childs'] ?? 0 }}" /> 
<input type="hidden" id="hotel_booking_quantity" name="quantity" value="{{ $dataForm['quantity'] ?? 0 }}" />
<div class="inputWithLabelInside peopleGroup inputWithForm">
    <label for="bookFormSort_date">Số hành khách & phòng</label>
    
        <input type="text" id="js_setValueQuantityHotel_idWrite" class="form-control inputWithForm_input" value="{{ $dataForm['adults'] ?? 1 }} Người lớn, {{ $dataForm['childs'] ?? 0 }} Trẻ em, {{ $dataForm['quantity'] ?? 0 }} Phòng" readonly="readonly" aria-label="Số khách đặt phòng khách sạn" required="">
        <div class="inputWithForm_form">
            <div class="formBox">
                <div class="formBox_labelOneRow">
                    <div class="formBox_labelOneRow_item">
                        <div class="labelWithIcon">
                            <div class="labelWithIcon_icon adult"></div>
                            <div class="labelWithIcon_label">
                                Người lớn (Năm sinh từ {{ date('Y', time()) - 12 }})
                            </div>
                        </div>
                        <div class="inputNumberCustom"> 
                            <div class="inputNumberCustom_button" onclick="changeValueInputHotel('js_changeValueInputHotel_input_nguoilon', 'minus');">
                                <i class="fa-solid fa-minus"></i>
                            </div>
                            <input id="js_changeValueInputHotel_input_nguoilon" class="inputNumberCustom_input" type="number" value="{{ $dataForm['adults'] ?? 1 }}" aria-label="Số người lớn đặt phòng khách sạn" onkeyup="setValueQuantityHotel()">
                            <div class="inputNumberCustom_button" onclick="changeValueInputHotel('js_changeValueInputHotel_input_nguoilon', 'plus');">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="formBox_labelOneRow_item">
                        <div class="labelWithIcon">
                            <div class="labelWithIcon_icon children"></div>
                            <div class="labelWithIcon_label">
                                Trẻ em (Năm sinh từ {{ date('Y', time()) - 6 }} - {{ date('Y', time()) - 11 }})
                            </div>
                        </div>
                        <div class="inputNumberCustom"> 
                            <div class="inputNumberCustom_button" onclick="changeValueInputHotel('js_changeValueInputHotel_input_treem', 'minus');">
                                <i class="fa-solid fa-minus"></i>
                            </div>
                            <input id="js_changeValueInputHotel_input_treem" class="inputNumberCustom_input" type="number" value="{{ $dataForm['childs'] ?? 0 }}" aria-label="Số trẻ em đặt phòng khách sạn" onkeyup="setValueQuantityHotel()">
                            <div class="inputNumberCustom_button" onclick="changeValueInputHotel('js_changeValueInputHotel_input_treem', 'plus');">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                    <div class="formBox_labelOneRow_item">
                        <div class="labelWithIcon">
                            <div class="labelWithIcon_icon hotelRoom"></div>
                            <div class="labelWithIcon_label">
                                Số phòng
                            </div>
                        </div>
                        <div class="inputNumberCustom"> 
                            <div class="inputNumberCustom_button" onclick="changeValueInputHotel('js_changeValueInputHotel_input_phong', 'minus');">
                                <i class="fa-solid fa-minus"></i>
                            </div>
                            <input id="js_changeValueInputHotel_input_phong" class="inputNumberCustom_input" type="number" value="{{ $dataForm['quantity'] ?? 0 }}" aria-label="Số người cao tuổi đặt phòng khách sạn" onkeyup="setValueQuantityHotel()">
                            <div class="inputNumberCustom_button" onclick="changeValueInputHotel('js_changeValueInputHotel_input_phong', 'plus');">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function setValueQuantityHotel(){
            /* input lưu giá trị */ 
			const valueAdult 	= parseInt($('#js_changeValueInputHotel_input_nguoilon').val());
			const valueChild 	= parseInt($('#js_changeValueInputHotel_input_treem').val());
			const valueQuantity = parseInt($('#js_changeValueInputHotel_input_phong').val());
            $('#hotel_booking_adults').val(valueAdult);
            $('#hotel_booking_childs').val(valueChild);
            $('#hotel_booking_quantity').val(valueQuantity);
            /* input này chỉ show */
            const valueFull 	= valueAdult+' Người lớn, '+valueChild+' Trẻ em, '+valueQuantity+' Phòng';
			$('#js_setValueQuantityHotel_idWrite').val(valueFull);
		}
        function changeValueInputHotel(idInput, action){
			const valueInput 	= parseInt($('#'+idInput).val());
			if(action=='plus'){
				$('#'+idInput).val(parseInt(valueInput + 1));
			}else if(action=='minus'&&valueInput>0){
				$('#'+idInput).val(parseInt(valueInput - 1));
			}
			setValueQuantityHotel();
		}
    </script>
@endpush