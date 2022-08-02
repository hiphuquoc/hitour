<option value="0">- Lựa chọn -</option>
@if(!empty($data))
    @foreach($data as $item)
        <option value="{{ $item['id'] }}">
            {{ $item['name'] }}
        </option>
    @endforeach
@endif