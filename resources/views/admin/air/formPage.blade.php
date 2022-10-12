<input type="hidden" name="air_info_id" value="{{ !empty($item->id)&&$type=='edit' ? $item->id : null }}" />

<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <span data-toggle="tooltip" data-placement="top" title="
                    Đây là Tiêu đề của Chuyến tàu được hiển thị trên website
                ">
                    <i class="explainInput" data-feather='alert-circle'></i>
                    <label class="form-label inputRequired" for="title">Tiêu đề Trang</label>
                </span>
                <div class="inputWithNumberChacractor_count" data-charactor="title">
                    {{ !empty($item->seo->title) ? mb_strlen($item->seo->title) : 0 }}
                </div>
            </div>
            <input type="text" class="form-control" name="title" value="{{ old('title') ?? $item->seo['title'] ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <span data-toggle="tooltip" data-placement="top" title="
                    Đây là Mô tả của Chuyến tàu được hiển thị trên website
                ">
                    <i class="explainInput" data-feather='alert-circle'></i>
                    <label class="form-label inputRequired" for="description">Mô tả Trang</label>
                </span>
                <div class="inputWithNumberChacractor_count" data-charactor="description">
                    {{ !empty($item->seo->description) ? mb_strlen($item->seo->description) : 0 }}
                </div>
            </div>
            <textarea class="form-control" name="description" rows="5" required>{{ old('description') ?? $item->seo['description'] ?? '' }}</textarea>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="air_departure_id">Điểm khởi hành</label>
                    <select class="select2 form-select select2-hidden-accessible" name="air_departure_id" onChange="loadSelectBoxAirPort(this.value, 'departure', 
                    'js_loadSelectBoxAirPort_idWrite_departure');">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($airDepartures))
                            @foreach($airDepartures as $departure)
                                @php
                                    $selected   = null;
                                    if(!empty($item->air_departure_id)&&$item->air_departure_id==$departure->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $departure['id'] }}"{{ $selected }}>{{ $departure['name'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="air_port_departure_id">Sân bay khởi hành</label>
                    <select id="js_loadSelectBoxAirPort_idWrite_departure" class="form-select" name="air_port_departure_id">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($airPortDepartures))
                            @foreach($airPortDepartures as $airDeparture)
                                @php
                                    $selected   = null;
                                    if(!empty($item->air_port_departure_id)&&$item->air_port_departure_id==$airDeparture->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $airDeparture->id }}"{{ $selected }}>{{ $airDeparture->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="air_location_id">Điểm đến</label>
                    <select class="select2 form-select select2-hidden-accessible" name="air_location_id" onChange="loadSelectBoxAirPort(this.value, 'location', 
                    'js_loadSelectBoxAirPort_idWrite_location');">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($airLocations))
                            @foreach($airLocations as $location)
                                @php
                                    $selected   = null;
                                    if(!empty($item->air_location_id)&&$item->air_location_id==$location->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $location['id'] }}"{{ $selected }}>{{ $location['name'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="air_port_location_id">Sân bay đáp</label>
                    <select id="js_loadSelectBoxAirPort_idWrite_location" class="form-select" name="air_port_location_id">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($airPortLocations))
                            @foreach($airPortLocations as $airLocation)
                                @php
                                    $selected   = null;
                                    if(!empty($item->air_port_location_id)&&$item->air_port_location_id==$airLocation->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $airLocation->id }}"{{ $selected }}>{{ $airLocation->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="staff">Nhân viên tư vấn</label>
            <select class="select2 form-select select2-hidden-accessible" name="staff[]" multiple="true">
                <option value="0">- Lựa chọn -</option>
                @if(!empty($staffs))
                    @foreach($staffs as $staff)
                        @php
                            $selected   = null;
                            if(!empty($item->staffs)){
                                foreach($item->staffs as $s) {
                                    if($staff['id']==$s['staff_info_id']) {
                                        $selected = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $staff['id'] }}"{{ $selected }}>{{ $staff['firstname'] }} {{ $staff['lastname'] }} ({{ $staff['prefix_name'] }}. {{ $staff['lastname'] }})</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="partner">Đối tác</label>
            <select class="select2 form-select select2-hidden-accessible" name="partner[]" multiple="true">
                @if(!empty($airPartners))
                    @foreach($airPartners as $partner)
                        @php
                            $selected   = null;
                            if(!empty($item->partners)){
                                foreach($item->partners as $p) {
                                    if($partner['id']==$p['partner_info_id']) {
                                        $selected = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $partner['id'] }}"{{ $selected }}>{{ $partner['company_name'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function loadSelectBoxAirPort(value, type, idWrite){
            $.ajax({
                url         : "{{ route('admin.airPort.loadSelectBoxAirPort') }}",
                type        : "GET",
                dataType    : "html",
                data        : {
                    id      : value,
                    type
                }
            }).done(function(data){
                $('#'+idWrite).html(data);
            });
        }
    </script>
@endpush