<x-app-layout>
    <x-toaster />

    <div class="p-4 sm:p-8 min-h-screen">
        {{-- Floating Back Button --}}
        <a href="{{ route('admin.dashboard') }}"
            class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        {{-- Header --}}
        <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">

            <!-- LEFT SIDE -->
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span
                        class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">
                        System Live
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                    Financial Ledger
                </h1>

                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    {{ $allExpenses->count() }} transactions • Sigma Engineering Services
                </p>
            </div>

            <!-- RIGHT SIDE (FILTER) -->
            <form method="GET"
                class="w-full lg:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-2
           bg-white dark:bg-white/5 p-2 rounded-2xl
           border border-gray-200 dark:border-white/10 shadow-sm">

                <div class="relative w-full sm:w-auto">
                    <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}"
                        class="appearance-none w-full sm:w-auto px-3 py-2 rounded-xl bg-transparent
                   text-xs font-semibold dark:text-white
                   focus:ring-2 focus:ring-pink-500 outline-none cursor-pointer">

                    <!-- Custom calendar icon overlay -->
                    <div
                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500 dark:text-gray-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <button
                    class="w-full sm:w-auto px-5 py-2
               bg-pink-500
               hover:from-pink-600 hover:to-rose-600
               text-white text-[10px] font-bold uppercase tracking-widest
               rounded-xl transition-all shadow-lg shadow-pink-500/20
               hover:scale-[1.02] active:scale-95">
                    Sync
                </button>
            </form>

        </div>



        {{-- Stats Row --}}
        @php
            $groupedExpenses = $allExpenses->groupBy(function ($item) {
                return is_array($item->category)
                    ? $item->category['name'] ?? 'General'
                    : $item->category->name ?? 'General';
            });
            $palette = ['#ff5733', '#ffbd33', '#33ff57', '#3357ff', '#ff33a6'];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">

            {{-- Total Spend --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 rounded-xl bg-pink-500/10 text-pink-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-pink-600 dark:text-pink-400">Total
                        Period Outflow</span>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono">
                    {{ number_format($allExpenses->sum('amount')) }}</p>
                <p class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-widest">PKR Disbursed</p>
                <div class="mt-4 h-1 w-full bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-pink-500 w-full animate-pulse"></div>
                </div>
            </div>

            {{-- Category Breakdown --}}
            <div
                class="lg:col-span-2 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm flex items-center gap-6">
                <div class="flex-1 space-y-3">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-4">Category Split</p>
                    <div class="space-y-3 max-h-[140px] overflow-y-auto custom-scrollbar pr-2">
                        @foreach ($groupedExpenses as $categoryName => $group)
                            @php $color = $palette[$loop->index % count($palette)]; @endphp
                            <div class="flex justify-between items-center text-xs">
                                <span class="flex items-center gap-2 font-medium text-gray-500 dark:text-gray-400">
                                    <i class="w-2 h-2 rounded-full flex-shrink-0"
                                        style="background-color: {{ $color }}"></i>
                                    {{ $categoryName }}
                                </span>
                                <span class="font-bold text-[10px] uppercase font-mono"
                                    style="color: {{ $color }}">
                                    {{ $group->count() }} items
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative w-28 h-28 flex-shrink-0">
                    <canvas id="expenseDistributionChart"></canvas>
                </div>
            </div>
        </div>


        <!-- Ledger Table -->
        <div
            class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">

            <!-- Desktop Table -->
            <div class="hidden md:block w-full">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100 dark:border-white/5">
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">Manager</th>
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">Expense Title</th>
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">Category</th>
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">Amount</th>
                            <th class="pb-4 font-bold text-right text-[10px] uppercase tracking-[0.15em]">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                        @forelse ($expenses as $exp)
                            <tr class="group transition-colors hover:bg-gray-50/50 dark:hover:bg-white/5">
                                <td class="py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[10px] font-black uppercase">
                                            {{ strtoupper(substr($exp->user->name, 0, 2)) }}
                                        </div>
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $exp->user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 text-gray-500 dark:text-gray-400">{{ $exp->title }}</td>
                                <td class="py-4">
                                    <span
                                        class="px-3 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 text-[10px] font-bold uppercase tracking-tighter">
                                        {{ is_array($exp->category) ? $exp->category['name'] ?? 'General' : $exp->category->name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="py-4 font-mono font-bold text-pink-600 dark:text-pink-400">
                                    {{ number_format($exp->amount) }} PKR</td>
                                <td class="py-4 text-right text-xs text-gray-400 dark:text-gray-500 font-semibold">
                                    {{ \Carbon\Carbon::parse($exp->expense_date)->format('d M, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 italic text-sm">No
                                    transactions recorded for this period.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Stacked Cards -->
            <div class="md:hidden space-y-4 mt-4">
                @foreach ($expenses as $exp)
                    <div
                        class="p-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[10px] font-black uppercase">
                                    {{ strtoupper(substr($exp->user->name, 0, 2)) }}
                                </div>
                                <span
                                    class="font-semibold text-gray-900 dark:text-white text-sm">{{ $exp->user->name }}</span>
                            </div>
                            <span
                                class="text-xs text-gray-400 dark:text-gray-500 font-semibold">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d M, Y') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <div><span class="font-semibold">Title:</span> {{ $exp->title }}</div>
                            <div>
                                <span class="font-semibold">Category:</span>
                                <span
                                    class="px-2 py-1 rounded-full bg-gray-100 dark:bg-white/5 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-tighter">
                                    {{ is_array($exp->category) ? $exp->category['name'] ?? 'General' : $exp->category->name ?? 'General' }}
                                </span>
                            </div>
                            <div><span class="font-semibold">Amount:</span> <span
                                    class="font-mono font-bold text-pink-600 dark:text-pink-400">{{ number_format($exp->amount) }}
                                    PKR</span></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $expenses->appends(request()->query())->links() }}
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('expenseDistributionChart');
            const categoryLabels = @json($groupedExpenses->keys());
            const categoryCounts = @json($groupedExpenses->map->count()->values());
            const sigmaPalette = ['#ff5733', '#ffbd33', '#33ff57', '#3357ff', '#ff33a6'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: categoryLabels,
                    datasets: [{
                        data: categoryCounts,
                        backgroundColor: sigmaPalette,
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.label}: ${ctx.raw} items`
                            }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(236, 72, 153, 0.2);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #ec4899;
        }
    </style>
</x-app-layout>
