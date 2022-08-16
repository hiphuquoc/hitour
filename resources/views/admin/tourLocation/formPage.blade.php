<input type="hidden" name="tour_location_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : null }}" />

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
            <label class="form-label" for="region">Vùng miền</label>
            <select class="select2 form-select select2-hidden-accessible" id="region" name="region" onChange="javascript:loadProvinceByRegion(this.value, 'province');">
                <option value="0">- Lựa chọn -</option>
                @foreach(config('admin.region') as $region)
                    @php
                        $selected   = null;
                        if(!empty($item->region_id)&&$item->region_id==$region['id']) $selected = ' selected';
                    @endphp
                    <option value="{{ $region['id'] }}"{{ $selected }}>{{ $region['name'] }}</option>
                @endforeach
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="province">Tỉnh</label>
            <select class="select2 form-select select2-hidden-accessible" id="province" name="province" onChange="javascript:loadDistrictByProvince(this.value, 'district');">
                <option value="0">- Lựa chọn -</option>
                @if(!empty($provinces))
                    @foreach($provinces as $province)
                        @php
                            $selected   = null;
                            if(!empty($item->province_id)&&$item->province_id==$province['id']) $selected = ' selected';
                        @endphp
                        <option value="{{ $province['id'] }}"{{ $selected }}>{{ $province['name'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="district">Thành phố /Quận</label>
            <select class="select2 form-select select2-hidden-accessible" id="district" name="district">
                <option value="0">- Lựa chọn -</option>
                @if(!empty($districts))
                    @foreach($districts as $district)
                        @php
                            $selected   = null;
                            if(!empty($item->district_id)&&$item->district_id==$district['id']) $selected = ' selected';
                        @endphp
                        <option value="{{ $district['id'] }}"{{ $selected }}>{{ $district['name'] }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="form-check form-check-success">
                <input type="checkbox" class="form-check-input" id="island" name="island" {{ !empty($item->island)&&($item->island==1) ? 'checked' : null }}>
                <label class="form-check-label" for="island">Khu vực này được tính là biển đảo</label>
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