@php
    $tmp                = \App\Models\TourLocation::select('*')
                            ->with('region')
                            ->get();
    $dataTourLocation   = [];
    foreach($tmp as $tourLocation){
        $dataTourLocation[$tourLocation->region->name][] = $tourLocation;
    }
@endphp
<div class="bookFormSort_body_item">
    <div class="flexBox">
        <div class="flexBox_item inputWithIcon location">
            <label for="ship_port_departure_id">Điểm đến</label>
            <select id="js_loadTourByTourLocation_element" class="select2 form-select select2-hidden-accessible" name="tour_location_id" onchange="loadTourByTourLocation(this, 'js_loadTourByTourLocation_idWrite');" tabindex="-1" aria-hidden="true">
                @foreach($dataTourLocation as $region => $tourLocations)
                    <optgroup label="{{ $region }}, Việt Nam">
                    @foreach($tourLocations as $tourLocation)
                        @php
                            $selected	= null;
                            if(!empty($item->id)&&$item->id==$tourLocation->id) $selected = 'selected';
                        @endphp
                        <option value="{{ $tourLocation->id }}" {{ $selected }}>{{ $tourLocation->display_name }}</option>
                    @endforeach
                @endforeach
            </select>
        </div>
        <div class="flexBox_item inputWithIcon location">
            <label for="ship_port_location_id">Danh sách tour</label>
            <select id="js_loadTourByTourLocation_idWrite" class="select2 form-select select2-hidden-accessible" name="tour_info_id" tabindex="-1" aria-hidden="true">
                {{-- <option value="">- Lựa chọn -</option> --}}
            </select>
        </div>
    </div>
    <div class="inputWithIcon adult">
        <label for="bookFormSort_date">Số hành khách</label>
        <div class="inputWithForm">
            <input type="text" id="js_setValueQuantityTour_idWrite" class="form-control inputWithForm_input" name="date_1" value="1 Người lớn, 0 Trẻ em, 0 Cao tuổi" readonly="readonly" required>
            <div class="inputWithForm_form">
                <div class="formBox">
                    <div class="formBox_labelOneRow">
                        <div class="formBox_labelOneRow_item">
                            <div>
                                <label>Người lớn</label>
                                <div style="font-size: 0.95rem;">Năm sinh từ {{ date('Y', time()) - 12 }} - {{ date('Y', time()) - 59 }}</div>
                            </div>
                            <div class="inputNumberCustom"> 
                                <div class="inputNumberCustom_button" onClick="changeValueInputTour('js_changeValueInputTour_input_nguoilon', 'minus');">
                                    <i class="fa-solid fa-minus"></i>
                                </div>
                                <input id="js_changeValueInputTour_input_nguoilon" class="inputNumberCustom_input" type="text" name="adult_tour" value="1" />
                                <div class="inputNumberCustom_button" onClick="changeValueInputTour('js_changeValueInputTour_input_nguoilon', 'plus');">
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
                                <div class="inputNumberCustom_button" onClick="changeValueInputTour('js_changeValueInputTour_input_treem', 'minus');">
                                    <i class="fa-solid fa-minus"></i>
                                </div>
                                <input id="js_changeValueInputTour_input_treem" class="inputNumberCustom_input" type="text" name="child_tour" value="0" />
                                <div class="inputNumberCustom_button" onClick="changeValueInputTour('js_changeValueInputTour_input_treem', 'plus');">
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
                                <div class="inputNumberCustom_button" onClick="changeValueInputTour('js_changeValueInputTour_input_caotuoi', 'minus');">
                                    <i class="fa-solid fa-minus"></i>
                                </div>
                                <input id="js_changeValueInputTour_input_caotuoi" class="inputNumberCustom_input" type="text" name="old_tour" value="0" />
                                <div class="inputNumberCustom_button" onClick="changeValueInputTour('js_changeValueInputTour_input_caotuoi', 'plus');">
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

@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            loadTourByTourLocation($('#js_loadTourByTourLocation_element'), 'js_loadTourByTourLocation_idWrite');
        });

        function setValueQuantityTour(){
			const valueAdult 	= parseInt($('#js_changeValueInputTour_input_nguoilon').val());
			const valueChild 	= parseInt($('#js_changeValueInputTour_input_treem').val());
			const valueOld 		= parseInt($('#js_changeValueInputTour_input_caotuoi').val());
			const valueFull 	= valueAdult+' Người lớn, '+valueChild+' Trẻ em, '+valueOld+' Cao tuổi';
			$('#js_setValueQuantityTour_idWrite').val(valueFull);
		}
        function changeValueInputTour(idInput, action){
			const valueInput 	= parseInt($('#'+idInput).val());
			if(action=='plus'){
				$('#'+idInput).val(parseInt(valueInput + 1));
			}else if(action=='minus'&&valueInput>0){
				$('#'+idInput).val(parseInt(valueInput - 1));
			}
			setValueQuantityTour();
		}
        function loadTourByTourLocation(element, idWrite){
            const idTourLocation = $(element).val();
            $.ajax({
                url         : '{{ route("main.tourBooking.loadTour") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    '_token'        	: '{{ csrf_token() }}',
                    tour_location_id    : idTourLocation
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }
        
    </script>
@endpush