{{-- <option value="0">- Lựa chọn -</option> --}}
@if(!empty($data))
    @php
        $variableDistinct   = [];
    @endphp
    @foreach($data as $item)
        @if(!in_array($item->id, $variableDistinct))
            @php
                $name   = \App\Helpers\Build::buildFullShipPort($item);
                $variableDistinct[] = $item->id;
            @endphp
            <option value="{{ $item->id  }}">{!! $name !!}</option>
        @endif
    @endforeach
@endif