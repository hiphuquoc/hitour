@if(!empty($item))
@php
    $no = $no ?? 0;
@endphp
<tr id="serviceLocation_{{ $item->id ?? 0 }}">
    <td style="width:60px;text-align:center;">
        {{ $no }}
    </td>
    <td style="width:200px;text-align:right;">
        <img src="{{ $item->seo->image_small ?? config('default_750x460') }}?{{ time() }}" />
    </td>
    <td style="vertical-align:top;">
        <div class="oneLine">
            <span class="tableHighLight">
                Tiêu đề Trang: (<span class="highLight_500">{{ !empty($item->seo->title) ? mb_strlen($item->seo->title) : null }}</span>)
            </span>
            {{ $item->seo->title ?? null }}
        </div>
        <div class="oneLine">
            <span class="tableHighLight">
                Tiêu đề SEO: (<span class="highLight_500">{{ !empty($item->seo->seo_title) ? mb_strlen($item->seo->seo_title) : null }}</span>)
            </span>
            {{ $item->seo->seo_title ?? null }}
        </div>
        <div class="oneLine maxLine_2">
            <span class="tableHighLight">
                Mô tả SEO: (<span class="highLight_500">{{ !empty($item->seo->seo_description) ? mb_strlen($item->seo->seo_description) : null }}</span>)
            </span>
            {{ $item->seo->seo_description ?? null }}
        </div>
        <div class="oneLine">
            <span class="tableHighLight">
                Đường dẫn tĩnh:
            </span>
            {{ $item->seo->slug_full ?? null }}
        </div>
    </td>
    <td style="vertical-align:top;width:210px;">
        @if(!empty($item->files))
            @foreach($item->files as $slider)
                @if(file_exists(public_path($slider->file_path)))
                    <div class="oneLine">
                        <img src="{{ $slider->file_path }}" style="width:210px;border-radius:0;" />
                    </div>
                @endif
            @endforeach
        @endif

    </td>
    <td style="vertical-align:top;">
        <div class="oneLine">
            Đánh giá:
            <span class="tableHighLight">
                {{ $item->seo->rating_aggregate_star ?? '-' }} / {{ $item->seo->rating_aggregate_count ?? '-' }}
            </span>
        </div>
        @if(!empty($item->seo->created_at))
            <div class="oneLine">
                <i data-feather='plus'></i>
                {{ date('H:i, d/m/Y', strtotime($item->seo->created_at)) }}
            </div>
        @endif
        @if(!empty($item->seo->updated_at))
            <div class="oneLine">
                <i data-feather='edit-2'></i>
                {{ date('H:i, d/m/Y', strtotime($item->seo->updated_at)) }}
            </div>
        @endif
    </td>
    <td style="vertical-align:top;display:flex;font-size:0.95rem;">
        @if(!empty($item->seo->slug_full))
            <div class="icon-wrapper iconAction">
                <a href="{{ url($item->seo->slug_full) }}" target="_blank">
                    <i data-feather='eye'></i>
                    <div>Xem</div>
                </a>
            </div>
        @endif
        <div class="icon-wrapper iconAction">
            <a href="{{ route('admin.serviceLocation.view', ['id' => $item->id ?? 0, 'type' => 'edit' ]) }}">
                <i data-feather='edit'></i>
                <div>Sửa</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <a href="{{ route('admin.serviceLocation.view', ['id' => $item->id ?? 0, 'type' => 'copy']) }}">
                <i data-feather='copy'></i>
                <div>Chép</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <div class="actionDelete" onClick="deleteItem('{{ $item->id ?? 0 }}');">
                <i data-feather='x-square'></i>
                <div>Xóa</div>
            </div>
        </div>
    </td>
</tr>
@endif