<input type="hidden" name="ship_partner_id" value="{{ $item->id ?? null }}" />
<div class="card-header border-bottom">
    <h4 class="card-title">Thông tin</h4>
</div>
<div class="card-body">
    <div class="formBox">
        <div class="formBox_full">
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label inputRequired" for="name">Tên hiển thị</label>
                </div>
                <input type="text" class="form-control" name="name" value="{{ old('name') ?? $item->name ?? '' }}" required>
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
                <div class="inputWithNumberChacractor">
                    <label class="form-label inputRequired" for="address">Địa chỉ</label>
                </div>
                <input type="text" class="form-control" name="address" value="{{ old('address') ?? $item->address ?? '' }}" required>
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
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