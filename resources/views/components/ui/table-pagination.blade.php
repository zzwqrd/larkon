@props(['items'])

@if ($items->hasPages())
    <nav>
        <ul class="pagination justify-content-end mb-0" id="tablePagination">
            {{-- Previous Page Link --}}
            @if ($items->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="bx bx-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link ajax-page-link" href="#!" data-page="{{ $items->currentPage() - 1 }}" rel="prev"><i class="bx bx-chevron-left"></i></a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $page => $url)
                @if ($page == $items->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link ajax-page-link" href="#!" data-page="{{ $page }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($items->hasMorePages())
                <li class="page-item">
                    <a class="page-link ajax-page-link" href="#!" data-page="{{ $items->currentPage() + 1 }}" rel="next"><i class="bx bx-chevron-left bx-rotate-180"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link"><i class="bx bx-chevron-left bx-rotate-180"></i></span>
                </li>
            @endif
        </ul>
    </nav>
@endif
