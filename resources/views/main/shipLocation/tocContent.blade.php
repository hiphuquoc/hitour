@if(!empty($data))
    @foreach($data as $item)
        <a href="#{{ $item['id'] }}" class="tocContentTour_item">
            {!! $item['icon'].$item['title'] !!}
        </a>
    @endforeach
@endif