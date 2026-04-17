<x-app-layout>
    <x-toaster />

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Back Button (minimal style) --}}
    <a href="{{ route('manager.dashboard') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8  min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                        <span
                            class="text-[10px] uppercase tracking-widest text-pink-600 dark:text-pink-400 font-bold">{{__('app.Analytics & Log')}}</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{__('app.Expense Overview')}}</h1>
                </div>

                {{-- Advanced Filter Tool --}}
                <form method="GET"
                    class="group flex items-center gap-2 bg-white/80 dark:bg-neutral-950/50 p-1.5 pl-5 rounded-xl border border-gray-200 dark:border-white/10 shadow-2xl backdrop-blur-xl transition-all hover:border-pink-500/30">

                    <div class="flex flex-col justify-center">
                        <label
                            class="text-[8px] font-bold uppercase tracking-[0.2em] text-pink-500 mb-0.5 ml-0.5 opacity-80">
                            {{__('app.SELECT_PERIOD')}}
                        </label>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-gray-400 dark:text-gray-500"
                                width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                            </svg>
                            <input type="month" name="month" value="{{ request('month', date('Y-m')) }}"
                                class="bg-transparent border-none p-0 text-[11px] font-bold dark:text-white focus:ring-0 uppercase tracking-tighter cursor-pointer appearance-none">
                        </div>
                    </div>

                    <div class="h-8 w-[1px] bg-gray-200 dark:bg-white/10 mx-2"></div>

                    <button type="submit"
                        class="relative overflow-hidden flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-7 py-3.5 rounded-xl text-[10px] font-bold uppercase tracking-[0.15em] transition-all duration-300 hover:shadow-pink-500/40 hover:-translate-y-0.5 active:scale-95 shadow-lg shadow-pink-500/20">

                        <span
                            class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></span>

                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="relative z-10 group-hover:rotate-180 transition-transform duration-500"
                            width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 2v6h-6M3 12a9 9 0 0 1 15-6.7L21 8M3 22v-6h6M21 12a9 9 0 0 1-15 6.7L3 16" />
                        </svg>

                        <span class="relative z-10">{{__('app.SYNC')}}</span>
                    </button>
                </form>




            </div>

            {{-- Stats Stack (Converted from Summary Layout) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">

                <div
                    class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-pink-500/10 text-pink-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">{{__('app.TOTAL LOGS')}}</p>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                         {{ $totalLogs }}
                        <span class="text-xs font-medium text-gray-400 ml-1">{{__('app.Bills')}}</span>
                    </h3>
                </div>

                <div
                    class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-rose-500/10 text-rose-500 rounded-lg">
    <svg class="w-4 h-4" viewBox="0 0 122.88 98.34" fill="currentColor">
        <path fill-rule="evenodd" d="M.71,24.6H8.8V0H40q14.7,0,22.74,7.64c4.4,4.19,7,9.86,7.77,17H81.81a.73.73,0,0,1,.71.72v11a.73.73,0,0,1-.71.72H70.15Q67.79,50.36,56.74,56.45L73.72,96V97h-21L38.1,61.65H28.37V97H8.8V37.07H.71A.73.73,0,0,1,0,36.35v-11a.71.71,0,0,1,.71-.72ZM107.86,81.4A4.84,4.84,0,0,0,106,77.76c-1.26-1.12-4.06-2.61-8.39-4.52q-9.57-3.88-13.16-8a15.28,15.28,0,0,1-3.58-10.33,16.56,16.56,0,0,1,5.62-12.82Q92.06,37,101.29,37c6.5,0,11.68,1.68,15.58,5s5.86,7.85,5.86,13.49H107.26q0-7.22-6-7.22a5.54,5.54,0,0,0-4,1.53,5.67,5.67,0,0,0-1.57,4.27,4.52,4.52,0,0,0,1.73,3.47c1.18,1,3.92,2.45,8.27,4.29,6.31,2.34,10.75,5,13.36,7.8s3.9,6.57,3.9,11.12a15.56,15.56,0,0,1-5.95,12.75c-4,3.21-9.18,4.81-15.64,4.81A24.81,24.81,0,0,1,89.82,95.8,19.2,19.2,0,0,1,82,88.71a18.23,18.23,0,0,1-2.8-9.77h14.7c.08,2.68.69,4.72,1.89,6.06s3.11,2,5.82,2c4.14,0,6.23-1.88,6.23-5.63ZM28.37,24.6H50.51q-2.13-8.24-10.68-8.23H28.37V24.6ZM50.42,37.07h-22v8.21H39.49q5.86,0,8.8-3.88a13.79,13.79,0,0,0,2.13-4.33Z"/>
    </svg>
</div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">{{__('app.TOTAL SPENDING')}}</p>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <span class="text-lg font-semibold text-gray-400">{{__('app.Rs')}}</span>
                        {{ number_format($totalSpend) }}
                    </h3>
                </div>

                <div
                    class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-500/10 text-blue-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21.21 15.89A10 10 0 1 1 8 2.83" />
                                <path d="M22 12A10 10 0 0 0 12 2v10z" />
                            </svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">{{__('app.UNIT AVERAGE')}}</p>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <span class="text-lg font-semibold text-gray-400">{{__('app.Rs')}}</span>
                      {{ $totalLogs > 0 ? number_format($totalSpend / $totalLogs) : 0 }}
                    </h3>
                </div>

                <div
                    class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-pink-500/10 text-pink-500 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                <polyline points="17 6 23 6 23 12" />
                            </svg>
                        </div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">{{__('app.STABILITY')}}</p>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white uppercase">
                        {{__('app.Healthy')}}
                    </h3>
                </div>

            </div>

            {{-- Modernized Visual Graph Card --}}
            <div
                class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl p-7 shadow-sm">

                {{-- Structured Header --}}
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-white/5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">{{__('app.Spending Chart')}}</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{__('app.Spending over')}}
                        </p>
                    </div>

                    {{-- Elegant, Subtle Badge instead of the top-right text --}}
                    <div
                        class="flex items-center gap-2 px-3 py-1 rounded-full bg-pink-500/10 text-pink-600 dark:text-pink-400">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-pink-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-pink-500"></span>
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider">{{__('app.LIVE VIEW')}}</span>
                    </div>
                </div>

                {{-- Chart Container --}}
                <div class="h-[300px] w-full">
                    <canvas id="spendingChart"></canvas>
                </div>
            </div>

            {{-- Log Grid --}}
            <div class="space-y-3">
                @forelse($expenses as $expense)
                    <div
                        class="group bg-white dark:bg-white/5 border border-gray-100 dark:border-white/10 rounded-2xl p-4 hover:shadow-lg hover:border-pink-500/30 transition-all duration-300">
                        <div class="flex items-center gap-4">

                            {{-- Compact Icon/Image Preview --}}
                            <div class="relative flex-shrink-0 w-12 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-black/20 cursor-pointer"
                                onclick="showReceipt('{{ asset('storage/' . $expense->image) }}')">
                                <img src="{{ asset('storage/' . $expense->image) }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors">
                                </div>
                            </div>

                            {{-- Primary Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-0.5">
    <h3 class="font-bold text-gray-900 dark:text-white truncate tracking-tight">
        {{ $expense->title }}
    </h3>

    <span class="px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider
        @if ($expense->status == 'approved') bg-emerald-500/10 text-emerald-600
        @elseif($expense->status == 'rejected') bg-rose-500/10 text-rose-600
        @else bg-amber-500/10 text-amber-600 @endif">

        {{ __('app.status.' . $expense->status) }}
    </span>
</div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                                    {{ $expense->description ?? 'No supplemental notes logged.' }}
                                </p>
                            </div>

                            {{-- Meta Info (Hidden on mobile, shown on md+) --}}
                            <div
                                class="hidden md:flex flex-col items-end gap-1 px-4 border-r border-gray-100 dark:border-white/5">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">
                                    {{ $expense->expense_date->format('M d, Y') }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-mono">
                                    #{{ str_pad($expense->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>

                            {{-- Amount & Action --}}
                            <div class="flex items-center gap-3 ml-4">

    {{-- Amount --}}
    <div class="text-right">
        <p class="text-lg font-bold text-gray-900 dark:text-white tracking-tight">
            <span class="text-xs font-medium text-pink-500">Rs</span>{{ number_format($expense->amount) }}
        </p>
    </div>

    {{-- VIEW RECEIPT --}}
    <button onclick="showReceipt('{{ asset('storage/' . $expense->image) }}')"
        class="p-2 rounded-lg bg-gray-50 dark:bg-white/5 text-gray-400 hover:text-pink-500 hover:bg-pink-500/10 transition-all cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg"  class="w-5 h-5" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.--><path d="M128 96C110.3 96 96 110.3 96 128L96 224C96 241.7 110.3 256 128 256C145.7 256 160 241.7 160 224L160 160L224 160C241.7 160 256 145.7 256 128C256 110.3 241.7 96 224 96L128 96zM160 416C160 398.3 145.7 384 128 384C110.3 384 96 398.3 96 416L96 512C96 529.7 110.3 544 128 544L224 544C241.7 544 256 529.7 256 512C256 494.3 241.7 480 224 480L160 480L160 416zM416 96C398.3 96 384 110.3 384 128C384 145.7 398.3 160 416 160L480 160L480 224C480 241.7 494.3 256 512 256C529.7 256 544 241.7 544 224L544 128C544 110.3 529.7 96 512 96L416 96zM544 416C544 398.3 529.7 384 512 384C494.3 384 480 398.3 480 416L480 480L416 480C398.3 480 384 494.3 384 512C384 529.7 398.3 544 416 544L512 544C529.7 544 544 529.7 544 512L544 416z"/></svg>
    </button>

    {{-- ✏️ EDIT BUTTON --}}
    <a href="{{ route('manager.expense.edit', $expense->id) }}"

        class="p-2 rounded-lg bg-gray-50 dark:bg-white/5 text-gray-400 hover:text-blue-500 hover:bg-blue-500/10 transition-all">

        <!-- Pencil Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5h-6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-6M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
        </svg>

    </a>

</div>
                        </div>
                    </div>
                @empty
                    <div
                        class="py-20 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 dark:border-white/5 rounded-[2rem]">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.3em]">Ledger Empty</p>
                    </div>
                @endforelse
            </div>
            {{-- 4. Footer Pagination --}}
            @if (method_exists($expenses, 'links'))
                <div class="mt-8 pb-12">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Premium Receipt Modal --}}
    <div id="receiptModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#050505]/95 p-6 backdrop-blur-2xl transition-all duration-500"
        onclick="closeReceipt()">
        <div class="relative max-w-4xl w-full h-full flex flex-col items-center justify-center">
            <div class="absolute top-10 right-0 group cursor-pointer" onclick="closeReceipt()">
                <div
                    class="p-3 bg-white/5 border border-white/10 rounded-2xl text-white group-hover:bg-pink-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>
            <img id="modalImg" src=""
                class="max-w-full max-h-[80vh] rounded-[2rem] shadow-[0_0_100px_rgba(234,37,142,0.2)] border border-white/10 object-contain transition-all duration-700 scale-95 opacity-0"
                onload="this.classList.add('scale-100', 'opacity-100')">
            <p class="text-white font-bold uppercase tracking-[0.5em] text-[10px] mt-8 opacity-40">{{__('app.Click dismiss')}}</p>
        </div>
    </div>

    <script>
        function showReceipt(url) {
            const modal = document.getElementById('receiptModal');
            document.getElementById('modalImg').src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeReceipt() {
            const modal = document.getElementById('receiptModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            document.getElementById('modalImg').classList.remove('scale-100', 'opacity-100');
        }

        {{-- Enhanced Bar Graph Script --}}
        const ctx = document.getElementById('spendingChart').getContext('2d');

        // Create a vertical gradient for the bars
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, '#10b981'); // pink-500
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.2)'); // Faded pink

        new Chart(ctx, {
            type: 'bar', // Changed from 'line' to 'bar'
            data: {
                labels: {!! json_encode($expenses->pluck('expense_date')->map(fn($d) => $d->format('d M'))->toArray()) !!},
                datasets: [{
                    label: 'Spent',
                    data: {!! json_encode($expenses->pluck('amount')->toArray()) !!},
                    backgroundColor: gradient,
                    hoverBackgroundColor: '#059669', // Darker pink on hover
                    borderRadius: 8, // Gives the bars rounded tops
                    borderSkipped: false, // Ensures all corners can be rounded
                    maxBarThickness: 32, // Prevents bars from becoming giant on large screens
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
                        backgroundColor: '#1f2937',
                        padding: 12,
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 14
                        },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rs. ' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false, // Keep the clean "no vertical lines" look
                            drawBorder: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                family: 'Inter',
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)', // Subtle horizontal lines
                            drawBorder: false
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 11
                            },
                            padding: 10,
                            callback: function(value) {
                                if (value >= 1000) return 'Rs. ' + value / 1000 + 'k';
                                return 'Rs. ' + value;
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
