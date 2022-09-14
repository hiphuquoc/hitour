{{-- <option value="0">- Lựa chọn -</option> --}}
@if(!empty($data))
    @php
        $variableDistinct   = [];
    @endphp
    @foreach($data as $item)
        @if(!in_array($item->id, $variableDistinct))
            @php
                $selected = null;
                if(!empty($namePortActive)&&$item->name==$namePortActive) $selected = 'selected';
                $name   = \App\Helpers\Build::buildFullShipPort($item);
                $variableDistinct[] = $item->id;
            @endphp
            <option value="{{ $item->id  }}" {{ $selected }}>{!! $name !!}</option>
        @endif
    @endforeach
@endif