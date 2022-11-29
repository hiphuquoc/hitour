{{-- <div>Hệ thống đặt Vé vui chơi đang bảo trì! Để được hỗ trợ nhanh chóng Quý khách vui lòng liên hệ <span style="font-size:1.2rem;color:rgb(0,123,255);font-weight:700;">08 6868 6868</span>.</div> --}}

@php
    $dataServiceLocation 		= \App\Models\ServiceLocation::select('*')
                                    ->whereHas('services', function(){

                                    })
                                    ->get();
@endphp

<div class="bookFormSortService">
    <div class="bookFormSortService_input">
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithIcon location">
                @php
                    /* xác định service_info_id active (trong trang service_info) */
                    $idServiceInfo      = 0;
                    if(!empty($item->seo->type)&&$item->seo->type=='service_info') $idServiceInfo  = $item->id;
                @endphp
                <label for="service_location_id">Điểm đến</label>
                <select class="select2 form-select select2-hidden-accessible" id="service_location_id" name="service_location_id" onchange="loadServiceByLocation('js_loadServiceByLocation_idWrite');" tabindex="-1" aria-hidden="true">
                    @foreach($dataServiceLocation as $serviceLocation)
                        @php
                            $selected	= null;
                            /* kiểm tra cho trang service_location */
                            if(!empty($item->id)&&$item->id==$serviceLocation->id&&$item->seo->type=='service_location') $selected = 'selected';
                            /* kiểm tra cho trang service_info */
                            if(!empty($item->serviceLocation->id)&&$item->serviceLocation->id==$serviceLocation->id) $selected = 'selected';
                        @endphp
                        <option value="{{ $serviceLocation->id }}" {{ $selected }}>{{ $serviceLocation->display_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div>
                <label for="service_info_id">Chọn dịch vụ</label>
                <select id="js_loadServiceByLocation_idWrite" class="select2 form-select select2-hidden-accessible" name="service_info_id" tabindex="-1" aria-hidden="true">
                </select>
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithIcon date">
                <label for="bookFormSort_date">Ngày khởi hành</label>
                <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" value="{{ date('Y-m-d', time() + 86400) }}" aria-label="Ngày đi tàu cao tốc" readonly="readonly" required>
            </div>
        </div>
    </div>
    <div class="bookFormSortService_button">
        <div class="buttonSecondary" onClick="submitForm('serviceBookingSort');" style="padding-top:0.5rem !important;padding-bottom:0.5rem !important;">
            <i class="fa-solid fa-check"></i>Đặt vé ngay
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        $(window).ready(function(){
            loadServiceByLocation('js_loadServiceByLocation_idWrite', '{{ $idServiceInfo }}');
        })

        function loadServiceByLocation(idWrite, idServiceInfo = 0){
            const idServiceLocation = $('#service_location_id').val();
            $.ajax({
                url         : '{{ route("main.serviceBooking.loadService") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    service_location_id : idServiceLocation,
					service_info_id     : idServiceInfo
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }
    </script>
@endpush