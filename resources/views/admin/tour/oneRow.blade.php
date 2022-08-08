@if(!empty($item))
@php
    $no = $no ?? 0;
@endphp
<tr id="tour_{{ $item->id }}">
    <td style="width:60px;text-align:center;">
        {{ $no }}
    </td>
    <td style="width:210px;">
        <img src="{{ $item->seo->image_small }}" />
        @if(!empty($item->locations))
            <div class="oneLine">
                Điểm đến: 
                @foreach($item->locations as $location)
                    <div class="badge bg-primary" {{ $loop->index>0 ? 'style=margin-left:0.25rem' : null }}>{{ $location->infoLocation['name'] ?? null }}</div>
                @endforeach
            </div>
            <div class="oneLine">
                Khởi hành: <div class="badge bg-primary">{{ $item->departure->name ?? null }}</div>
            </div>
        @endif
    </td>
    <td style="vertical-align:top;">
        <div class="oneLine">
            <span class="tableHighLight">
                Tiêu đề Trang: (<span class="highLight_500">{{ mb_strlen($item->seo->title) }}</span>)
            </span>
            {{ $item->seo->title }}
        </div>
        <div class="oneLine">
            <span class="tableHighLight">
                Tiêu đề SEO: (<span class="highLight_500">{{ mb_strlen($item->seo->seo_title) }}</span>)
            </span>
            {{ $item->seo->seo_title }}
        </div>
        <div class="oneLine">
            <span class="tableHighLight">
                Mô tả SEO: (<span class="highLight_500">{{ mb_strlen($item->seo->seo_description) }}</span>)
            </span>
            {{ $item->seo->seo_description }}
        </div>
        <div class="oneLine">
            <span class="tableHighLight">
                Đường dẫn tĩnh:
            </span>
            {{ $item->seo->slug }}
        </div>
    </td>
    <td style="vertical-align:top;width:210px;">
        @php
            if(!empty($item->files)){
                $gallery        = [];
                foreach($item->files as $file){
                    if($file->file_type==='gallery') $gallery[]  = $file;
                }
            }
        @endphp
        @if(!empty($gallery))
            <div style="position:relative;">
                <img src="{{ $gallery[0]->file_path }}" style="width:210px;" />
                @if(count($gallery)>1)
                    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);font-size:2rem;color:#fff;font-weight:bold;border-radius:50%;border:3px solid #fff;padding:0.2rem 1rem;background:rgba(0,0,0,0.2);">{{ count($gallery) }}</span>
                @endif
            </div>
        @endif
    </td>
    <td style="vertical-align:top;width:210px;">
        @php
            if(!empty($item->files)){
                $slider        = [];
                foreach($item->files as $file){
                    if($file->file_type==='slider') $slider[]  = $file;
                }
            }
        @endphp
        @if(!empty($slider))
            <div style="position:relative;">
                @foreach($slider as $s)
                    @if($loop->index<3)
                        <div class="oneLine">
                            <img src="{{ $s->file_path }}" style="width:210px;border-radius:0;" />
                        </div>
                    @endif
                @endforeach
                @if(count($slider)>3)
                    <span style="position:absolute;top:50%;left:50%;transform:translate(-50%, -50%);font-size:2rem;color:#fff;font-weight:bold;border-radius:50%;border:3px solid #fff;padding:0.2rem 1rem;background:rgba(0,0,0,0.2);">{{ count($slider) }}</span>
                @endif
            </div>
        @endif

    </td>
    <td style="vertical-align:top;">
        <div class="oneLine">
            Đánh giá:
            <span class="tableHighLight">
                {{ $item->seo->rating_aggregate_star }} / {{ $item->seo->rating_aggregate_count }}
            </span>
        </div>
        <div class="oneLine">
            <i data-feather='edit-2'></i>
            {{ date('H:i, d/m/Y', strtotime($item->seo->updated_at)) }}
        </div>
        @if(!empty($item->partners))
            <div class="oneLine">
                Đối tác:<br/>
                @foreach($item->partners as $partner)
                    @if(!empty($partner->infoPartner['name'])) 
                        <span class="badge bg-primary" {{ $loop->index>0 ? 'style=margin-left:0.25rem' : null }}>{{ $partner->infoPartner['name'] }}</span>
                    @endif
                @endforeach
            </div>
        @endif
        @if(!empty($item->staffs))
            <div class="oneLine">
                Nhân viên:<br/>
                @foreach($item->staffs as $staff)
                    <div class="badge bg-primary" {{ $loop->index>0 ? 'style=margin-left:0.25rem' : null }}>{{ $staff->infoStaff['prefix_name'] }}. {{ $staff->infoStaff['lastname'] }}</div>
                @endforeach
            </div>
        @endif
    </td>
    <td style="vertical-align:top;display:flex;">
        <div class="icon-wrapper iconAction">
            @php
                $tmp    = \App\Helpers\Url::buildFullLinkOne($item->seo);
            @endphp
            <a href="/{{ $tmp->slug_full }}" target="_blank">
                <i data-feather='eye'></i>
                <div>Xem</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <a href="{{ route('admin.tour.viewEdit', ['id' => $item->id]) }}">
                <i data-feather='edit'></i>
                <div>Sửa</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <a href="{{ route('admin.tour.viewEdit', ['id' => $item->id, 'type' => 'copy']) }}">
                <i data-feather='copy'></i>
                <div>Chép</div>
            </a>
        </div>
        <div class="icon-wrapper iconAction">
            <div class="actionDelete" onClick="deleteItem('{{ $item->id }}');">
                <i data-feather='x-square'></i>
                <div>Xóa</div>
            </div>
        </div>
    </td>
</tr>
@endif