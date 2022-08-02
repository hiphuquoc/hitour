<div class="card-header border-bottom">
    <h4 class="card-title">Thông tin Nhân viên</h4>
</div>
<div class="card-body">

    @if(!empty($item->id))
        <input type="hidden" name="tourLocation_id" value="{{ $item->id }}" />
    @endif
    @if(!empty($item->seo->id))
        <input type="hidden" name="seo_id" value="{{ $item->seo->id }}" />
    @endif

    <div class="formBox">
        <div class="formBox_full">
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label inputRequired" for="prefix_name">Danh xưng</label>
                </div>
                <select class="form-select" id="prefix_name" name="prefix_name" aria-hidden="true">
                    <option value="0" data-select2-id="61">- Lựa chọn -</option>
                    @foreach(config('admin.prefix_name') as $prefix)
                        @php
                            $selected   = null;
                            if(!empty($item->prefix_name)&&$item->prefix_name===$prefix) $selected = ' selected';
                        @endphp
                        <option value="{{ $prefix }}"{{ $selected }}>{{ $prefix }}</option>
                    @endforeach
                </select>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label inputRequired" for="firstname">Họ & Lót</label>
                </div>
                <input type="text" class="form-control" id="firstname" name="firstname" value="{{ old('firstname') ?? $item->firstname ?? '' }}" required>
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <div class="inputWithNumberChacractor">
                    <label class="form-label inputRequired" for="lastname">Tên</label>
                </div>
                <input type="text" class="form-control" id="lastname" name="lastname" value="{{ old('lastname') ?? $item->lastname ?? '' }}" required>
                <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') ?? $item->phone ?? '' }}" required>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="zalo">Zalo</label>
                <input type="text" class="form-control" id="zalo" name="zalo" value="{{ old('zalo') ?? $item->zalo ?? '' }}" required>
            </div>
            <!-- One Row -->
            <div class="formBox_full_item">
                <label class="form-label inputRequired" for="email">email</label>
                <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ?? $item->email ?? '' }}" required>
            </div>
        </div>
    </div>
    
</div>

@push('scripts-custom')
    <script type="text/javascript">


    </script>
@endpush