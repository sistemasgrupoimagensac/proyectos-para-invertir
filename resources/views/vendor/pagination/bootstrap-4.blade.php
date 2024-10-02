@if ($paginator->hasPages())
    <ul class="pagination justify-content-center" role="navigation">
        {{-- Previous Page Link --}}
        @if(!$paginator->onFirstPage())
            <li class="page-item">
                <a class="page-link ms-2 d-flex justify-content-center align-items-center rounded-circle" style="width: 60px; height: 60px;" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"><i class="fa-solid fa-chevron-left"></i></a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link ms-2 d-flex justify-content-center align-items-center rounded-circle" style="width: 60px; height: 60px;">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link ms-2 d-flex justify-content-center align-items-center rounded-circle" style="width: 60px; height: 60px;">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link ms-2 d-flex justify-content-center align-items-center rounded-circle" style="width: 60px; height: 60px;" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link ms-2 d-flex justify-content-center align-items-center rounded-circle" style="width: 60px; height: 60px;" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"><i class="fa-solid fa-chevron-right"></i></a>
            </li>
        @endif
    </ul>
@endif
