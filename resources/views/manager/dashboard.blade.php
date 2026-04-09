<x-app-layout>
    <x-toaster />

    <div class="p-4 sm:p-8 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
                <div class="w-full sm:w-auto">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-widest text-pink-600 dark:text-pink-400 font-bold">
                            Manager Dashboard
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Wallet Overview
                    </h1>
                </div>

                <button onclick="document.getElementById('requestModal').showModal()"
                    class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold uppercase rounded-xl transition-all shadow-lg shadow-pink-500/25 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Request Top-up
                </button>
            </div>

            {{-- Wallet Logic --}}
            @php
                $currentBalance = auth()->user()->wallet;
                $safeInflow = $totalInflow > 0 ? $totalInflow : 1;
                $remainingPercent = min(100, max(0, ($currentBalance / $safeInflow) * 100));
                $barColor =
                    $remainingPercent < 20 ? 'bg-red-500' : ($remainingPercent < 50 ? 'bg-orange-500' : 'bg-pink-500');

                // Prepare transactions (only expenses/debits)
                $sortedLogs = collect($recent)
                    ->filter(fn($item) => $item->type === 'debit')
                    ->sortByDesc('expense_date');

                // Prepare top-up requests & HR manual credits
                $topUpLogs = collect($approvedRequests)
                    ->merge($hrManualCredits ?? collect()) // If HR manual credits exist
                    ->sortByDesc('created_at');
            @endphp

            {{-- Wallet & Stats --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Current Balance Card --}}
                <div
                    class="lg:col-span-2 p-6 sm:p-8 rounded-[2rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">
                                    Current Balance
                                </p>
                                <h2
                                    class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mt-2 tracking-tighter">
                                    <span class="text-pink-500 text-2xl sm:text-3xl">Rs.</span>
                                    {{ number_format($currentBalance) }}
                                </h2>
                            </div>
                            <div class="p-3 bg-gray-100 dark:bg-white/10 rounded-2xl text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                </svg>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="mt-10">
                            <div class="flex justify-between items-end mb-3">
                                <div class="space-y-1">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Remaining
                                        Balance</p>
                                    <p class="text-xs font-bold dark:text-white">
                                        {{ number_format($currentBalance) }} <span
                                            class="text-gray-500 font-medium">left of</span>
                                        {{ number_format($totalInflow) }}
                                    </p>
                                </div>
                                <span
                                    class="text-xs font-bold {{ str_replace('bg-', 'text-', $barColor) }}">{{ round($remainingPercent) }}%</span>
                            </div>

                            <div
                                class="w-full bg-gray-100 dark:bg-white/5 h-3 rounded-full overflow-hidden border border-gray-200 dark:border-white/10">
                                <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000 ease-out relative"
                                    style="width: {{ $remainingPercent }}%">
                                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-pink-500/5 rounded-full blur-[80px]"></div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                    <div
                        class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-lg">
                                <!-- PKR Currency SVG -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 4h8a4 4 0 010 8H4v8m8-12l8 8" />
                                    <!-- This creates a P-like shape with an extra slash for R -->
                                </svg>
                            </div>
                            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Monthly Spend</p>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Rs.
                            {{ number_format($totalSpentMonth) }}</h3>
                    </div>

                    <div
                        class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-indigo-500/10 text-indigo-500 rounded-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-10.6 8.38 8.38 0 0 1 3.8.9" />
                                    <path d="M22 4l-11.5 11.5-4.5-4.5" />
                                </svg>
                            </div>
                            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">Recent Entries</p>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($recent) }}</h3>
                    </div>
                </div>
            </div>


            {{-- Chart & Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <div
                    class="lg:col-span-3 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="text-gray-400" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M3 3v18h18" />
                            <path d="m19 9-5 5-4-4-3 3" />
                        </svg>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm">Spending Velocity</h3>
                    </div>
                    <div class="h-[250px] w-full">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 flex flex-col gap-4">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm ml-2 uppercase tracking-widest">Quick
                        Actions</h3>
                    <a href="{{ route('manager.create-expense') }}"
                        class="flex-1 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-pink-500/50 transition-all group flex flex-col justify-center">
                        <div
                            class="w-12 h-12 rounded-2xl bg-pink-500/10 text-pink-500 flex items-center justify-center mb-4 group-hover:scale-110 transition shadow-inner">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" x2="12" y1="3" y2="15" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold  dark:text-white uppercase ">Submit New Expense</p>
                    </a>

                    <a href="/manager/my-expenses"
                        class="flex-1 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-indigo-500/50 transition-all group flex flex-col justify-center">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center mb-4 group-hover:scale-110 transition shadow-inner">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" x2="8" y1="13" y2="13" />
                                <line x1="16" x2="8" y1="17" y2="17" />
                            </svg>
                        </div>
                        <p class="text-xs font-bold dark:text-white uppercase tracking-tighter">View Expense History
                        </p>
                    </a>
                </div>
            </div>
            {{-- Transaction Logs Table (Only - amounts) --}}
            <div class="space-y-4 mt-8">
                <div class="flex justify-between items-center px-2">
                    <div class="flex items-center gap-2 text-gray-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 20v-6M6 20V10M18 20V4" />
                        </svg>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-widest">
                            Transaction Logs</h3>
                    </div>

                    {{-- VIEW ALL BUTTON --}}
                    <a href="{{ route('manager.expenses.history') }}"
                        class="group flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-pink-500/10 rounded-xl transition-all border border-transparent hover:border-pink-500/30">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-gray-500 group-hover:text-pink-500 transition-colors">View
                            All History</span>
                        <svg class="text-gray-400 group-hover:text-pink-500 transition-transform group-hover:translate-x-1"
                            width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="3">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div
                    class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[2rem] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr
                                    class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Title </th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Created Date
                                    </th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Amount</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Actual Date
                                    </th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px] text-right">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @forelse($sortedLogs as $item)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.03] transition-all">
                                        <td class="px-6 py-4">
                                            <span
                                                class="font-bold text-gray-900 dark:text-white">{{ $item->title }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-400 dark:text-gray-500 text-[10px] font-medium">
                                            {{ $item->created_at ? $item->created_at->format('d M, Y h:i A') : 'System N/A' }}
                                        </td>
                                        <td class="px-6 py-4 font-mono font-bold text-pink-600 dark:text-pink-400">
                                            -Rs. {{ number_format($item->amount) }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-400 dark:text-gray-500 text-[10px] font-medium">
                                            {{-- Since it's cast in the Model, just call format directly --}}
                                            {{ $item->expense_date ? $item->expense_date->format('d M, Y') : 'No Date Set' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span
                                                class="inline-flex px-2 py-1 rounded-lg text-[9px] font-bold uppercase bg-orange-500/10 text-orange-500">
                                                {{ strtoupper($item->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-xs">No
                                            transaction history.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Top-up History (Approved HR/Manager Credits) --}}
            <div class="space-y-4 mt-12">
                <div class="flex justify-between items-center px-2">
                    <div class="flex items-center gap-2 text-gray-400">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-widest">Top-up
                            History</h3>
                    </div>

                    {{-- VIEW ALL BUTTON --}}
                    <a href="{{ route('manager.topup.history') }}"
                        class="group flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-emerald-500/10 rounded-xl transition-all border border-transparent hover:border-emerald-500/30">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-gray-500 group-hover:text-emerald-500 transition-colors">View
                            Records</span>
                        <svg class="text-gray-400 group-hover:text-emerald-500 transition-transform group-hover:translate-x-1"
                            width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="3">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div
                    class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[2rem] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr
                                    class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Topup</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Date</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">Amount</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px] text-right">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @forelse($topUpLogs as $log)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.03] transition-all">
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">Wallet Top-up
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-[11px] font-medium">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M, Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                            +Rs. {{ number_format($log->amount) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span
                                                class="inline-flex px-2 py-1 rounded-lg text-[9px] font-bold uppercase bg-emerald-500 text-white">
                                                CLEARED
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-xs">No
                                            top-up records.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Request Funds Modal --}}
    <dialog id="requestModal"
        class="modal p-0 rounded-[2rem] bg-transparent backdrop:bg-black/80 backdrop:backdrop-blur-sm fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 m-0 overflow-visible">
        <div
            class="bg-white dark:bg-[#0f0f0f] w-[90vw] max-w-md p-8 border border-gray-200 dark:border-white/10 shadow-2xl rounded-[2rem]">
            <div class="flex justify-between items-center mb-6 ">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">Request Funds
                </h3>
                <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-pink-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" x2="6" y1="6" y2="18" />
                        <line x1="6" x2="18" y1="6" y2="18" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('manager.request-funds') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">Amount
                        (Rs.)</label>
                    <input type="number" name="amount" required placeholder="0.00"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent outline-none transition">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">Reason
                        / Note</label>
                    <textarea name="remarks" rows="3" placeholder="Why do you need more funds?"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent outline-none transition"></textarea>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-pink-500 hover:bg-pink-600 text-white font-bold uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-pink-500/30 transition-all active:scale-95">
                    Submit Request
                </button>
            </form>
        </div>
    </dialog>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0,0,0,0.05)';
            const ctx = document.getElementById('expenseChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar', // Changed from 'line' to 'bar'
                data: {
                    labels: @json($months),
                    datasets: [{
                        data: @json($chartData),
                        backgroundColor: '#ec4899', // Pink-500
                        hoverBackgroundColor: '#db2777', // Pink-600 on hover
                        borderRadius: 8, // Rounded tops for bars
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDark ? '#1f2937' : '#ffffff',
                            titleColor: isDark ? '#ffffff' : '#111827',
                            bodyColor: isDark ? '#ffffff' : '#111827',
                            borderColor: 'rgba(236, 72, 153, 0.3)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 10,
                                    family: 'Inter, sans-serif'
                                },
                                // Optional: adds a currency prefix to the Y axis
                                callback: function(value) {
                                    return 'Rs. ' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 10,
                                    family: 'Inter, sans-serif'
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .modal::backdrop {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</x-app-layout>
