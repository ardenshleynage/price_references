@if ($paginator->hasPages())
    <nav style="display: flex; justify-content: center; padding: 15px;">
        <ul class="pagination" style="display: flex; gap: 5px; list-style: none; margin: 0; padding: 0;">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" style="pointer-events: none;">
                    <span style="padding: 8px 12px; background: #f0f0f0; color: #888; border-radius: 4px; display: block;">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" style="padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; display: block;">&lsaquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" style="pointer-events: none;">
                        <span style="padding: 8px 12px; color: #888; background: transparent; display: block;">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span style="padding: 8px 12px; background: #28d; color: #fff; border-radius: 4px; display: block;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}" style="padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; display: block;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" style="padding: 8px 12px; background: #f0f0f0; color: #333; text-decoration: none; border-radius: 4px; display: block;">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" style="pointer-events: none;">
                    <span style="padding: 8px 12px; background: #f0f0f0; color: #888; border-radius: 4px; display: block;">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
    <style>
        html.dark .pagination .page-link,
        html.dark .pagination .page-link span {
            background: #444 !important;
            color: #fff !important;
        }
        html.dark .pagination .page-item.active .page-link span {
            background: #28d !important;
        }
    </style>
@endif
