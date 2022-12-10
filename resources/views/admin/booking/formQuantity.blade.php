@if(!empty($prices))
    @foreach($prices as $item)
        <div class="formBox_full_item">
            <label class="form-label">{{ $item->apply_age ?? 'Không xác định' }} * {!! !empty($item->price) ? '<span style="font-weight:bold;">'.number_format($item->price).config('main.unit_currency').'</span>' : '-' !!}</label>
            @php
                $value          = '';
                foreach($quantity as $q){
                    if($item->apply_age==$q->option_age){
                        $value  = $q->quantity;
                        break;
                    }
                }
            @endphp 
            <input type="number" min="0" class="form-control" name="quantity[{{ $item->id }}]" placeholder="0" value="{{ $value }}" />
        </div>
    @endforeach
@else 
    <div>Không có dữ liệu phù hợp</div>
@endif