<input type="hidden" id="ship_price_id" name="ship_price_id" value="{{ !empty($item->id)&&$type=='update' ? $item->id : null }}" />
<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="ship_partner_id">Đối tác Tàu</label>
            <select class="form-select" id="ship_partner_id" name="ship_partner_id" aria-hidden="true">
                <option value="0">- Lựa chọn -</option>
                @if(!empty($partners))
                    @foreach($partners as $partner)
                        @php
                            $selected   = null;
                            if(!empty($item->partner->id)&&$partner->id==$item->partner->id) $selected   = 'selected';
                        @endphp
                        <option value="{{ $partner->id }}" {{ $selected }}>{{ $partner->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="date_range">Khoảng ngày</label>
            @php
                $dateRange  = null;
                if(!empty($item->date_start)&&!empty($item->date_end)) $dateRange = date('Y-m-d', strtotime($item->date_start)).' to '.date('Y-m-d', strtotime($item->date_end))
            @endphp
            <input type="text" class="form-control flatpickr-disabled-range flatpickr-input active" id="date_range" name="date_range" value="{{ $dateRange }}" placeholder="YYYY-MM-DD" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="price_adult">Giá người lớn</label>
                    <input type="number" class="form-control" id="price_adult" name="price_adult" value="{{ old('price_adult') ?? $item->price_adult ?? null }}" required>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="price_child">Giá TE 6-11 tuổi</label>
                    <input type="text" class="form-control" id="price_child" name="price_child" value="{{ old('price_child') ?? $item->price_child ?? null }}" required>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="price_old">Giá cao tuổi</label>
                    <input type="number" class="form-control" id="price_old" name="price_old" value="{{ old('price_old') ?? $item->price_old ?? null }}" required>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="price_vip">Giá vé VIP</label>
                    <input type="text" class="form-control" id="price_vip" name="price_vip" value="{{ old('price_vip') ?? $item->price_vip ?? null }}" required>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="profit_percent">Phần trăm hoa hồng</label>
            <input type="text" class="form-control" id="profit_percent" name="profit_percent" value="{{ $item->profit_percent ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" data-repeater-list="time">
        @if(!empty($item->times))
            @foreach($item->times as $time)
                <div class="flexBox" {{ $loop->last ? 'data-repeater-item' : null}}>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired" for="string_from_to">Khởi hành - Cập bến</label>
                        <select class="form-select" name="string_from_to" aria-hidden="true">
                            <option value="0">- Lựa chọn -</option>
                            @if(!empty($shipInfo->location))
                                @php
                                    $locationName   = $shipInfo->location->district->district_name ?? $shipInfo->location->province->province_name;
                                    $departureName  = $shipInfo->departure->district->district_name ?? $shipInfo->departure->province->province_name;
                                    $nameTrip       = $departureName.' - '.$locationName;
                                    $nameRound      = $locationName.' - '.$departureName;
                                @endphp
                                <option value="{{ $nameTrip }}" {{ ($nameTrip===$time->name) ? 'selected' : null }}>{{ $nameTrip }}</option>
                                <option value="{{ $nameRound }}" {{ ($nameRound===$time->name) ? 'selected' : null }}>{{ $nameRound }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired" for="time_departure">Thời gian khởi hành</label>
                        <input type="text" class="form-control" name="time_departure" value="{{ $time->time_departure ?? null }}" required>
                    </div>
                    <div class="flexBox_item">
                        <label class="form-label inputRequired" for="time_arrive">Thời gian đến</label>
                        <input type="text" class="form-control" name="time_arrive" value="{{ $time->time_arrive ?? null }}" required>
                    </div>
                    <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
            @endforeach
        @else
            <div class="flexBox" data-repeater-item>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="string_from_to">Khởi hành - Cập bến</label>
                    <select class="form-select" name="string_from_to" aria-hidden="true">
                        <option value="0">- Lựa chọn -</option>
                            @if(!empty($shipInfo->location))
                                @php
                                    $locationName   = $shipInfo->location->district->district_name ?? $shipInfo->location->province->province_name;
                                    $departureName  = $shipInfo->departure->district->district_name ?? $shipInfo->departure->province->province_name;
                                    $nameTrip       = $departureName.' - '.$locationName;
                                    $nameRound      = $locationName.' - '.$departureName;
                                @endphp
                                <option value="{{ $nameTrip }}">{{ $nameTrip }}</option>
                                <option value="{{ $nameRound }}">{{ $nameRound }}</option>
                            @endif
                    </select>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="time_departure">Thời gian khởi hành</label>
                    <input type="text" class="form-control" name="time_departure" value="" required>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="time_arrive">Thời gian đến</label>
                    <input type="text" class="form-control" name="time_arrive" value="" required>
                </div>
                <div class="flexBox_item btnRemoveRepeater" data-repeater-delete>
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
        @endif
        </div>
        <!-- One Row -->
        <div class="formBox_full_item" style="text-align:right;">
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.flatpickr-input').flatpickr({
      mode: 'range'
    });
    $('.formBox_full').repeater();
</script>