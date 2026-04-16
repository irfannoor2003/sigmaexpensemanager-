<x-app-layout>
    {{-- Floating Back Button --}}
    <a href="{{ route('hr.dashboard') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
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
                            {{__('app.Sigma Archive')}}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{__('app.Master Expense Ledger')}}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{__('app.Reviewing_historic')}}</p>
                </div>

                {{-- Filter & Export Bar --}}
                <div class="flex flex-col sm:flex-row w-full sm:w-auto gap-2 ">


                    {{-- Unified Filter Form --}}
                    <form action="{{ route('hr.expenses.history') }}" method="GET"
                        class="flex items-center gap-1 bg-white dark:bg-white/5 px-0 py-2 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">

                        {{-- Month Picker Group --}}
                        <div class="flex items-center gap-0 pl-2  border-r border-gray-100 dark:border-white/10">
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <line x1="16" x2="16" y1="2" y2="6" />
                                    <line x1="8" x2="8" y1="2" y2="6" />
                                    <line x1="3" x2="21" y1="10" y2="10" />
                                </svg>
                            </div>
                            <div class="input-wrapper">
                                <input type="month" name="month"
                                    value="{{ request('month', now()->format('Y-m')) }}" onclick="this.showPicker()"
                                    class="no-calendar-icon bg-transparent border-none text-xs font-bold text-gray-700 dark:text-gray-300 focus:ring-0 cursor-pointer p-1  " style="width: 120px">
                            </div>
                        </div>

                        {{-- Category Dropdown --}}
                       {{-- Category Dropdown --}}
<div class="flex items-center border-r border-gray-100 dark:border-white/10 pr-2">
    <select name="category_id"
        class="bg-transparent border-none text-xs font-bold text-gray-700 dark:text-gray-300 focus:ring-0 cursor-pointer py-0 w-[140px]">

        <option value="">{{ __('app.All Categories') }}</option>

        @foreach ($categories as $cat)
            @php
                $map = [
                    'Bilty' => 'Bilty',
                    'Cash' => 'Cash',
                    'Office Supplies/Expenses' => 'Office Supplies/Expenses',
                    'Mobile Load' => 'Mobile Load',
                    'Food/Entertainment' => 'Food/Entertainment',
                    'Mis Salary' => 'Mis Salary',
                    'Parking' => 'Parking',
                    'Water Bottle Refill' => 'Water Bottle Refill',
                    'Miscellaneous' => 'Miscellaneous',
                    'Freight Out' => 'Freight Out',
                ];

                $langKey = $map[$cat->name] ?? $cat->name;
            @endphp

            <option value="{{ $cat->id }}"
                {{ request('category_id') == $cat->id ? 'selected' : '' }}>

                {{ __('app.categories.' . $langKey) }}
            </option>
        @endforeach

    </select>
</div>

                        <button type="submit"
                            class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-3 md:px-4 py-2 rounded-lg text-[9px] font-black uppercase transition-all hover:scale-[1.02] active:scale-95 mr-2">
                            {{__('app.FILTER')}}
                        </button>

                        @if (request()->hasAny(['month', 'category_id']))
                            <a href="{{ route('hr.expenses.history') }}"
                                class="pr-2 text-gray-400 hover:text-red-500 transition-colors" title="Clear All">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </a>
                        @endif
                    </form>


                    {{-- Export Form --}}
                    <form action="{{ route('hr.export') }}" method="GET" id="exportForm">
                        {{-- These will stay in sync with the filter bar via JS --}}
                        <input type="hidden" name="month" id="export_month"
                            value="{{ request('month', now()->format('Y-m')) }}">
                        <input type="hidden" name="category_id" id="export_category"
                            value="{{ request('category_id') }}">

                        <button type="submit"
                            class="flex items-center justify-center gap-2 bg-pink-500 hover:bg-pink-600 text-white w-full sm:w-auto px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all shadow-lg shadow-pink-500/20 active:scale-95 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" x2="12" y1="15" y2="3" />
                            </svg>
                            {{__('app.Export')}}
                        </button>
                    </form>

                </div>
            </div>

            {{-- Summary Stats (Optional but Professional) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">

    {{-- LEFT SIDE: CARDS --}}
    <div class="flex flex-col gap-6 lg:col-span-1 h-full">

        {{-- Total Amount --}}
        <div
            class="p-4 sm:p-6 rounded-2xl sm:rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-between shadow-sm h-full">

            <div>
                <p class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">
                    {{__('app.Total Filtered Amount')}}
                </p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    {{__('app.Rs')}} {{ number_format($expenses->sum('amount')) }}
                </h3>
            </div>

            <div
                class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-pink-500/10 flex items-center justify-center text-pink-500">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
            </div>
        </div>

        {{-- Total Records --}}
        <div
            class="p-4 sm:p-6 rounded-2xl sm:rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-between shadow-sm h-full">

            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400">
                    {{__('app.Total Record Count')}}
                </p>
                <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mt-1">
                    {{ $expenses->total() }} {{__('app.Transaction')}}
                </h3>
            </div>

            <div
                class="h-10 w-10 sm:h-12 sm:w-12 rounded-xl sm:rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>

    </div>

    {{-- RIGHT SIDE: CHART --}}
    <div
        class="lg:col-span-2 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm flex h-full flex-col gap-3 md:flex-row">

        <!-- LEFT SIDE -->
        <div class="flex-1 flex flex-col justify-between space-y-3">

            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                    {{__('app.Distribution')}}
                </p>
                <span class="h-2 w-2 rounded-full bg-pink-500"></span>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar pr-2">

    @php
        $totalAmount = array_sum($values->toArray());
        $palette = ['#ea258e', '#7c3aed', '#3b82f6', '#10b981', '#f59e0b'];

        // Category translation map (DB → lang key)
        $map = [
            'Bilty' => 'Bilty',
            'Cash' => 'Cash',
            'Office Supplies/Expenses' => 'Office Supplies/Expenses',
            'Mobile Load' => 'Mobile Load',
            'Food/Entertainment' => 'Food/Entertainment',
            'Mis Salary' => 'Mis Salary',
            'Parking' => 'Parking',
            'Water Bottle Refill' => 'Water Bottle Refill',
            'Miscellaneous' => 'Miscellaneous',
            'Freight Out' => 'Freight Out',
        ];
    @endphp

    @foreach ($labels as $index => $name)
        @php
            $percentage = $totalAmount > 0 ? round(($values[$index] / $totalAmount) * 100) : 0;
            $color = $palette[$index % count($palette)];

            // translate label
            $langKey = $map[$name] ?? $name;
        @endphp

        <div
            class="flex justify-between items-center text-xs px-2 py-1 rounded-xl transition hover:bg-gray-100 dark:hover:bg-white/5">

            {{-- LEFT SIDE (LABEL) --}}
            <span class="flex items-center gap-2 font-medium text-gray-600 dark:text-gray-300">
                <i class="w-2.5 h-2.5 rounded-full shadow-sm"
                    style="background-color: {{ $color }}"></i>

                {{ __('app.categories.' . $langKey) }}
            </span>

            {{-- RIGHT SIDE (PERCENTAGE) --}}
            <span class="font-mono font-bold text-[11px] tracking-wide"
                style="color: {{ $color }}">
                {{ $percentage }}%
            </span>

        </div>
    @endforeach

</div>

        </div>

        <!-- RIGHT SIDE (CHART) -->
        <div class="w-40 flex items-center justify-center">
            <div class="relative w-36 h-36">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

    </div>

</div>

            {{-- Master Table Container --}}
            <div
                class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[3rem] overflow-hidden shadow-2xl backdrop-blur-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr
                                class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">
                                    {{__('app.User / Role')}}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">
                                    {{__('app.Transaction Details')}}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">
                                    {{__('app.Expense Date')}}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">
                                    {{__('app.Expense Category')}}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">
                                    {{__('app.AMOUNT')}}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px] text-gray-400">
                                    {{__('app.STATUS')}}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($expenses as $item)
                                <tr class="group hover:bg-pink-500/[0.03] transition-all duration-300">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">

                                            {{-- Expense Image --}}
                                            <div class="group relative w-10 h-10 rounded-2xl overflow-hidden bg-gray-100 dark:bg-white/10 flex items-center justify-center shadow-sm cursor-pointer"
                                                onclick="showReceipt('{{ asset('storage/' . $item->image) }}')">

                                                @if ($item->image)
                                                    <img src="{{ asset('storage/' . $item->image) }}" alt="Receipt"
                                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                                @else
                                                    <span class="font-black text-gray-400 text-xs">N/A</span>
                                                @endif

                                                {{-- Hover Overlay --}}
                                                <div
                                                    class="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-100 transition">
                                                </div>
                                            </div>

                                            {{-- User Info --}}
                                            <div class="flex flex-col">
                                                <span class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $item->user->display_name }}
                                                </span>

                                            </div>

                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $item->title }}</span>

                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->expense_date->format('D, d M Y') }}</span>

                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
    <div class="flex flex-col">
        @php
            $map = [
                'Bilty' => 'Bilty',
                'Cash' => 'Cash',
                'Office Supplies/Expenses' => 'Office Supplies/Expenses',
                'Mobile Load' => 'Mobile Load',
                'Food/Entertainment' => 'Food/Entertainment',
                'Mis Salary' => 'Mis Salary',
                'Parking' => 'Parking',
                'Water Bottle Refill' => 'Water Bottle Refill',
                'Miscellaneous' => 'Miscellaneous',
                'Freight Out' => 'Freight Out',
            ];

            $name = $item->category->name ?? 'General';
            $langKey = $map[$name] ?? $name;
        @endphp

        <span class="text-sm font-semibold text-gray-900 dark:text-white">
            {{ __('app.categories.' . $langKey) }}
        </span>
    </div>
</td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-mono font-bold text-pink-600 dark:text-pink-400">
                                            -{{__('app.Rs')}} {{ number_format($item->amount) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
    @php
        $status = strtolower($item->status);

        $statusClasses = [
            'approved' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
            'pending' => 'bg-orange-500/10 text-orange-500 border-orange-500/20',
            'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
        ];
    @endphp

    <span
        class="inline-flex px-4 py-2 rounded-2xl text-[10px] font-bold uppercase tracking-wider border {{ $statusClasses[$status] ?? 'bg-gray-500/10 text-gray-500 border-gray-500/20' }}">

        {{ __('app.status.' . $status) }}
    </span>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-width="1.5"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                            <p
                                                class="text-xs font-bold uppercase tracking-widest text-gray-400 text-gray-500">
                                                No Historical Records Found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Custom Styled Pagination Footer --}}
                @if ($expenses->hasPages())
                    <div
                        class="px-8 py-6 bg-gray-50/50 dark:bg-white/[0.02] border-t border-gray-100 dark:border-white/5">
                        {{ $expenses->links() }}
                    </div>
                @endif
            </div>

            <div class="h-10"></div> {{-- Spacer --}}
        </div>
    </div>
    <div id="receiptModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-[#050505]/95 p-6 backdrop-blur-2xl transition-all duration-300"
        onclick="closeReceipt()">

        <div class="relative max-w-4xl w-full h-full flex flex-col items-center justify-center"
            onclick="event.stopPropagation()">

            {{-- Close Button --}}
            <div class="absolute top-6 right-6 group cursor-pointer z-10" onclick="closeReceipt()">
                <div
                    class="p-3 bg-white/5 border border-white/10 rounded-2xl text-white group-hover:bg-pink-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </div>
            </div>

            {{-- Image --}}
            <img id="modalImg" src=""
                class="max-w-full max-h-[80vh] rounded-[2rem] shadow-[0_0_100px_rgba(234,37,142,0.2)] border border-white/10 object-contain transition-all duration-500 scale-90 opacity-0">

            <p class="text-white font-bold uppercase tracking-[0.5em] text-[10px] mt-6 opacity-40">
                Click outside or press ESC
            </p>

        </div>
    </div>

    <style>
        /* Smooth Scrollbar for Table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: rgba(236, 72, 153, 0.1);
            border-radius: 10px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: rgba(236, 72, 153, 0.3);
        }
    </style>
    <style>
        /* 1. Hide the default icon */
        .no-calendar-icon::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }

        /* 2. Ensure the input wrapper allows clicking through */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. The Filter Inputs (What the user touches)
            const filterMonth = document.querySelector('input[name="month"]');
            const filterCategory = document.querySelector('select[name="category_id"]');

            // 2. The Export Inputs (What gets sent to the Excel file)
            const exportMonth = document.getElementById('export_month');
            const exportCategory = document.getElementById('export_category');

            // Function to copy values from Filter to Export
            const syncValues = () => {
                if (exportMonth && filterMonth) exportMonth.value = filterMonth.value;
                if (exportCategory && filterCategory) exportCategory.value = filterCategory.value;
            };

            // Update values whenever the user changes a filter
            filterMonth?.addEventListener('change', syncValues);
            filterCategory?.addEventListener('change', syncValues);

            // Run once on load to ensure initial state is correct
            syncValues();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('categoryChart').getContext('2d');
            const sigmaPalette = ['#ea258e', '#7c3aed', '#3b82f6', '#10b981', '#f59e0b'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        data: @json($values),
                        backgroundColor: sigmaPalette,
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%', // Thin elegant ring
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#111',
                            titleFont: {
                                size: 10
                            },
                            bodyFont: {
                                size: 10
                            },
                            callbacks: {
                                label: (ctx) => ` Rs. ${new Intl.NumberFormat().format(ctx.raw)}`
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script>
        const modal = document.getElementById('receiptModal');
        const modalImg = document.getElementById('modalImg');

        function showReceipt(url) {
            modalImg.classList.remove('scale-100', 'opacity-100'); // reset
            modalImg.src = url;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.style.overflow = 'hidden';

            // Smooth animation trigger
            setTimeout(() => {
                modalImg.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeReceipt() {
            modalImg.classList.remove('scale-100', 'opacity-100');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                modalImg.src = "";
                document.body.style.overflow = 'auto';
            }, 200);
        }

        // ESC key support
        document.addEventListener('keydown', function(e) {
            if (e.key === "Escape") {
                closeReceipt();
            }
        });
    </script>
</x-app-layout>
