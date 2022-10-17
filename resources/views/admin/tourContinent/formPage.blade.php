<input type="hidden" name="tour_continent_id" value="{{ !empty($item->id)&&$type=='edit' ? $item->id : null }}" />

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
            <label class="form-label inputRequired" for="display_name">Tên hiển thị</label>
            <input type="text" class="form-control" id="display_name" name="display_name" value="{{ old('display_name') ?? $item->display_name ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="guide_info_id">Liên kết cẩm nang du lịch</label>
            <select class="select2 form-select select2-hidden-accessible" id="guide_info_id" name="guide_info_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($guides)&&$guides->isNotEmpty())
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
            <label class="form-label" for="service_location_id">Liên kết vé vui chơi giải trí</label>
            <select class="select2 form-select select2-hidden-accessible" id="service_location_id" name="service_location_id[]" tabindex="-1" aria-hidden="true" multiple>
                @if(!empty($serviceLocations)&&$serviceLocations->isNotEmpty())
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
    </div>
</div>

@push('scripts-custom')
    <script type="text/javascript">

        // function loadProvinceByRegion(idRegion, idWrite){
        //     $.ajax({
        //         url         : "{{ route('admin.form.loadProvinceByRegion') }}",
        //         type        : "POST",
        //         dataType    : "html",
        //         data        : {
        //             _token      : "{{ csrf_token() }}",
        //             region_id   : idRegion
        //         }
        //     }).done(function(data){
        //         $('#'+idWrite).html(data);
        //     });
        // }

        // function loadDistrictByProvince(idProvince, idWrite){
        //     $.ajax({
        //         url         : "{{ route('admin.form.loadDistrictByProvince') }}",
        //         type        : "POST",
        //         dataType    : "html",
        //         data        : {
        //             _token          : "{{ csrf_token() }}",
        //             province_id     : idProvince
        //         }
        //     }).done(function(data){
        //         $('#'+idWrite).html(data);
        //     });
        // }

    </script>

@endpush