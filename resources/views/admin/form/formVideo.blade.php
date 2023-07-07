<!-- One Row -->
<div class="formBox_full_item">
    <span data-toggle="tooltip" data-placement="top" title="
        Đây là Iframe Video Youtube
    ">
        <i class="explainInput" data-feather='alert-circle'></i>
        <label class="form-label" for="video">Iframe Youtube</label>
    </span>
    <textarea class="form-control" name="video" rows="3" placeholder="Bỏ thẻ hight và width của thẻ Iframe">{{ $item->seo->video ?? null }}</textarea>
    <div class="invalid-feedback">{{ config('admin.massage_validate.not_empty') }}</div>
</div>