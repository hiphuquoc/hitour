<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="date">Ngày khởi hành</label>
            <input type="text" class="form-control flatpickr-basic flatpickr-input active" id="date" name="date" placeholder="YYYY-MM-DD" value="{{ !empty($item->date_from) ? date('Y-m-d', strtotime($item->date_from)) : null }}" readonly="readonly">
        </div>
        <!-- Xử lý cho booking Tour -->
        @if(!empty($item->tour))
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="tour_info_id">Tour</label>
                <select class="select2 form-select select2-hidden-accessible" id="tour_info_id" name="tour_info_id" onChange="loadOptionTour();">
                    <option value="0">- Lựa chọn -</option>
                    @foreach($tourList as $tour)
                        @php
                            $idKey      = old('tour_info_id') ?? $item->reference_id;
                            $selected   = $idKey==$tour->id ? 'selected' : null;
                        @endphp
                        <option value="{{ $tour->id }}" {{ $selected }}>{{ $tour->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired">Option Tour</label>
                <div id="js_loadOptionTour_idWrite">
                    <!-- load Ajax -->
                    @php
                        $options = \App\Http\Controllers\TourBookingController::getTourOptionByDate($item->date_from, $item->tour->options->toArray());
                    @endphp
                    @include('admin.booking.optionTour', [
                        'options'   => $options,
                        'active'    => $item->quantityAndPrice[0]->option_name
                    ])
                </div>
            </div>
        @endif
        <!-- Xử lý cho booking Service -->
        @if(!empty($item->service))
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="service_info_id">Dịch vụ</label>
                <select class="select2 form-select select2-hidden-accessible" id="service_info_id" name="service_info_id" onChange="loadOptionService();">
                    <option value="0">- Lựa chọn -</option>
                    @foreach($serviceList as $service)
                        @php
                            $idKey      = old('service_info_id') ?? $item->reference_id;
                            $selected   = $idKey==$service->id ? 'selected' : null;
                        @endphp
                        <option value="{{ $service->id }}" {{ $selected }}>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired">Option Vé</label>
                <div id="js_loadOptionService_idWrite">
                    <!-- load Ajax -->
                    @php
                        $options = \App\Http\Controllers\TourBookingController::getTourOptionByDate($item->date_from, $item->service->options->toArray());
                    @endphp
                    @include('admin.booking.optionService', [
                        'options'   => $options,
                        'active'    => $item->quantityAndPrice[0]->option_name
                    ])
                </div>
            </div>
        @endif
        <!-- Form Tour Option - Price - Quantity -->
        <div id="js_loadFormQuantityByOption_idWrite" class="formBox_full_item">
            <!-- load Ajax -->
            @php
                $prices    = [];
                /* trường hợp booking tour */
                if(!empty($item->tour->options)){
                    foreach($item->tour->options as $option) {
                        if($option->name==$item->quantityAndPrice[0]->option_name) {
                            $prices = $option->prices;
                            break;
                        }
                    }
                }
                /* trường hợp booking vé dịch vụ */
                if(!empty($item->service->options)){
                    foreach($item->service->options as $option) {
                        if($option->name==$item->quantityAndPrice[0]->option_name) {
                            $prices = $option->prices;
                            break;
                        }
                    }
                }
            @endphp
            @include('admin.booking.formQuantity', ['prices' => $prices, 'quantity' => $item->quantityAndPrice])
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function loadOptionTour(){
            const date                  = $(document).find('[name=date]').val();
            const idTourInfo            = $(document).find('[name=tour_info_id]').val();
            if(date!=''&&idTourInfo!=''){
                $.ajax({
                    url         : '{{ route("main.tourBooking.loadOptionTour") }}',
                    type        : 'get',
                    dataType    : 'json',
                    data        : {
                        tour_info_id            : idTourInfo,
                        date                    : date,
                        type                    : 'admin'
                    },
                    success     : function(data){
                        $('#js_loadOptionTour_idWrite').html(data.content);
                        loadFormQuantityByOption(data.tour_option_id_active);
                    }
                });
            }
        }

        function loadFormQuantityByOption(idOption){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("main.tourBooking.loadFormQuantityByOption") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    booking_info_id     : idBooking,
                    tour_option_id      : idOption,
                    type                : 'admin'
                },
                success     : function(data){
                    $('#js_loadFormQuantityByOption_idWrite').html(data);
                }
            });
        }

        function loadOptionService(){
            const date                  = $(document).find('[name=date]').val();
            const idServiceInfo         = $(document).find('[name=service_info_id]').val();
            if(date!=''&&idServiceInfo!=''){
                $.ajax({
                    url         : '{{ route("main.serviceBooking.loadOption") }}',
                    type        : 'get',
                    dataType    : 'json',
                    data        : {
                        service_info_id         : idServiceInfo,
                        date                    : date,
                        type                    : 'admin'
                    },
                    success     : function(data){
                        $('#js_loadOptionService_idWrite').html(data.content);
                        loadFormQuantityByOptionService(data.service_option_id_active);
                    }
                });
            }
        }

        function loadFormQuantityByOptionService(idOption){
            const idBooking     = $('#booking_info_id').val();
            $.ajax({
                url         : '{{ route("main.serviceBooking.loadFormQuantityByOption") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    booking_info_id     : idBooking,
                    service_option_id   : idOption,
                    type                : 'admin'
                },
                success     : function(data){
                    $('#js_loadFormQuantityByOption_idWrite').html(data);
                }
            });
        }

    </script>

@endpush