<x-app-layout>
    {{-- Floating Back Button --}}
    <a href="{{ route('manager.dashboard') }}"
       class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Header Section with Pulse Badge --}}
            <div class="flex flex-col gap-3">
                <div class="flex flex-col">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-emerald-600 dark:text-emerald-400 font-bold">
                            Wallet Ledger
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tighter">
                        Top-up History
                    </h1>
                </div>
            </div>

            {{-- Filter/Summary Bar --}}
            <div class="p-4 rounded-[2rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex flex-wrap gap-4 items-center justify-between backdrop-blur-sm">
                <div class="flex items-center gap-4">
                    <div class="px-4 py-2 bg-gray-50 dark:bg-white/5 rounded-2xl">
                        <p class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                            Transaction Count:
                            <span class="text-emerald-500 ml-1">
                                {{ method_exists($topups, 'total') ? $topups->total() : $topups->count() }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="flex gap-2">
                    @if(method_exists($topups, 'links'))
                        {{ $topups->links('pagination::tailwind') }}
                    @endif
                </div>
            </div>

            {{-- Transactions Table --}}
            <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">Source & Method</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">Timestamp</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">Amount</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px]">Reference/Remarks</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[9px] text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($topups as $log)
                            <tr class="group hover:bg-emerald-500/[0.02] transition-all duration-300">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-xl group-hover:scale-110 transition-transform">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                            </svg>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white">
                                                {{ $log->created_by == auth()->id() ? 'Self Requested' : 'HR Credit' }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Deposit</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-gray-500 dark:text-gray-400 text-[11px] font-bold">
                                    {{ $log->created_at->format('d M, Y') }}
                                    <span class="block text-[9px] text-gray-400 font-medium">{{ $log->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-8 py-5 font-mono font-black text-emerald-600 dark:text-emerald-400 text-base">
                                    +Rs. {{ number_format($log->amount) }}
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-gray-500 dark:text-gray-400 text-[11px] italic">
                                        {{ $log->remarks ?? 'No remarks provided' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <span class="inline-flex px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        CLEARED
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-30">
                                        <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.407 2.63 1m-2.63-1V7m0 1v1m0 5v1m0-1c-1.11 0-2.08-.407-2.63-1m2.63 1v1m2.63-2.25c0 1.242-1.12 2.25-2.5 2.25s-2.5-1.008-2.5-2.25 1.12-2.25 2.5-2.25 2.5 1.008 2.5 2.25z"/>
                                        </svg>
                                        <p class="text-xs font-black uppercase tracking-[0.4em]">No Top-ups Found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Footer Pagination --}}
            @if(method_exists($topups, 'links'))
                <div class="mt-8 pb-12">
                    {{ $topups->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
