@if(!empty($options))
    <div class="optionListBox">
        @foreach($options as $item)
            <label class="optionListBox_item" for="option_{{ $item['tour_option_id'] }}">
                <div class="optionListBox_item_radio">
                    <div class="form-check form-check-info">
                        <input type="radio" id="option_{{ $item['tour_option_id'] }}" name="tour_option_id" value="{{ $item['tour_option_id'] }}" class="form-check-input" {{ !empty($optionChecked)&&$optionChecked==$item['tour_option_id'] ? 'checked' : null }} onChange="loadFormPriceQuantity(this.value);">
                    </div>
                </div>
                <div class="optionListBox_item_option">
                    {{ $item['option'] ?? 'Không có dữ liệu' }}<br/>
                    @if(!empty($item['date_apply']))
                        {{-- @php
                            dd($item['date_apply']);
                        @endphp --}}
                        @foreach($item['date_apply'] as $dateApply)
                            <div>{{ date('d-m-Y', strtotime($dateApply[0]['date_start'])) }} - {{ date('d-m-Y', strtotime($dateApply[0]['date_end'])) }}</div>
                        @endforeach
                    @endif
                    {{-- ({{ $item->apply_day ?? 'Không có dữ liệu' }}) --}}
                </div>
                @if(!empty($item->prices))
                    <div class="optionListBox_item_detail">
                        @foreach($item->prices as $price)
                            <div class="oneLine">
                                <span>{!! !empty($price->price) ? number_format($price->price).'<sup>đ</sup>' : '-' !!}</span> /{{ $price->apply_age ?? '-' }}
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