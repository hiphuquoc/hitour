{{-- <option value="0">- Lựa chọn -</option> --}}
@if(!empty($data))
    @foreach($data as $item)
        @php
            $selected = null;
        @endphp
        <option value="{{ $item->id  }}" {{ $selected }}>{!! $item->name !!}</option>
    @endforeach
@endif