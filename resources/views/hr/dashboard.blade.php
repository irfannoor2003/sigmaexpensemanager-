<x-app-layout>
    <x-toaster />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="p-4 sm:p-8  min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Dynamic Header --}}
            <div class="mb-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span
                            class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">System
                            Live</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Expense Oversight</h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Sigma Engineering Services • Real-time Expense
                        Control</p>
                </div>

                <div class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center gap-4 w-full">
                    @if ($deadline)
                        <div
                            class="flex items-center gap-2 px-3 py-2 rounded-xl bg-indigo-500/10 border border-indigo-500/20 w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                                stroke="currentColor" stroke-width="2" class="text-indigo-500">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>

                            <span class="text-[10px] font-bold text-indigo-600 uppercase tracking-wider">
                                Deadline: {{ \Carbon\Carbon::parse($deadline)->format('d M Y') }}
                            </span>
                        </div>
                    @endif

                    {{-- Grace Period Deadline Control --}}
                    <form action="{{ route('hr.extend-deadline') }}" method="POST"
                        class="flex items-center gap-2 bg-white dark:bg-white/5 p-1.5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm w-full sm:w-auto flex-1">
                        @csrf
                        <div class="pl-2 text-gray-400" title="Set Wind-up Deadline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </div>
                        <input type="date" name="deadline" required
                            class="bg-transparent border-none text-xs font-semibold dark:text-gray-300 focus:ring-0 cursor-pointer flex-1">
                        <button type="submit"
                            class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-2 rounded-lg text-[10px] font-bold uppercase transition-all shadow-lg shadow-indigo-500/20">
                            Lock Date
                        </button>
                    </form>

                    {{-- Export Form --}}
                    <form action="{{ route('hr.export') }}" method="GET"
                        class="flex items-center gap-2 bg-white dark:bg-white/5 p-1.5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm w-full sm:w-auto flex-1">
                        <div class="pl-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                            </svg>
                        </div>
                        <input type="month" name="month" value="{{ now()->format('Y-m') }}"
                            class="bg-transparent border-none text-xs font-semibold dark:text-gray-300 focus:ring-0 flex-1">
                        <button type="submit"
                            class="flex items-center gap-2 bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg text-xs font-bold transition-all shadow-lg shadow-pink-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" x2="12" y1="15" y2="3" />
                            </svg>
                            Export
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div x-data="{ view: 'month' }" class="space-y-6 ">

               <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4 mb-4">

    {{-- View History Button --}}
    <a href="{{ route('hr.financial-history') }}"
        class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl text-[10px] font-bold uppercase tracking-widest text-gray-600 dark:text-gray-400 hover:text-pink-500 transition-all shadow-sm group">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
            stroke-linejoin="round" class="group-hover:scale-110 transition-transform">
            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
            <path d="M3 3v5h5" />
            <path d="m12 7v5l4 2" />
        </svg>
        Full Financial History
    </a>

    {{-- Month/Year Toggle --}}
    <div
        class="w-full md:w-auto inline-flex justify-center p-1 bg-gray-100 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl shadow-sm">

        <button type="button" @click="view = 'month'"
            :class="view === 'month'
                ? 'bg-white dark:bg-white/10 shadow-sm text-gray-900 dark:text-white'
                : 'text-gray-500 hover:text-gray-700'"
            class="w-1/2 md:w-auto px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all cursor-pointer">
            Monthly
        </button>

        <button type="button" @click="view = 'year'"
            :class="view === 'year'
                ? 'bg-white dark:bg-white/10 shadow-sm text-gray-900 dark:text-white'
                : 'text-gray-500 hover:text-gray-700'"
            class="w-1/2 md:w-auto px-4 py-1.5 text-[10px] font-bold uppercase tracking-widest rounded-lg transition-all cursor-pointer">
            Yearly
        </button>

    </div>
</div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Total Approved Card --}}
                    <div
                        class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">
                                    Total Amount Approved
                                </p>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2"
                                    x-show="view === 'month'">
                                    Rs. {{ number_format($totalAddedMonth) }}
                                </h2>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2"
                                    x-show="view === 'year'" x-cloak>
                                    Rs. {{ number_format($totalAddedYear) }}
                                </h2>
                            </div>
                            <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="14" x="2" y="5" rx="2" />
                                    <line x1="2" x2="22" y1="10" y2="10" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 inline-flex items-center text-[10px] text-emerald-500 font-bold uppercase">
                            <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="18 15 12 9 6 15" />
                            </svg>
                            <span x-text="view === 'month' ? 'Approved (Month)' : 'Approved (Year)'"></span>
                        </div>
                    </div>

                    {{-- Total Spent Card --}}
                    <div
                        class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p
                                    class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">
                                    Total Spent
                                </p>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2"
                                    x-show="view === 'month'">
                                    Rs. {{ number_format($totalSpentMonth) }}
                                </h2>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mt-2"
                                    x-show="view === 'year'" x-cloak>
                                    Rs. {{ number_format($totalSpentYear) }}
                                </h2>
                            </div>
                            <div class="p-3 bg-pink-500/10 text-pink-500 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1" />
                                    <path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 inline-flex items-center text-[10px] text-pink-500 font-bold uppercase">
                            <svg class="mr-1" xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                            <span x-text="view === 'month' ? 'Spent (Month)' : 'Spent (Year)'"></span>
                        </div>
                    </div>

                    {{-- Pending Requests Card --}}
                    <div class="p-6 rounded-3xl bg-pink-500 shadow-lg shadow-pink-500/20">
                        <div class="flex justify-between items-start text-white">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest text-white/80 font-bold">Awaiting
                                    Funding</p>
                                <h2 class="text-3xl font-bold mt-2">{{ $moneyRequests->count() }} Requests</h2>
                            </div>
                            <div class="p-3 bg-white/20 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                    <line x1="12" x2="12" y1="9" y2="13" />
                                    <line x1="12" x2="12.01" y1="17" y2="17" />
                                </svg>
                            </div>
                        </div>
                        <div
                            class="mt-3 inline-flex items-center text-[10px] text-white font-bold uppercase bg-white/20 px-2 py-0.5 rounded-full">
                            Action Required
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                {{-- Analytics Section --}}
                <div
                    class="lg:col-span-3 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                <line x1="12" y1="20" x2="12" y2="10" />
                                <line x1="18" y1="20" x2="18" y2="4" />
                                <line x1="6" y1="20" x2="6" y2="16" />
                            </svg>
                            <h3 class="font-bold text-gray-900 dark:text-white">Financial Flow</h3>
                        </div>

                        {{-- Filters --}}
                        <div class="flex bg-gray-100 dark:bg-white/5 p-1 rounded-xl">
                            @foreach (['7days' => '7D', 'lastMonth' => '1M', 'last3Months' => '3M', 'lastYear' => '1Y'] as $key => $label)
                                <a href="{{ route('hr.dashboard', ['filter' => $key]) }}"
                                    class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase transition-all
                          {{ $filter === $key ? 'bg-white dark:bg-white/10 text-pink-500 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-white' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- The Chart Canvas --}}
                    <div class="relative h-[300px] w-full">
                        <canvas id="financeChart"></canvas>
                    </div>
                </div>

                {{-- Live Wallets --}}
                <div
                    class="lg:col-span-2 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center gap-2">
                            <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18"
                                height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            <h3 class="font-bold text-gray-900 dark:text-white">Total Managers</h3>
                        </div>
                        <a href="{{ route('hr.credit') }}"
                            class="p-2 bg-pink-500/10 text-pink-500 rounded-xl hover:bg-pink-500 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </a>
                    </div>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                        @foreach ($managers as $manager)
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-white/[0.02] rounded-2xl border border-transparent hover:border-gray-200 dark:hover:border-white/10 transition-all">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-500 flex items-center justify-center text-[10px] font-bold">
                                        {{ strtoupper(substr($manager->name, 0, 2)) }}
                                    </div>
                                    <span
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-200">{{ $manager->name }}</span>
                                </div>
                                <span class="text-sm font-mono font-bold text-pink-600 dark:text-pink-400">Rs.
                                    {{ number_format($manager->wallet) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Fund Requests Section --}}
            <section class="space-y-4">
                <div class="flex items-center gap-2 ml-2">
                    <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="m22 2-7 20-4-9-9-4Z" />
                        <path d="M22 2 11 13" />
                    </svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Incoming Requests</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($moneyRequests as $req)
                        <div
                            class="p-6 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl shadow-sm hover:shadow-md transition-all">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                                        <path d="M12 18V6" />
                                    </svg>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">Rs.
                                        {{ number_format($req->amount) }}</p>
                                    <p class="text-[10px] font-bold uppercase text-gray-400 tracking-wider">
                                        {{ $req->user->name }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 italic line-clamp-2">
                                "{{ $req->reason }}"</p>
                            <div class="grid grid-cols-2 gap-2">
                                <form action="{{ route('hr.approve-request', $req->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="w-full py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-bold uppercase rounded-xl transition-all flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12" />
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('hr.reject-request', $req->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="w-full py-2 bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-300 text-[10px] font-bold uppercase rounded-xl hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" x2="6" y1="6" y2="18" />
                                            <line x1="6" x2="18" y1="6" y2="18" />
                                        </svg>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div
                            class="col-span-full p-12 text-center border border-dashed border-gray-200 dark:border-white/10 rounded-3xl">
                            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">No pending requests.
                            </p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Audit Logs --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between ml-2">

                    {{-- Left: Title --}}
                    <div class="flex items-center gap-2">
                        <svg class="text-gray-400" xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12" />
                        </svg>
                        <h3 class="font-bold text-gray-900 dark:text-white">Activity Logs</h3>
                    </div>

                    {{-- Right: View All Button --}}
                    <a href="{{ route('hr.expenses.history') }}"
                        class="group flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-pink-500/10 rounded-xl transition-all border border-transparent hover:border-pink-500/30">

                        <span
                            class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-hover:text-pink-500 transition-colors">
                            View All History
                        </span>

                        <svg class="text-gray-400 group-hover:text-pink-500 transition-transform group-hover:translate-x-1"
                            width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="3">
                            <path d="M5 12h14m-7-7 7 7-7 7" />
                        </svg>
                    </a>

                </div>
                <div
                    class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-3xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr
                                    class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">Username
                                    </th>
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">Title</th>
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">Amount</th>
                                    {{-- New Date Column Header --}}
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">Date Of
                                        Created </th>
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px] text-right">
                                        Verification</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @foreach ($liveLogs as $log)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.03] transition-all">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span
                                                    class="font-semibold text-gray-900 dark:text-white">{{ $log->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $log->title }}
                                        </td>
                                        <td class="px-6 py-4 font-mono font-bold text-pink-600 dark:text-pink-400">
                                            Rs. {{ number_format($log->amount) }}
                                        </td>

                                        {{-- New Date Column Data --}}
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-gray-900 dark:text-gray-200 font-medium">
                                                    {{ $log->created_at->format('d M, Y') }}
                                                </span>

                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            {{-- Dynamic Status Colors --}}
                                            <span
                                                class="inline-flex items-center gap-1 px-2 py-1 rounded-full uppercase text-[10px] font-bold
                                {{ $log->status === 'approved' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-pink-500/10 text-pink-500' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                                </svg>
                                                {{ $log->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS AND STYLES RESTORED BELOW --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(236, 72, 153, 0.2);
            border-radius: 10px;
        }
    </style>

    <style>
        [x-cloak] {
            display: none !important;
        }

    </style>

<style>
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="month"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    right: 10px;
    cursor: pointer;
}
</style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#9ca3af' : '#4b5563';
            const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0,0,0,0.05)';

            // 1. Get raw data from PHP
            const rawCredits = {!! json_encode($creditsData) !!};
            const rawSpent = {!! json_encode($spentData) !!};

            // 2. Merge keys to get a master list of dates (Labels)
            const allDates = [...new Set([...Object.keys(rawCredits), ...Object.keys(rawSpent)])].sort();

            // 3. Map values to dates (fill 0 if no data for that date)
            const creditsValues = allDates.map(date => rawCredits[date] || 0);
            const spentValues = allDates.map(date => rawSpent[date] || 0);

            const ctx = document.getElementById('financeChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar', // Changed to Bar
                data: {
                    labels: allDates.map(date => {
                        // Formatting date to "DD MMM" for cleaner look
                        const d = new Date(date);
                        return d.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short'
                        });
                    }),
                    datasets: [{
                            label: 'Inflow (Credits)',
                            data: creditsValues,
                            backgroundColor: '#10b981', // Emerald
                            borderRadius: 6,
                            borderSkipped: false,
                            barThickness: 12,
                        },
                        {
                            label: 'Outflow (Expenses)',
                            data: spentValues,
                            backgroundColor: '#ec4899', // Pink/Magenta
                            borderRadius: 6,
                            borderSkipped: false,
                            barThickness: 12,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
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
                                    size: 10
                                },
                                callback: (value) => 'Rs. ' + value.toLocaleString()
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

    <script>
        document.querySelectorAll('input[type="date"], input[type="month"]').forEach(input => {
    input.addEventListener('click', () => {
        if (input.showPicker) {
            input.showPicker(); // Chrome/Edge modern
        } else {
            input.focus(); // fallback
        }
    });
});
    </script>
</x-app-layout>
