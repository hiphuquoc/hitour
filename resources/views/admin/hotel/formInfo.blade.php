<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <label class="form-label" for="company_name">Tên công ty</label>
            </div>
            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') ?? $item['company_name'] ?? '' }}" />
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <label class="form-label" for="address">Địa chỉ</label>
            </div>
            <input type="text" class="form-control" id="address" name="address" value="{{ old('address') ?? $item['address'] ?? '' }}" />
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <!-- One Row -->
                <div class="flexBox_item">
                    <label class="form-label inputRequired" for="type_name">Loại</label>
                    <select class="select2 form-select select2-hidden-accessible" id="type_name" name="type_name">
                        @foreach(config('main.hotel_type') as $typeName)
                            @php
                                $selected   = null;
                                if(!empty($item['type_name'])&&strtolower($item['type_name'])==strtolower($typeName)) $selected = ' selected';
                            @endphp
                            <option value="{{ strtolower($typeName) }}"{{ $selected }}>{{ strtolower($typeName) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flexBox_item">
                    <label class="form-label" for="type_rating">Loại sao</label>
                    <input type="number" min="1" max="5" class="form-control" id="type_rating" name="type_rating" value="{{ old('type_rating') ?? $item['type_rating'] ?? '' }}" />
                </div>
            </div>
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <!-- One Row -->
                <div class="flexBox_item">
                    <label class="form-label" for="company_code">Mã số thuế</label>
                    <input type="text" class="form-control" id="company_code" name="company_code" value="{{ old('company_code') ?? $item['company_code'] ?? '' }}" />
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
                <div class="flexBox_item">
                    <label class="form-label" for="website">Website</label>
                    <input type="text" class="form-control" id="website" name="website" value="{{ old('website') ?? $item['website'] ?? '' }}" />
                </div>
            </div>
            
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="flexBox">
                <!-- One Row -->
                <div class="flexBox_item">
                    <label class="form-label" for="hotline">Hotline</label>
                    <input type="text" class="form-control" id="hotline" name="hotline" value="{{ old('hotline') ?? $item['hotline'] ?? '' }}" />
                </div>
                <div class="flexBox_item">
                    <label class="form-label" for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ?? $item['email'] ?? '' }}" />
                </div>
            </div>
            
        </div>
    </div>
</div>