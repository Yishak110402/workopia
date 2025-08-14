@if ($paginator->haspages())
    <nav class="flex justify-center gap-8">
        @if ($paginator->onFirstPage())
            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-l-lg">Previous</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-l-lg">Previous</a>
        @endif
        @if (!$paginator->hasMorePages())
            <span class="px-4 py-2 bg-gray-300 text-gray-500 rounded-r-lg">Next</span>
        @else
            <a href="{{ $paginator->nextPageUrl() }}"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-r-lg">Next</a>
        @endif

    </nav>
@else
@endif
