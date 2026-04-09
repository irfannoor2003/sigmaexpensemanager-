<x-app-layout>
    {{-- Floating Back Button --}}
    <a href="{{ route('hr.dashboard') }}"
       class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Dynamic Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-widest font-bold text-pink-600 dark:text-pink-400">
                            Sigma Archive
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        Master Expense Ledger
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Reviewing historic spendings and audit trails.</p>
                </div>

                {{-- Filter & Export Bar --}}
                <div class="flex flex-wrap items-center gap-4">

    {{-- Filter Month --}}
    <form action="{{ route('hr.expenses.history') }}" method="GET"
          class="flex items-center gap-2 bg-white dark:bg-white/5 p-1.5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">

        <div class="pl-2 text-gray-400">
            {{-- Calendar Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect width="18" height="18" x="3" y="4" rx="2"/>
                <line x1="16" x2="16" y1="2" y2="6"/>
                <line x1="8" x2="8" y1="2" y2="6"/>
                <line x1="3" x2="21" y1="10" y2="10"/>
            </svg>
        </div>

        <input type="month" name="month"
               value="{{ request('month', now()->format('Y-m')) }}"
               class="bg-transparent border-none text-xs font-semibold dark:text-gray-300 focus:ring-0 cursor-pointer">

        <button type="submit"
                class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-3 py-2 rounded-lg text-[10px] font-bold uppercase transition-all hover:opacity-80">
            Filter
        </button>
    </form>

    {{-- Export --}}
    <form action="{{ route('hr.export') }}" method="GET"
      class="flex items-center gap-2 bg-white dark:bg-white/5 p-1.5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">

    {{-- ✅ Pass current filters --}}
    <input type="hidden" name="month" value="{{ request('month') }}">
    <input type="hidden" name="status" value="{{ request('status') }}">
    <input type="hidden" name="user_id" value="{{ request('user_id') }}">
    <input type="hidden" name="search" value="{{ request('search') }}">

    <button type="submit"
        class="flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-lg shadow-pink-500/20">
        Export
    </button>
</form>

</div>
            </div>

            {{-- Summary Stats (Optional but Professional) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">Total Filtered Amount</p>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rs. {{ number_format($expenses->sum('amount')) }}</h3>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-pink-500/10 flex items-center justify-center text-pink-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <div class="p-6 rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400">Total Record Count</p>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $expenses->total() }} Transactions</h3>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                </div>
            </div>

            {{-- Master Table Container --}}
            <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[3rem] overflow-hidden shadow-2xl backdrop-blur-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">User / Role</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">Transaction Details</th>
                             <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">Expense Date</th>
                               <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">Amount</th>
                              <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($expenses as $item)
                            <tr class="group hover:bg-pink-500/[0.03] transition-all duration-300">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/10 flex items-center justify-center font-black text-pink-500 text-xs shadow-sm">
                                            {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                           <span class="font-semibold text-gray-900 dark:text-white">
{{ $item->user->name }}</span>
                                            <span class="text-[10px] uppercase text-gray-400 tracking-wider font-bold">{{ $item->user->role }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $item->title }}</span>
                                        <span class="text-[10px] font-mono text-pink-500">ID: #{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->expense_date->format('D, d M Y') }}</span>
                                        <span class="text-[10px] text-gray-400 uppercase tracking-wider">Added: {{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                  <span class="text-sm font-mono font-bold text-pink-600 dark:text-pink-400">
                                        -Rs. {{ number_format($item->amount) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="inline-flex px-4 py-2 rounded-2xl text-[10px] font-bold uppercase tracking-wider border {{ $item->status === 'approved' ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-orange-500/10 text-orange-500 border-orange-500/20 shadow-lg shadow-orange-500/10' }}">
                                        {{ $item->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 text-gray-500">No Historical Records Found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Custom Styled Pagination Footer --}}
                @if($expenses->hasPages())
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-white/5">
                    {{ $expenses->links() }}
                </div>
                @endif
            </div>

            <div class="h-10"></div> {{-- Spacer --}}
        </div>
    </div>

    <style>
        /* Smooth Scrollbar for Table */
        .overflow-x-auto::-webkit-scrollbar { height: 6px; }
        .overflow-x-auto::-webkit-scrollbar-thumb { background: rgba(236, 72, 153, 0.1); border-radius: 10px; }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: rgba(236, 72, 153, 0.3); }
    </style>
</x-app-layout>
