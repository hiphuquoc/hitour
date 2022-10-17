<input type="hidden" name="service_info_id" value="{{ !empty($item->id)&&$type=='edit' ? $item->id : null }}" />

<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <span data-toggle="tooltip" data-placement="top" title="
                    Đây là Tiêu đề của Chuyên mục được hiển thị trên website
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
                    Đây là Mô tả của Chuyên mục được hiển thị trên website
                ">
                    <i class="explainInput" data-feather='alert-circle'></i>
                    <label class="form-label inputRequired" for="description">Mô tả đặc biệt</label>
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
            <label class="form-label inputRequired" for="code">Mã dịch vụ</label>
            <input type="text" class="form-control" id="code" name="code" value="{{ old('code') ?? $item->code ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="service_location_id">Thuộc khu vực</label>
            <select class="select2 form-select select2-hidden-accessible" id="service_location_id" name="service_location_id">
                <option value="">- Lựa chọn -</option>
                @if(!empty($serviceLocations))
                    @foreach($serviceLocations as $location)
                        @php
                            $selected   = null;
                            if(!empty($item->serviceLocation->id)&&$item->serviceLocation->id==$location['id']) $selected = ' selected';
                        @endphp
                        <option value="{{ $location['id'] }}"{{ $selected }}>{{ $location['name'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="staff">Nhân viên tư vấn</label>
            <select class="select2 form-select select2-hidden-accessible" id="staff" name="staff[]" multiple>
                <option value="">- Lựa chọn -</option>
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
            <div class="flexBox">
                <div class="flexBox_item">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Dùng cho Tour trong ngày
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label" for="time_start">Thời gian bắt đầu</label>
                    </span>
                    <input type="text" id="time_start" name="time_start" class="form-control flatpickr-time text-start flatpickr-input active" placeholder="HH:MM" value="{{ old('time_start') ?? $item->time_start ?? null }}" readonly="readonly">
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Dùng cho Tour trong ngày
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label" for="time_end">Thời gian kết thúc</label>
                    </span>
                    <input type="text" id="time_end" name="time_end" class="form-control flatpickr-time text-start flatpickr-input active" placeholder="HH:MM" value="{{ old('time_end') ?? $item->time_end ?? null }}" readonly="readonly">
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <div class="flexBox_item">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Giá này sẽ được hiển thị đại diện cho chương trình Tour
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label inputRequired" for="price_show">Giá đại diện</label>
                    </span>
                    <input type="number" min="0" id="price_show" class="form-control" name="price_show" value="{{ old('price_show') ?? $item->price_show ?? null }}" placeholder="0" required>
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Giá này là giá cũ được hiển thị kiểu gạch bỏ
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label" for="price_del">Giá cũ</label>
                    </span>
                    <input type="number" min="0" id="price_del" class="form-control" name="price_del" value="{{ old('price_del') ?? $item->price_del ?? null }}" placeholder="0">
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">
        function loadProvinceByRegion(idRegion, idWrite){
            $.ajax({
                url         : "{{ route('admin.form.loadProvinceByRegion') }}",
                type        : "POST",
                dataType    : "html",
                data        : {
                    _token      : "{{ csrf_token() }}",
                    region_id   : idRegion
                }
            }).done(function(data){
                $('#'+idWrite).html(data);
            });
        }

        function loadDistrictByProvince(idProvince, idWrite){
            $.ajax({
                url         : "{{ route('admin.form.loadDistrictByProvince') }}",
                type        : "POST",
                dataType    : "html",
                data        : {
                    _token          : "{{ csrf_token() }}",
                    province_id     : idProvince
                }
            }).done(function(data){
                $('#'+idWrite).html(data);
            });
        }

    </script>

@endpush