<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="name">Tên Blog</label>
            <input type="text" class="form-control" name="name" value="{{ $item['name'] ?? null }}" />
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="url">URL Blog</label>
            <input type="text" class="form-control" name="url" value="{{ $item['url'] ?? null }}" />
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="email">Email quản lí</label>
            <input type="text" class="form-control" name="email" value="{{ $item['email'] ?? null }}" />
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="password">Mật khẩu email</label>
            <input type="text" class="form-control" name="password" value="{{ $item['password'] ?? null }}" />
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="phone">SDT khôi phục</label>
            <input type="text" class="form-control" name="phone" value="{{ $item['phone'] ?? null }}" />
        </div>
    </div>
</div>