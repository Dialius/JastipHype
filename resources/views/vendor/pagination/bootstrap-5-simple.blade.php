@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">
                        <span aria-hidden="true" style="font-size: 1rem;">‹</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" 
                       style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">
                        <span aria-hidden="true" style="font-size: 1rem;">‹</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link" style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link" style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" 
                       style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">
                        <span aria-hidden="true" style="font-size: 1rem;">›</span>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link" style="font-size: 0.875rem; padding: 0.375rem 0.75rem;">
                        <span aria-hidden="true" style="font-size: 1rem;">›</span>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
    
    {{-- Showing X to Y of Z results --}}
    @if ($paginator->total() > 0)
        <div class="mt-2">
            <p class="small text-muted mb-0">
                Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
            </p>
        </div>
    @endif
@endif
