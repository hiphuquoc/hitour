<input type="hidden" id="tour_option_id" name="tour_option_id" value="{{ $item->tour_option_id ?? null }}" />

<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="departure_day">Ngày khởi hành</label>
            <input type="text" class="form-control flatpickr-basic flatpickr-input active" id="departure_day" name="departure_day" placeholder="YYYY-MM-DD" value="{{ !empty($item->departure_day) ? date('Y-m-d', strtotime($item->departure_day)) : null }}" readonly="readonly">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="tour_info_id">Chương trình Tour</label>
            <select class="select2 form-select select2-hidden-accessible" id="tour_info_id" name="tour_info_id" onChange="loadOptionTourList(this.value);">
                <option value="0">- Lựa chọn -</option>
                @if(!empty($tourList))
                    @foreach($tourList as $tour)
                        @php
                            if(!empty(old('tour_info_id'))){
                                $selected   = old('tour_info_id')==$tour->id ? 'selected' : null;
                            }else {
                                $selected   = !empty($item->tour_info_id)&&$item->tour_info_id==$tour->id ? 'selected' : null;
                            }
                        @endphp
                        <option value="{{ $tour->id }}" {{ $selected }}>{{ $tour->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="tour_option_id">Option Tour</label>
            <div id="jd_loadOptionTourList_idWrite">
                <!-- load Ajax -->
                @if(!empty($item->tour->options))
                    @php
                        $options = \App\Http\Controllers\AdminTourOptionController::margeTourPriceByDate($item->tour->options);
                    @endphp
                    @include('admin.tourBooking.optionTourList', ['options' => $options, 'optionChecked' => $item->tour_option_id])
                @endif
            </div>
        </div>
        <!-- Form Tour Option - Price - Quantity -->
        <div id="js_loadFormPriceQuantity_idWrite" class="formBox_full formBox_full_item">
            <!-- load Ajax -->
            @if(!empty($item->tour->options))
            
                @php
                    $prices    = [];
                    foreach($item->tour->options as $option) {
                        if($option->id==$item->tour_option_id) {
                            $prices = $option->prices;
                            break;
                        }
                    }
                @endphp
                @include('admin.tourBooking.formPriceQuantity', ['prices' => $prices, 'quantity' => $item->quantiesAndPrices])
            @endif
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        $(document).ready(function(){
            // loadOptionTourList();
        })

        function loadOptionTourList(idTour){
            // const idTour        = $('#tour_info_id').val();
            const idOption      = $('#tour_option_id').val();
            $.ajax({
                url         : '{{ route("admin.tourBooking.loadOptionTourList") }}',
                type        : 'GET',
                dataType    : 'json',
                data        : {tour_info_id : idTour, tour_option_id : idOption},
                success     : function(data){
                    $('#jd_loadOptionTourList_idWrite').html(data.content);
                    if(data.tour_option_id!='0') loadFormPriceQuantity(data.tour_option_id);
                }
            });
        }

        function loadFormPriceQuantity(idOption){
            const idBooking = $('#tour_booking_id').val();
            $.ajax({
                url         : '{{ route("admin.tourBooking.loadFormPriceQuantity") }}',
                type        : 'GET',
                dataType    : 'html',
                data        : {tour_booking_id : idBooking, tour_option_id : idOption},
                success     : function(data){
                    $('#js_loadFormPriceQuantity_idWrite').html(data);
                }
            });
        }
        // function loadProvinceByRegion(idRegion, idWrite){
        //     $.ajax({
        //         url         : "{{ route('admin.form.loadProvinceByRegion') }}",
        //         type        : "POST",
        //         dataType    : "html",
        //         data        : {
        //             _token      : "{{ csrf_token() }}",
        //             region_id   : idRegion
        //         }
        //     }).done(function(data){
        //         $('#'+idWrite).html(data);
        //     });
        // }

        // function loadDistrictByProvince(idProvince, idWrite){
        //     $.ajax({
        //         url         : "{{ route('admin.form.loadDistrictByProvince') }}",
        //         type        : "POST",
        //         dataType    : "html",
        //         data        : {
        //             _token          : "{{ csrf_token() }}",
        //             province_id     : idProvince
        //         }
        //     }).done(function(data){
        //         $('#'+idWrite).html(data);
        //     });
        // }

    </script>

@endpush