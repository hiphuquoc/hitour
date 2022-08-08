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
        <!-- One Row -->
        @if(!empty($allPage))
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Là trang cha chứa trang hiện tại... URL cũng sẽ được hiển thị theo cấp cha - con
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label" for="parent">Trang cha</label>
            </span>
            <select class="select2 form-select select2-hidden-accessible" id="parent" name="parent">
                <option value="0">- Lựa chọn -</option>
                @foreach($allPage as $page)
                    @php
                        $selected   = null;
                        if(!empty($item->seo->parent)&&$item->seo->parent==$page->id) $selected = 'selected';
                    @endphp
                    <option value="{{ $page->id }}" {{ $selected }}>{{ $page->title }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <!-- One Row -->
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Đây là Số lượt đánh giá này được hiển thị trên trang website và ngoài Google để thể hiện sự uy tín (tự nhập tùy thích)
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label inputRequired" for="rating_aggregate_count">Lượt đánh giá</label>
            </span>
            <input type="number" id="rating_aggregate_count" class="form-control" name="rating_aggregate_count" value="{{ old('rating_aggregate_count') ?? $item->seo['rating_aggregate_count'] ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <span data-toggle="tooltip" data-placement="top" title="
                Đây là Điểm đánh giá tương ứng này được hiển thị trên trang website và ngoài Google để thể hiện sự uy tín (tự nhập tùy thích)
            ">
                <i class="explainInput" data-feather='alert-circle'></i>
                <label class="form-label inputRequired" for="rating_aggregate_star">Điểm đánh giá</label>
            </span>
            <input type="text" id="rating_aggregate_star" class="form-control" name="rating_aggregate_star" value="{{ old('rating_aggregate_star') ?? $item->seo['rating_aggregate_star'] ?? '' }}" required>
            <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
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