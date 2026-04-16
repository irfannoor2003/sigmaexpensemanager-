<x-app-layout>
    {{-- Floating Back Button --}}
    <a href="{{ route('manager.dashboard') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- 1. CONVERTED Breadcrumbs & Header --}}
            <div class="flex flex-col gap-3">
                <a href="{{ route('manager.dashboard') }}"
                    class="group flex items-center gap-2 text-pink-500 text-[10px] font-black uppercase tracking-[0.2em] hover:gap-3 transition-all duration-300">
                </a>

                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-pink-600 dark:text-pink-400 font-bold">
                            {{__('app.Analytics & Log')}}
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tighter">
                        {{__('app.Expense History')}}
                    </h1>
                </div>
            </div>

            {{-- 2. Filter Bar / Stats Bar --}}
            <div
                class="p-4 rounded-[2rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex flex-wrap gap-4 items-center justify-between backdrop-blur-sm">
                <div class="flex items-center gap-4">
                    <div class="px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-2xl">
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                            {{__('app.TOTAL RECORDS')}}:
                            <span class="text-pink-500 ml-1">
                                {{ method_exists($expenses, 'total') ? $expenses->total() : $expenses->count() }}
                            </span>
                        </p>
                    </div>
                </div>


            </div>

            {{-- 3. Main Table Container --}}
            <div
                class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr
                                class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">{{__('app.Expense Details')}}
                                </th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">{{__('app.CATEGORY')}}</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">{{__('app.Date (Actual)')}}th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">{{__('app.AMOUNT')}}</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px] text-right">{{__('app.STATUS')}}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($expenses as $item)
                                <tr class="group hover:bg-pink-500/[0.02] transition-all duration-300">
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-gray-900 dark:text-white group-hover:text-pink-500 transition-colors">{{ $item->title }}</span>
                                            <span class="text-[10px] text-gray-400 font-mono">{{__('app.Ref:')}}
                                                #EXP-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                    </td>
                                 <td class="px-8 py-5">
    <span
        class="px-3 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400 text-[10px] font-black uppercase tracking-tight">

        {{ $item->category
            ? __('app.categories.' . $item->category->name)
            : __('app.General')
        }}

    </span>
</td>
                                    <td class="px-8 py-5 text-gray-500 dark:text-gray-400 text-[11px] font-bold">
                                        {{ $item->expense_date->format('d M, Y') }}
                                    </td>
                                    <td
                                        class="px-8 py-5 font-mono font-black text-pink-600 dark:text-pink-400 text-base">
                                        -{{__('app.Rs')}}. {{ number_format($item->amount) }}
                                    </td>
                                    <td class="px-8 py-5 text-right">
    <span
        class="inline-flex px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest
        {{ $item->status === 'approved' ? 'bg-emerald-500/10 text-emerald-500' : ($item->status === 'rejected' ? 'bg-rose-500/10 text-rose-500' : 'bg-orange-500/10 text-orange-500') }}">

        {{ __('app.status.' . strtolower($item->status)) }}
    </span>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-30">
                                            <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-xs font-black uppercase tracking-[0.4em]">No Records Found
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. Footer Pagination --}}
            @if (method_exists($expenses, 'links'))
                <div class="mt-8 pb-12">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
