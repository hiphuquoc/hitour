{{-- <option value="0">- Lựa chọn -</option> --}}
@if(!empty($data))
    @php
        $variableDistinct   = [];
        $tmp            = [];
    @endphp
    @foreach($data as $item)
        @if(!in_array($item->id, $variableDistinct))
            @php
                $selected       = null;
                $tmp['item'][]  = $item->name;
                $tmp['name'][]  = $namePortActive;
                if(!empty($namePortActive)&&$item->name==$namePortActive) $selected = 'selected';
                $name   = \App\Helpers\Build::buildFullShipPort($item);
                $variableDistinct[] = $item->id;
            @endphp
            <option value="{{ $item->id  }}" {{ $selected }}>{!! $name !!}</option>
        @endif
    @endforeach
@endif