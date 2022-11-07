@if(!empty($data))
    @foreach($data as $item)
        <a href="#{{ $item['id'] }}" class="tocContentTour_item" {{ substr($item['name'], -1, 1)==3 ? 'style=padding-left:2.5rem;' : null }}>
            {!! $item['icon'] ?? null !!}{!! $item['title'] !!}
        </a>
    @endforeach
@endif