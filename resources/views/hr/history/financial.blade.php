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
                            {{ __('app.Sigma Archive') }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('app.Financial Audit Log') }}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        {{ __('app.Reviewing monthly funding performance for') }} {{ $selectedYear }}.</p>
                </div>


                {{-- Year Filter --}}
                <div class="w-full lg:w-auto">
                    <form action="{{ route('hr.financial-history') }}" method="GET"
                        class="w-full lg:w-64 flex items-center gap-2 bg-white dark:bg-white/5 p-1.5 rounded-xl border border-gray-200 dark:border-white/10 shadow-sm">

                        <div class="pl-2 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <rect width="18" height="18" x="3" y="4" rx="2" />
                                <line x1="16" x2="16" y1="2" y2="6" />
                                <line x1="8" x2="8" y1="2" y2="6" />
                                <line x1="3" x2="21" y1="10" y2="10" />
                            </svg>
                        </div>

                        <select name="year" onchange="this.form.submit()"
                            class="w-full bg-transparent border-none text-xs font-bold dark:text-gray-300 focus:ring-0 cursor-pointer pr-8">

                            @for ($y = now()->year; $y >= 2023; $y--)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                    {{ __('app.Year') }} {{ $y }}
                                </option>
                            @endfor

                        </select>
                    </form>
                </div>
            </div>

            {{-- Summary Stats Section --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    class="p-6 rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">
                            {{ __('app.Total Annual Amount Added') }}</p>
                        <h3 class="text-2xl font-bold text-emerald-500 mt-1">{{ __('app.Rs') }}
                            {{ number_format($monthlyCredits->sum('total_credited')) }}</h3>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center">
    <!-- PKR SVG -->
    <svg class="w-6 h-6 text-emerald-500" viewBox="0 0 122.88 98.34" fill="currentColor">
    <path fill-rule="evenodd" d="M.71,24.6H8.8V0H40q14.7,0,22.74,7.64c4.4,4.19,7,9.86,7.77,17H81.81a.73.73,0,0,1,.71.72v11a.73.73,0,0,1-.71.72H70.15Q67.79,50.36,56.74,56.45L73.72,96V97h-21L38.1,61.65H28.37V97H8.8V37.07H.71A.73.73,0,0,1,0,36.35v-11a.71.71,0,0,1,.71-.72ZM107.86,81.4A4.84,4.84,0,0,0,106,77.76c-1.26-1.12-4.06-2.61-8.39-4.52q-9.57-3.88-13.16-8a15.28,15.28,0,0,1-3.58-10.33,16.56,16.56,0,0,1,5.62-12.82Q92.06,37,101.29,37c6.5,0,11.68,1.68,15.58,5s5.86,7.85,5.86,13.49H107.26q0-7.22-6-7.22a5.54,5.54,0,0,0-4,1.53,5.67,5.67,0,0,0-1.57,4.27,4.52,4.52,0,0,0,1.73,3.47c1.18,1,3.92,2.45,8.27,4.29,6.31,2.34,10.75,5,13.36,7.8s3.9,6.57,3.9,11.12a15.56,15.56,0,0,1-5.95,12.75c-4,3.21-9.18,4.81-15.64,4.81A24.81,24.81,0,0,1,89.82,95.8,19.2,19.2,0,0,1,82,88.71a18.23,18.23,0,0,1-2.8-9.77h14.7c.08,2.68.69,4.72,1.89,6.06s3.11,2,5.82,2c4.14,0,6.23-1.88,6.23-5.63ZM28.37,24.6H50.51q-2.13-8.24-10.68-8.23H28.37V24.6ZM50.42,37.07h-22v8.21H39.49q5.86,0,8.8-3.88a13.79,13.79,0,0,0,2.13-4.33Z"/>
</svg>
</div>
                </div>
                <div
                    class="p-6 rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">
                            {{ __('app.Total Annual Amount Spent') }}</p>
                        <h3 class="text-2xl font-bold text-pink-500 mt-1">{{ __('app.Rs') }}
                            {{ number_format($monthlyStats->sum('total_spent')) }}</h3>
                    </div>
                    <div class="h-12 w-12 rounded-2xl bg-pink-500/10 flex items-center justify-center text-pink-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="p-6 rounded-[2.5rem] bg-gray-900 dark:bg-white shadow-xl flex items-center justify-between">
                    <div>
                        <p class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold">
                            {{ __('app.Yearly Savings') }}</p>
                        @php $yearlyDiff = $monthlyCredits->sum('total_credited') - $monthlyStats->sum('total_spent'); @endphp
                        <h3
                            class="text-2xl font-bold {{ $yearlyDiff >= 0 ? 'text-emerald-400 dark:text-emerald-600' : 'text-rose-400 dark:text-rose-600' }} mt-1">
                            {{ $yearlyDiff >= 0 ? '+' : '' }}{{ __('app.Rs') }}. {{ number_format($yearlyDiff) }}
                        </h3>
                    </div>
                    <div
                        class="h-12 w-12 rounded-2xl bg-white/10 dark:bg-black/5 flex items-center justify-center text-white dark:text-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- History Table --}}
            <div
                class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[3rem] overflow-hidden shadow-2xl backdrop-blur-md">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr
                                class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px]">
                                    {{ __('app.Month Period') }}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px]">
                                    {{ __('app.Approved Funds') }}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px]">
                                    {{ __('app.Actual Spending') }}</th>
                                <th class="px-8 py-6 font-medium tracking-wider uppercase text-[10px]">
                                    {{ __('app.Remaining Balance') }}</th>

                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($monthlyStats as $stat)
                                @php
                                    $credited = $monthlyCredits[$stat->month]->total_credited ?? 0;
                                    $balance = $credited - $stat->total_spent;
                                @endphp
                                <tr class="group hover:bg-pink-500/[0.03] transition-all duration-300">
                                    @php
                                        $monthNumber = (int) $stat->month;
                                        $monthName = __('app.months.' . $monthNumber);
                                    @endphp

                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">

                                            <div
                                                class="w-10 h-10 rounded-2xl bg-gray-100 dark:bg-white/10 flex items-center justify-center font-black text-pink-500 text-xs">
                                                {{ substr($monthName, 0, 3) }}
                                            </div>

                                            <div class="flex flex-col">
                                                <span class="font-bold text-gray-900 dark:text-white text-base">
                                                    {{ $monthName }}
                                                </span>

                                                <span
                                                    class="text-[10px] uppercase text-gray-400 tracking-wider font-bold">
                                                    {{ $selectedYear }} {{ __('app.Fiscal') }}
                                                </span>
                                            </div>

                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-bold text-emerald-500">{{ __('app.Rs') }}
                                            {{ number_format($credited) }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="text-sm font-bold text-pink-500">{{ __('app.Rs') }}
                                            {{ number_format($stat->total_spent) }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span
                                            class="inline-flex px-3 py-1 rounded-xl text-[10px] font-black uppercase tracking-widest {{ $balance >= 0 ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                            {{ $balance >= 0 ? '+' : '' }}{{ __('app.Rs') }}
                                            {{ number_format($balance) }}
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
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-xs font-bold uppercase tracking-widest text-gray-500">No
                                                Historical Data for {{ $selectedYear }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="h-10"></div>
        </div>
    </div>

    <style>
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
</x-app-layout>
