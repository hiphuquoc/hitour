<!-- Input hidden -->
<input type="hidden" id="combo_option_id" name="combo_option_id" value="{{ $option['combo_option_id'] ?? null }}" />
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="departure">Điểm khởi hành</label>
            <select class="select2 form-select select2-hidden-accessible" id="departure_id" name="departure_id">
                @if(!empty($departures))
                    @foreach($departures as $departure)
                        @php
                            $selected       = null;
                            $idDeparture    = 0;
                            if(!empty($option['date_apply'])){
                                foreach($option['date_apply'] as $o){
                                    if(!empty($o[0]['departure_id'])) {
                                        $idDeparture = $o[0]['departure_id'];
                                        break;
                                    }
                                }
                            }
                            if(!empty($idDeparture)&&$idDeparture==$departure->id) $selected = 'selected';
                        @endphp
                        <option value="{{ $departure['id'] }}"{{ $selected }}>{{ $departure['name'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="combo_option">Option</label>
            <input type="text" class="form-control" id="combo_option" name="name" value="{{ $option['name'] ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="days">Số ngày</label>
                    <input type="number" min="0" id="days" class="form-control" name="days" placeholder="0" value="{{ old('days') ?? $option['days'] ?? '' }}" required>
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <label class="form-label inputRequired" for="nights">Số đêm</label>
                    <input type="number" min="0" id="nights" class="form-control" name="nights" placeholder="0" value="{{ old('nights') ?? $option['nights'] ?? '' }}" required>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            @php
                $idHotelInfo                = '';
            @endphp 
            <label class="form-label" for="hotel_info_id">Khách sạn</label>
            <select class="select2 form-select select2-hidden-accessible" id="hotel_info_id" name="hotel_info_id" onChange="loadOptionHotelRoomByIdHotel('hotel_room_id');">
                <option value="0">- Vui lòng chọn -</option>
                @foreach($hotels as $hotel)
                    @php
                        $selected           = 0;
                        if($option['hotel_info_id']==$hotel->id) {
                            $selected       = 'selected';
                            $idHotelInfo    = $hotel->id;
                        }
                    @endphp
                    <option value="{{ $hotel->id }}" {{ $selected }}>{{ $hotel->name }}</option>
                @endforeach
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="hotel_room_id">Loại phòng</label>
            <select class="select2 form-select select2-hidden-accessible" id="hotel_room_id" name="hotel_room_id">
                <!-- load Ajax -->
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" data-repeater-list="repeater_date_range">
            @if(!empty($option['date_apply']))
                @foreach($option['date_apply'] as $dateApply)
                    @php
                        /* sẵn vòng lặp build giá -> do key bằng ngày nên ko lấy được */
                        if($loop->index==0){
                            $dataPrice              = [];
                            foreach($dateApply as $price) $dataPrice[] = $price;
                        }
                    @endphp
                    <div class="flexBox" data-repeater-item>
                        <div class="flexBox_item">
                            <label class="form-label inputRequired" for="date_range">Ngày áp dụng</label>
                            <input type="text" class="date_range form-control flatpickr-range flatpickr-input active" name="repeater_date_range[{{ $loop->index }}][date_range]" placeholder="YYYY-MM-DD đến YYYY-MM-DD" value="{{ date('Y-m-d', strtotime($dateApply[0]['date_start'])) }} to {{ date('Y-m-d', strtotime($dateApply[0]['date_end'])) }}" readonly="readonly">
                        </div>
                        <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flexBox" data-repeater-item>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired" for="date_range">Ngày áp dụng</label>
                        <input type="text" class="date_range form-control flatpickr-range flatpickr-input active" name="repeater_date_range[date_range]" placeholder="YYYY-MM-DD đến YYYY-MM-DD" readonly="readonly">
                    </div>
                    <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" style="text-align:right;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item" data-repeater-list="repeater">
            @if(!empty($dataPrice))
                @foreach($dataPrice as $price)
                    <div class="flexBox" data-repeater-item>
                        <div class="flexBox_item">
                            <label class="form-label inputRequired">Tuổi áp dụng</label>
                            <input type="text" class="form-control" name="repeater[{{ $loop->index }}][apply_age]" value="{{ $price['apply_age'] ?? 0 }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label inputRequired">Giá</label>
                            <input type="number" class="form-control" name="repeater[{{ $loop->index }}][price]" value="{{ $price['price'] ?? 0 }}" required>
                        </div>
                        <div class="flexBox_item" style="margin-left:1rem;">
                            <label class="form-label">Hoa hồng</label>
                            <input type="number" class="form-control" name="repeater[{{ $loop->index }}][profit]" value="{{ $price['profit'] ?? 0 }}">
                        </div>
                        <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="flexBox" data-repeater-item>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired">Tuổi áp dụng</label>
                        <input type="text" class="form-control" name="apply_age" value="" required>
                    </div>
                    <div class="flexBox_item" style="margin-left:1rem;">
                        <label class="form-label inputRequired">Giá</label>
                        <input type="text" class="form-control" name="price" value="" required>
                    </div>
                    <div class="flexBox_item" style="margin-left:1rem;">
                        <label class="form-label">Hoa hồng</label>
                        <input type="text" class="form-control" name="profit" value="">
                    </div>
                    <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endif
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" style="text-align:right;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</div>
<script src="{{ asset('sources/admin/app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('sources/admin/app-assets/js/scripts/forms/form-select2.min.js') }}"></script>
<script type="text/javascript">
    loadOptionHotelRoomByIdHotel('hotel_room_id', {{ $idHotelInfo }}, {{ $option['hotel_room_id'] ?? 0 }});

    $('.formBox_full').repeater();

    setInterval(() => {
        $(document).find('.date_range').each(function(){
            if($(this).hasClass('added')){
                /* đã addListener thì thôi */
            }else {
                $('.date_range').addClass('added').flatpickr({
                    mode: 'range'
                });
            }
        })
    }, 100);

    function loadOptionHotelRoomByIdHotel(idWrite, idHotelInfo = 0, idHotelRoom = 0){
        if(idHotelInfo=='0') {
            const idHotelInfo = $('#hotel_info_id').val();
        }
        $.ajax({
            url         : '{{ route("admin.hotel.loadOptionHotelRoomByIdHotel") }}',
            type        : 'post',
            dataType    : 'html',
            data        : {
                '_token'        : '{{ csrf_token() }}',
                hotel_info_id   : idHotelInfo,
                hotel_room_id   : idHotelRoom
            },
            success     : function(response){
                $('#'+idWrite).html(response);
            }
        });
    }
</script>