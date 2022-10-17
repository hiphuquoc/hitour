<input type="hidden" name="ship_info_id" value="{{ !empty($item->id)&&$type=='edit' ? $item->id : null }}" />

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
                    <label class="form-label inputRequired" for="ship_departure_id">Điểm khởi hành</label>
                    <select class="select2 form-select select2-hidden-accessible" name="ship_departure_id" onChange="loadSelectBoxShipPort(this.value, 'departure', 
                    'js_loadSelectBoxShipPort_idWrite_departure');">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($shipDepartures))
                            @foreach($shipDepartures as $departure)
                                @php
                                    $selected   = null;
                                    if(!empty($item->ship_departure_id)&&$item->ship_departure_id==$departure->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $departure['id'] }}"{{ $selected }}>{{ $departure['name'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="ship_port_departure_id">Cảng khởi hành</label>
                    <select id="js_loadSelectBoxShipPort_idWrite_departure" class="form-select" name="ship_port_departure_id">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($shipPortDepartures))
                            @foreach($shipPortDepartures as $shipDeparture)
                                @php
                                    $selected   = null;
                                    if(!empty($item->ship_port_departure_id)&&$item->ship_port_departure_id==$shipDeparture->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $shipDeparture->id }}"{{ $selected }}>{{ $shipDeparture->name }}</option>
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
                    <label class="form-label inputRequired" for="ship_location_id">Điểm đến Tàu</label>
                    <select class="select2 form-select select2-hidden-accessible" name="ship_location_id" onChange="loadSelectBoxShipPort(this.value, 'location', 
                    'js_loadSelectBoxShipPort_idWrite_location');">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($shipLocations))
                            @foreach($shipLocations as $location)
                                @php
                                    $selected   = null;
                                    if(!empty($item->ship_location_id)&&$item->ship_location_id==$location->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $location['id'] }}"{{ $selected }}>{{ $location['name'] }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="ship_port_location_id">Cảng cập bến</label>
                    <select id="js_loadSelectBoxShipPort_idWrite_location" class="form-select" name="ship_port_location_id">
                        <option value="0">- Lựa chọn -</option>
                        @if(!empty($shipPortLocations))
                            @foreach($shipPortLocations as $shipLocation)
                                @php
                                    $selected   = null;
                                    if(!empty($item->ship_port_location_id)&&$item->ship_port_location_id==$shipLocation->id) $selected = 'selected';
                                @endphp
                                <option value="{{ $shipLocation->id }}"{{ $selected }}>{{ $shipLocation->name }}</option>
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
                                    if(!empty($s['staff_info_id'])&&$s['staff_info_id']==$staff['id']) {
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
            <label class="form-label inputRequired" for="partner">Đối tác Tàu</label>
            <select class="select2 form-select select2-hidden-accessible" name="partner[]" multiple="true">
                @if(!empty($shipPartners))
                    @foreach($shipPartners as $partner)
                        @php
                            $selected   = null;
                            if(!empty($item->partners)){
                                foreach($item->partners as $p) {
                                    if(!empty($p['partner_info_id'])&&$p['partner_info_id']==$partner['id']) {
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
        function loadSelectBoxShipPort(value, type, idWrite){
            $.ajax({
                url         : "{{ route('admin.shipPort.loadSelectBoxShipPort') }}",
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