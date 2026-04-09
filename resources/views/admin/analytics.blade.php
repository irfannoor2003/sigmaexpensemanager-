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
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span
                        class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">Live
                        Sync</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Financial Intelligence</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">HR Credits vs Manager Disbursements • FY
                    {{ $year }}</p>
            </div>

            <form action="{{ route('admin.analytics') }}" method="GET"
    class="flex items-center gap-2 bg-white dark:bg-white/5 p-2 rounded-2xl border border-gray-200 dark:border-white/10 shadow-sm">

    <select name="year"
        class="bg-transparent border-none text-xs font-semibold dark:text-white focus:ring-0 cursor-pointer outline-none appearance-none">

        @foreach (range(date('Y'), date('Y') - 3) as $y)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }} class="dark:text-black">
                Financial Year  {{ $y }}
            </option>
        @endforeach

    </select>

    <button
        class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-pink-500/20">
        Sync
    </button>
</form>
        </div>



        {{-- KPI Cards --}}
        @php $net = $totalYearlyCredit - $totalYearlyDebit; @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            {{-- Total HR Credits --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400">Total
                        HR Credits</span>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono">
                    {{ number_format($totalYearlyCredit) }}</p>
                <p class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-widest">PKR Inbound</p>
            </div>

            {{-- Manager Debits --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div class="p-2 rounded-xl bg-pink-500/10 text-pink-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest text-pink-600 dark:text-pink-400">Manager
                        Debits</span>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white font-mono">
                    {{ number_format($totalYearlyDebit) }}</p>
                <p class="text-xs text-gray-400 mt-1 font-semibold uppercase tracking-widest">PKR Expenses</p>
            </div>

            {{-- Net Balance --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <div
                        class="p-2 rounded-xl {{ $net >= 0 ? 'bg-indigo-500/10 text-indigo-500' : 'bg-rose-500/10 text-rose-500' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-bold uppercase tracking-widest {{ $net >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-rose-600 dark:text-rose-400' }}">Current
                        Liquidity</span>
                </div>
                <p
                    class="text-3xl font-bold font-mono {{ $net >= 0 ? 'text-gray-900 dark:text-white' : 'text-rose-500' }}">
                    {{ number_format($net) }}</p>
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="flex h-1.5 w-1.5 rounded-full {{ $net >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }} animate-pulse"></span>
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-widest">PKR Live Balance</p>
                </div>
            </div>
        </div>

        {{-- Monthly Variance Chart --}}
        <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Monthly Variance</h3>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Capital Flow
                        Architecture</p>
                </div>
                {{-- Legend --}}
                <div
                    class="flex items-center gap-6 bg-gray-50 dark:bg-white/5 px-5 py-3 rounded-2xl border border-gray-100 dark:border-white/10">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-[10px] font-bold dark:text-gray-300 uppercase tracking-widest">Credits</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-pink-500"></span>
                        <span class="text-[10px] font-bold dark:text-gray-300 uppercase tracking-widest">Debits</span>
                    </div>
                </div>
            </div>
            <canvas id="analyticsChart" class="max-h-[350px]"></canvas>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('analyticsChart').getContext('2d');
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                            label: 'HR Credits',
                            data: @json($creditsData),
                            backgroundColor: '#10b981',
                            borderRadius: 8,
                            barThickness: 18
                        },
                        {
                            label: 'Manager Expenses',
                            data: @json($debitsData),
                            backgroundColor: '#ec4899',
                            borderRadius: 8,
                            barThickness: 18
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) =>
                                    ` ${ctx.dataset.label}: PKR ${ctx.parsed.y.toLocaleString()}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                callback: val => val >= 1000 ? val / 1000 + 'k' : val
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
