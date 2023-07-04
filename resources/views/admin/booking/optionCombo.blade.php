@if(!empty($options))
    <div class="optionListBox">
        @foreach($options as $item)
            <label class="optionListBox_item" for="option_{{ $item['id'] }}" onClick="loadFormQuantityByOption({{ $item['id'] }});">
                <div class="optionListBox_item_radio">
                    <div class="form-check form-check-info">
                        @php
                            $checked        = null;
                            /* nếu không có phần tử chỉ định active thì lấy phần từ đầu tiên active */
                            if(!empty($active)){
                                if($active==$item['name']) $checked = 'checked';
                            }else {
                                if($loop->index==0) $checked = 'checked';
                            }
                        @endphp
                        <input type="radio" id="option_{{ $item['id'] }}" name="combo_option_id" value="{{ $item['id'] }}" class="form-check-input" {{ $checked }} />
                    </div>
                </div>
                <div class="optionListBox_item_option">
                    <div style="font-weight:bold;">{{ $item['name'] ?? 'Không có dữ liệu' }}</div>
                    <div style="color:red;">{{ date('d-m-Y', strtotime($item['prices'][0]['date_start'])) }} - {{ date('d-m-Y', strtotime($item['prices'][0]['date_end'])) }}</div>
                </div>
                @if(!empty($item['prices']))
                    <div class="optionListBox_item_detail">
                        @foreach($item['prices'] as $price)
                            <div class="oneLine">
                                <span style="color:rgb(0,90,180);font-size:1.2rem;">{!! !empty($price['price']) ? number_format($price['price']).config('main.unit_currency') : '-' !!}</span> /{{ $price['apply_age'] ?? '-' }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </label>
        @endforeach
    </div>
@else
    <div>Không có dữ liệu phù hợp!</div>
@endif