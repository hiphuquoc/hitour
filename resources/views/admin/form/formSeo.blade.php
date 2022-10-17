<input type="hidden" name="seo_id" value="{{ $item->seo->id ?? null }}" />

<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <span data-toggle="tooltip" data-placement="top" title="
                    Đây là Tiêu đề được hiển thị ngoài Google... Tốt nhất nên từ 55- 60 ký tự, có chứa từ khóa chính tranh top và thu hút người truy cập click
                ">
                    <i class="explainInput" data-feather='alert-circle'></i>
                    <label class="form-label inputRequired" for="seo_title">Tiêu đề SEO</label>
                </span>
                <div class="inputWithNumberChacractor_count" data-charactor="seo_title">
                    {{ !empty($item->seo->seo_title) ? mb_strlen($item->seo->seo_title) : 0 }}
                </div>
            </div>
            <input type="text" id="seo_title" class="form-control" name="seo_title" value="{{ old('seo_title') ?? $item->seo['seo_title'] ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <div class="inputWithNumberChacractor">
                <span class="inputWithNumberChacractor_label" data-toggle="tooltip" data-placement="top" title="
                    Đây là Mô tả được hiển thị ngoài Google... Tốt nhất nên từ 140 - 160 ký tự, có chứa từ khóa chính tranh top và mô tả được cái người dùng đang cần
                ">
                    <i class="explainInput" data-feather='alert-circle'></i>
                    <label class="form-label inputRequired" for="seo_description">Mô tả SEO</label>
                </span>
                <div class="inputWithNumberChacractor_count" data-charactor="seo_description">
                    {{ !empty($item->seo->seo_description) ? mb_strlen($item->seo->seo_description) : 0 }}
                </div>
            </div>
            <textarea class="form-control" id="seo_description"  name="seo_description" rows="5" required>{{ old('seo_description') ?? $item->seo['seo_description'] ?? '' }}</textarea>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Đây là URL để người dùng truy cập... viết liền không dấu và ngăn cách nhau bởi dấu gạch (-)... nên chứa từ khóa SEO chính và ngắn gọn
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label inputRequired" for="slug">Đường dẫn tĩnh</label>
            </span>
            <input type="text" id="slug" class="form-control" name="slug" value="{{ old('slug') ?? $item->seo['slug'] ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Đây là thẻ khai báo Link Canonical để chuyển giá trị trang trùng lặp nội dung về trang chính. Bỏ trống tức trang chính là trang này
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label" for="link_canonical">Link Canonical</label>
            </span>
            <div class="input-group input-group-merge">
                <span class="input-group-text" style="background:#efefef;">{{ env('APP_URL') }}/</span>
                <input type="text" name="link_canonical" class="form-control" value="{{ old('link_canonical') ?? $item->seo->link_canonical ?? null }}" style="padding-left:1rem;" />
            </div>
        </div>
        <!-- One Row -->
        @if(!empty($parents))
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Là trang cha chứa trang hiện tại... URL cũng sẽ được hiển thị theo cấp cha - con
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label" for="parent">Trang cha</label>
            </span>
            <select class="select2 form-select select2-hidden-accessible" id="parent" name="parent">
                <option value="0">- Lựa chọn -</option>
                @foreach($parents as $page)
                    @php
                        $selected   = null;
                        if(!empty($item->seo->parent)&&$item->seo->parent==$page->seo->id) $selected = 'selected';
                    @endphp
                    <option value="{{ $page->seo->id }}" {{ $selected }}>{{ $page->seo->title }}</option>
                @endforeach
            </select>
        </div>
        @endif
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
            <div class="flexBox">
                <div class="flexBox_item">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Đây là Số lượt đánh giá này được hiển thị trên trang website và ngoài Google để thể hiện sự uy tín (tự nhập tùy thích)
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label inputRequired" for="rating_aggregate_count">Lượt đánh giá</label>
                    </span>
                    <input type="number" id="rating_aggregate_count" class="form-control" name="rating_aggregate_count" value="{{ old('rating_aggregate_count') ?? $item->seo['rating_aggregate_count'] ?? '' }}" required>
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
                <div class="flexBox_item" style="margin-left:1rem;">
                    <span data-toggle="tooltip" data-placement="top" title="
                        Đây là Điểm đánh giá tương ứng này được hiển thị trên trang website và ngoài Google để thể hiện sự uy tín (tự nhập tùy thích)
                    ">
                        <i class="explainInput" data-feather='alert-circle'></i>
                        <label class="form-label inputRequired" for="rating_aggregate_star">Điểm đánh giá</label>
                    </span>
                    <input type="text" id="rating_aggregate_star" class="form-control" name="rating_aggregate_star" value="{{ old('rating_aggregate_star') ?? $item->seo['rating_aggregate_star'] ?? '' }}" required>
                    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
                </div>
            </div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Đây là Mã Topic dùng đánh dấu Topic Clusther - Content Hub (sẽ cập nhật tính năng SEO này sau)
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label" for="topic">Mã Topic</label>
            </span>
            <input type="text" id="topic" class="form-control" name="topic" disabled>
        </div>
    </div>
</div>