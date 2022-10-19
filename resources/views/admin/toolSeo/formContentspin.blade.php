
<!-- One Row -->
<div class="formBox_full_item">
    <input type="hidden" name="seo_id" value="{{ $item->id ?? null }}" />
    <label class="form-label inputRequired" for="title">Tiêu đề</label>
    <textarea class="form-control" name="title" rows="2" required>{{ $item->contentspin->title ?? null }}</textarea>
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
</div>
<!-- One Row -->
<div class="formBox_full_item">
    <label class="form-label" for="description">Mô tả</label>
    <textarea class="form-control" name="description" rows="2">{{ $item->contentspin->description ?? null }}</textarea>
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
</div>
<!-- One Row -->
<div class="formBox_full_item">
    <label class="form-label inputRequired" for="content">Nội dung</label>
    <textarea class="form-control" name="content" rows="10" required>{{ $item->contentspin->content ?? null }}</textarea>
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
</div>