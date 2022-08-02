@if(!empty($item->id))
    <input type="hidden" name="customer_info_id" value="{{ $item->customer_contact->id ?? null }}" />
@endif

<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="prefix_name">Danh xưng</label>
            <select class="form-select" id="prefix_name" name="prefix_name">
                <option value="0">- Lựa chọn -</option>
                @foreach(config('admin.prefix_name_customer') as $prefix)
                    @php
                        if(!empty(old('prefix_name'))){
                            $selected   = old('prefix_name')===$prefix ? 'selected' : null;
                        }else {
                            $selected   = !empty($item->customer_contact->prefix_name)&&$item->customer_contact->prefix_name===$prefix ? 'selected' : null;
                        }
                    @endphp
                    <option value="{{ $prefix }}" {{ $selected }}>{{ $prefix }}</option>
                @endforeach
            </select>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="name">Họ tên</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') ?? $item->customer_contact->name ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="phone">Điện thoại</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') ?? $item->customer_contact->phone ?? null }}" required>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="zalo">Zalo</label>
            <input type="text" class="form-control" id="zalo" name="zalo" value="{{ old('zalo') ?? $item->customer_contact->zalo ?? null }}">
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" value="{{ old('email') ?? $item->customer_contact->email ?? null }}">
        </div>
    </div>
</div>