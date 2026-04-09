@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- Mobile --}}
        <div class="flex gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-400 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 cursor-not-allowed rounded-xl">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-pink-500 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-500/10 transition-all">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-pink-500 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl hover:bg-pink-50 dark:hover:bg-pink-500/10 transition-all">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-xs font-bold uppercase tracking-widest text-gray-400 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 cursor-not-allowed rounded-xl">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between gap-4">

            <p class="text-xs text-gray-400 dark:text-gray-500 font-semibold uppercase tracking-widest">
                Showing
                @if ($paginator->firstItem())
                    <span class="text-pink-500 font-bold">{{ $paginator->firstItem() }}</span>
                    –
                    <span class="text-pink-500 font-bold">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                of
                <span class="text-pink-500 font-bold">{{ $paginator->total() }}</span>
                results
            </p>

            <div class="flex items-center gap-1">

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-white/10 text-gray-300 dark:text-gray-600 cursor-not-allowed bg-white dark:bg-white/5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400 bg-white dark:bg-white/5 hover:bg-pink-50 dark:hover:bg-pink-500/10 hover:text-pink-500 hover:border-pink-300 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="inline-flex items-center justify-center w-9 h-9 text-xs font-bold text-gray-400 dark:text-gray-500">
                            {{ $element }}
                        </span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-xs font-bold text-white bg-pink-500 border border-pink-500 shadow-lg shadow-pink-500/20 cursor-default">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-xs font-bold text-gray-500 dark:text-gray-400 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:bg-pink-50 dark:hover:bg-pink-500/10 hover:text-pink-500 hover:border-pink-300 transition-all" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400 bg-white dark:bg-white/5 hover:bg-pink-50 dark:hover:bg-pink-500/10 hover:text-pink-500 hover:border-pink-300 transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 dark:border-white/10 text-gray-300 dark:text-gray-600 cursor-not-allowed bg-white dark:bg-white/5">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif

            </div>
        </div>
    </nav>
@endif
