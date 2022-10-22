@if(!empty($item))
@php
    $data = [];
    foreach($item->checkSeos as $check){
        if($check->type=='heading') $data['heading'][] = $check;
        if($check->type=='link') $data['link'][] = $check;
        if($check->type=='image') $data['image'][] = $check;
    }
@endphp
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#heading" role="tab">Thẻ heading</a>
    </li>
    @if(!empty($data['link']))
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#link" role="tab">Liên kết</a>
        </li>
    @endif
    @if(!empty($data['image']))
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#image" role="tab">Ảnh</a>
        </li>
    @endif
</ul>
<div class="tab-content">
    <div class="tab-pane" id="heading" role="tabpanel">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" style="width:60px;">Stt</th>
                    <th class="text-center" style="width:60px;">Thẻ</th>
                    <th class="text-center">Nội dung thẻ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['heading'] as $heading)
                    @php
                        $fontSize = null;
                        if($heading->name=='h1') $fontSize = 'font-size:1.4rem;font-weight:bold;';
                        if($heading->name=='h2') $fontSize = 'font-size:1.2rem;font-weight:bold;';
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->index + 1 }}</td>
                        <td class="text-center">
                            <div>
                                <b>{{ $heading->name }}</b>
                            </div>
                        </td>
                        <td>
                            <span style="{{ $fontSize }}">{{ $heading->text }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(!empty($data['link']))
        <div class="tab-pane" id="link" role="tabpanel">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px;">Stt</th>
                        <th class="text-center">Anchor text</th>
                        <th class="text-center">Đích</th>
                        <th class="text-center">Lỗi</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $colorError         = config('admin.error_color');
                        /* sắp xếp (đưa lỗi lên trên) */
                        $error              = array();
                        foreach($data['link'] as $key => $value){
                            $error[$key]    = $value->error_type;
                        }
                        array_multisort($error, SORT_DESC, $data['link']);
                    @endphp
                    @foreach($data['link'] as $link)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td>
                                {{ $link['text'] }}
                            </td>
                            <td class="text-center">
                                {{ $link['href'] }}
                            </td>
                            <td>
                                <span style="color:{{ !empty($image->error_type) ? $colorError[$image->error_type] : null }};font-weight:bold;">
                                    {{ $link['error'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    @if(!empty($data['image']))
        @php
            $flagError          = false;
            foreach($data['image'] as $image){
                if(!empty($image->error)){
                    $flagError  = true;
                    break;
                }
            }
        @endphp
        <div class="tab-pane active" id="image" role="tabpanel">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px;">Stt</th>
                        <th class="text-center">Src</th>
                        <th class="text-center">Alt /Title</th>
                        @if($flagError==true)
                            <th class="text-center">Lỗi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php
                        $colorError         = config('admin.error_color');
                        /* sắp xếp (đưa lỗi lên trên) */
                        $error              = array();
                        foreach($data['image'] as $key => $value){
                            $error[$key]    = $value->error_type;
                        }
                        array_multisort($error, SORT_DESC, $data['image']);
                    @endphp
                    @foreach($data['image'] as $image)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td>
                                <div>{{ $image->src }}</div>
                            </td>
                            <td>
                                <div>{{ $image->alt }}</div>
                            </td>
                            @if($flagError==true)
                                <td>
                                    <span style="color:{{ !empty($image->error_type) ? $colorError[$image->error_type] : null }};font-weight:bold;">
                                        {{ $image['error'] }}
                                    </span>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endif