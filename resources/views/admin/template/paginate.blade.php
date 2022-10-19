@if ($paginator->hasPages())
    <div class="pull-right pagination">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if($paginator->onFirstPage())
                <li class="page-item prev disabled"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"></a></li>
            @else
                <li class="page-item prev"><a class="page-link" href="{{ $paginator->previousPageUrl() }}"></a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item next"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"></a></li>
            @else
                <li class="page-item next disabled"><a class="page-link" href="{{ $paginator->nextPageUrl() }}"></a></li>
            @endif
        </ul>
    </div>
    <!-- Pagination -->
@endif