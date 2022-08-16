@if(!empty($item->id))
    <input type="hidden" name="tour_location_id" value="{{ $item->id }}" />
@endif

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
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') ?? $item->seo['title'] ?? '' }}" required>
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
            <textarea class="form-control" id="description"  name="description" rows="5" required>{{ old('description') ?? $item->seo['description'] ?? '' }}</textarea>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="ship_location_id">Điểm đến Tàu</label>
            <select class="select2 form-select select2-hidden-accessible" id="ship_location_id" name="ship_location_id">
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
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="ship_departure_id">Điểm khởi hành</label>
            <select class="select2 form-select select2-hidden-accessible" id="ship_departure_id" name="ship_departure_id">
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
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="staff">Nhân viên tư vấn</label>
            @php
                dd($item->staffs);
            @endphp
            <select class="select2 form-select select2-hidden-accessible" id="staff" name="staff[]" multiple="true">
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
            <label class="form-label inputRequired" for="partner">Đối tác Tàu</label>
            <select class="select2 form-select select2-hidden-accessible" id="partner" name="partner[]" multiple="true">
                @if(!empty($shipPartners))
                    @foreach($shipPartners as $partner)
                        @php
                            $selected   = null;
                            if(!empty($item->partners)){
                                foreach($item->partners as $p) {
                                    if($partner['id']==$p['ship_partner_id']) {
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
    {{-- <script type="text/javascript">
        

    </script> --}}

@endpush