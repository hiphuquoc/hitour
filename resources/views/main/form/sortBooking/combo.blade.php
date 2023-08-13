<div>Hệ thống đặt Combo đang bảo trì! Để được hỗ trợ nhanh chóng Quý khách vui lòng liên hệ <span style="font-size:1.2rem;color:rgb(0,123,255);font-weight:700;">08 6868 6868</span>.</div>

{{-- @php
    $dataComboLocation 		= \App\Models\ComboLocation::select('*')
                                ->whereHas('combos', function(){

                                })
                                ->with('region')
                                ->get();
@endphp

<div class="bookFormSortService">
    <div class="bookFormSortService_input">
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithLabelInside location">
                @php
                    /* xác định combo_info_id active (trong trang combo_info) */
                    $idComboInfo      = 0;
                    if(!empty($item->seo->type)&&$item->seo->type=='combo_info') $idComboInfo  = $item->id;
                @endphp
                <label for="combo_location_id">Điểm đến</label>
                <select class="select2 form-select select2-hidden-accessible" id="combo_location_id" name="combo_location_id" onchange="loadComboByLocation('js_loadComboByLocation_idWrite');" tabindex="-1" aria-hidden="true">
                    @foreach($dataComboLocation as $comboLocation)
                        @php
                            $selected	= null;
                            /* kiểm tra cho trang combo_location */
                            if(!empty($item->id)&&$item->id==$comboLocation->id&&$item->seo->type=='combo_location') $selected = 'selected';
                            /* kiểm tra cho trang combo_info */
                            if(!empty($item->serviceLocation->id)&&$item->serviceLocation->id==$comboLocation->id) $selected = 'selected';
                        @endphp
                        <option value="{{ $comboLocation->id }}" {{ $selected }}>{{ $comboLocation->display_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithLabelInside">
                <label for="combo_info_id">Chọn dịch vụ</label>
                <select id="js_loadComboByLocation_idWrite" class="select2 form-select select2-hidden-accessible" name="combo_info_id" tabindex="-1" aria-hidden="true">
                </select>
            </div>
        </div>
        <!-- One column -->
        <div class="bookFormSortService_input_item">
            <div class="inputWithLabelInside date">
                <label for="bookFormSort_date">Ngày khởi hành</label>
                <input type="text" class="form-control flatpickr-basic flatpickr-input active" name="date" value="{{ date('Y-m-d', time() + 86400) }}" aria-label="Ngày đi tàu cao tốc" readonly="readonly" required>
            </div>
        </div>
    </div>
    <div class="bookFormSortService_button">
        <div class="buttonSecondary" onClick="submitForm('comboBookingSort');">
            <i class="fa-solid fa-check"></i>Đặt combo
        </div>
    </div>
</div>

@pushonce('scripts-custom')
    <script type="text/javascript">
        $(window).ready(function(){
            loadComboByLocation('js_loadComboByLocation_idWrite', '{{ $idComboInfo }}');
        })

        function loadComboByLocation(idWrite, idComboInfo = 0){
            const idComboLocation = $('#combo_location_id').val();
            $.ajax({
                url         : '{{ route("main.comboBooking.loadCombo") }}',
                type        : 'get',
                dataType    : 'html',
                data        : {
                    combo_location_id : idComboLocation,
					combo_info_id     : idComboInfo
                },
                success     : function(data){
                    $('#'+idWrite).html(data);
                }
            });
        }
    </script>
@endpushonce --}}