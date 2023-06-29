<input type="hidden" name="tour_location_id" value="{{ !empty($item->id)&&$type=='edit' ? $item->id : null }}" />

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
            <label class="form-label inputRequired" for="display_name">Tên hiển thị</label>
            <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name') ?? $item->display_name ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="form-check form-check-success">
                <input type="checkbox" class="form-check-input" id="island" name="island" {{ !empty($item->island)&&($item->island==1) ? 'checked' : null }}>
                <label class="form-check-label" for="island">Đây là biển đảo</label>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="form-check form-check-success">
                <input type="checkbox" class="form-check-input" id="special" name="special" {{ !empty($item->special)&&($item->special==1) ? 'checked' : null }}>
                <label class="form-check-label" for="special">Đây là điểm đến nổi bật</label>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="combo_location_id">Liên kết combo</label>
            <select class="select2 form-select select2-hidden-accessible" id="combo_location_id" name="combo_location_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($comboLocations))
                    @foreach($comboLocations as $comboLocation)
                        @php
                            $selected           = null;
                            if(!empty($item->comboLocations)&&$item->comboLocations->isNotEmpty()){
                                foreach($item->comboLocations as $s){
                                    if(!empty($s->infoComboLocation->id)&&$s->infoComboLocation->id==$comboLocation->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $comboLocation->id }}"{{ $selected }}>{{ $comboLocation->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="guide_info_id">Liên kết cẩm nang du lịch</label>
            <select class="select2 form-select select2-hidden-accessible" id="guide_info_id" name="guide_info_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($guides))
                    @foreach($guides as $guide)
                        @php
                            $selected           = null;
                            if(!empty($item->guides)&&$item->guides->isNotEmpty()){
                                foreach($item->guides as $g){
                                    if(!empty($g->guide_info_id)&&$g->guide_info_id==$guide->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $guide->id }}"{{ $selected }}>{{ $guide->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="guide_info_id">Liên kết vé tàu</label>
            <select class="select2 form-select select2-hidden-accessible" id="ship_location_id" name="ship_location_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($shipLocations))
                    @foreach($shipLocations as $shipLocation)
                        @php
                            $selected           = null;
                            if(!empty($item->shipLocations)&&$item->shipLocations->isNotEmpty()){
                                foreach($item->shipLocations as $s){
                                    if(!empty($s->infoShipLocation->id)&&$s->infoShipLocation->id==$shipLocation->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $shipLocation->id }}"{{ $selected }}>{{ $shipLocation->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="service_location_id">Liên kết vé vui chơi giải trí</label>
            <select class="select2 form-select select2-hidden-accessible" id="service_location_id" name="service_location_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($serviceLocations))
                    @foreach($serviceLocations as $serviceLocation)
                        @php
                            $selected           = null;
                            if(!empty($item->serviceLocations)&&$item->serviceLocations->isNotEmpty()){
                                foreach($item->serviceLocations as $s){
                                    if(!empty($s->infoServiceLocation->id)&&$s->infoServiceLocation->id==$serviceLocation->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $serviceLocation->id }}"{{ $selected }}>{{ $serviceLocation->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="carrental_location_id">Liên kết Cho thuê xe</label>
            <select class="select2 form-select select2-hidden-accessible" id="carrental_location_id" name="carrental_location_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($carrentalLocations))
                    @foreach($carrentalLocations as $carrentalLocation)
                        @php
                            $selected           = null;
                            if(!empty($item->carrentalLocations)&&$item->carrentalLocations->isNotEmpty()){
                                foreach($item->carrentalLocations as $c){
                                    if(!empty($c->infoCarrentalLocation->id)&&$c->infoCarrentalLocation->id==$carrentalLocation->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $carrentalLocation->id }}"{{ $selected }}>{{ $carrentalLocation->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="air_location_id">Liên kết vé máy bay</label>
            <select class="select2 form-select select2-hidden-accessible" id="air_location_id" name="air_location_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($airLocations))
                    @foreach($airLocations as $airLocation)
                        @php
                            $selected           = null;
                            if(!empty($item->airLocations)&&$item->airLocations->isNotEmpty()){
                                foreach($item->airLocations as $a){
                                    if(!empty($a->infoAirLocation->id)&&$a->infoAirLocation->id==$airLocation->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $airLocation->id }}"{{ $selected }}>{{ $airLocation->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="destination_list_id">Liên kết điểm đến</label>
            <select class="select2 form-select select2-hidden-accessible" id="destination_list_id" name="destination_list_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($categories))
                    @foreach($categories as $category)
                        @php
                            $selected           = null;
                            if(!empty($item->destinations)&&$item->destinations->isNotEmpty()){
                                foreach($item->destinations as $a){
                                    if(!empty($a->infoCategory->id)&&$a->infoCategory->id==$category->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $category->id }}"{{ $selected }}>{{ $category->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
         <!-- One Row -->
         <div class="formBox_full_item">
            <label class="form-label" for="special_list_id">Liên kết đặc sản</label>
            <select class="select2 form-select select2-hidden-accessible" id="special_list_id" name="special_list_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($categories))
                    @foreach($categories as $category)
                        @php
                            $selected           = null;
                            if(!empty($item->specials)&&$item->specials->isNotEmpty()){
                                foreach($item->specials as $a){
                                    if(!empty($a->infoCategory->id)&&$a->infoCategory->id==$category->id){
                                        $selected   = ' selected';
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <option value="{{ $category->id }}"{{ $selected }}>{{ $category->name }}</option>
                    @endforeach
                @endif
            </select>
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