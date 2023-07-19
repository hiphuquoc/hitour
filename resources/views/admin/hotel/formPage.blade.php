<input type="hidden" id="hotel_info_id" name="hotel_info_id" value="{{ !empty($item->id)&&$type!='copy' ? $item->id : null }}" />
<input type="hidden" id="url_crawler" name="url_crawler" value="{{ $item['url_crawler'] ?? null }}" />
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
                    {{ !empty($item['name']) ? mb_strlen($item['name']) : 0 }}
                </div>
            </div>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('name') ?? $item['name'] ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <span data-toggle="tooltip" data-placement="top" title="
                    Đây là Mô tả của Chuyên mục được hiển thị trên website
                ">
                    <i class="explainInput" data-feather='alert-circle'></i>
                    <label class="form-label" for="description">Mô tả</label>
                </span>
                <div class="inputWithNumberChacractor_count" data-charactor="description">
                    {{ !empty($item['description']) ? mb_strlen($item['description']) : 0 }}
                </div>
            </div>
            <textarea class="form-control" id="description"  name="description" rows="5">{{ old('description') ?? $item['description'] ?? '' }}</textarea>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Nhập vào một số để thể hiện độ ưu tiên khi hiển thị cùng các Category khác (Số càng nhỏ càng ưu tiên cao - Để trống tức là không ưu tiên)
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label" for="ordering">Thứ tự</label>
            </span>
            <input type="number" min="0" id="ordering" class="form-control" name="ordering" value="{{ old('ordering') ?? $item->seo['ordering'] ?? '' }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="staff">Nhân viên tư vấn</label>
            <select class="select2 form-select select2-hidden-accessible" id="staff" name="staff[]" multiple="true">
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