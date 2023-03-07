@php
    $tmp                = \App\Models\TourLocation::select('*')
                            ->with('region')
                            ->get();
    $dataTourLocation   = [];
    foreach($tmp as $tourLocation){
        $dataTourLocation[$tourLocation->region->name][] = $tourLocation;
    }
@endphp
<div class="bookFormSortService">
    <div class="bookFormSortService_input">
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithLabelInside location">
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
        </div>
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithLabelInside">
                <label for="ship_port_location_id">Danh sách tour</label>
                <select id="js_loadTourByTourLocation_idWrite" class="select2 form-select select2-hidden-accessible" name="tour_info_id" tabindex="-1" aria-hidden="true">
                    {{-- <option value="">- Lựa chọn -</option> --}}
                </select>
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithLabelInside date">
                <label for="bookFormSort_date">Ngày khởi hành</label>
                <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" value="{{ date('Y-m-d', time() + 86400) }}" aria-label="Ngày khởi hành tour du lịch" readonly="readonly" required>
            </div>
        </div>
    </div>
    <div class="bookFormSortService_button" style="flex:0 0 155px;">
        <div class="buttonSecondary" onClick="submitForm('tourBookingSort');">
            <i class="fa-solid fa-check"></i>Đặt tour ngay
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