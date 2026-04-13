@if ($paginator->hasPages())
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">

        <div class="btn-group" style="gap: 4px; border-radius: var(--radius-sm);  background:transparent;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button type="button" class="btn btn-secondary btn-sm" disabled
                    style="opacity:0.5; cursor:not-allowed; background:var(--bg-alt);">
                    <i class="bi bi-chevron-left"></i>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "..." Three Dots --}}
                @if (is_string($element))
                    <button type="button" class="btn btn-secondary btn-sm" disabled
                        style="background:transparent; border-color:transparent; opacity:0.6;">
                        {{ $element }}
                    </button>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <button type="button" class="btn btn-primary btn-sm" style="min-width:32px;">
                                {{ $page }}
                            </button>
                        @else
                            <a href="{{ $url }}" class="btn btn-secondary btn-sm" style="min-width:32px; text-align:center;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-chevron-right"></i>
                </a>
            @else
                <button type="button" class="btn btn-secondary btn-sm" disabled
                    style="opacity:0.5; cursor:not-allowed; background:var(--bg-alt);">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @endif
        </div>
    </div>
@endif