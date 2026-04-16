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
                            {{__('app.Manager Dashboard')}}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{__('app.Wallet Overview')}}
                    </h1>
                </div>

                <button onclick="document.getElementById('requestModal').showModal()"
                    class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold uppercase rounded-xl transition-all shadow-lg shadow-pink-500/25 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    {{__('app.Request Top-up')}}
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
                                    {{__('app.Current Balance')}}
                                </p>
                                <h2
                                    class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white mt-2 tracking-tighter">
                                    <span class="text-pink-500 text-2xl sm:text-3xl">{{__('app.Rs')}}</span>
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
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{__('app.Remaining Balance')}}</p>
                          <p class="text-xs font-bold dark:text-white">
    @if(app()->getLocale() === 'ur')
        {{ number_format($totalInflow) }}
        <span class="text-gray-500 font-medium">میں سے</span>
        {{ number_format($currentBalance) }}
        <span class="text-gray-500 font-medium">باقی ہے</span>
    @else
        {{ number_format($currentBalance) }}
        <span class="text-gray-500 font-medium">left of</span>
        {{ number_format($totalInflow) }}
    @endif
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
                                <svg class="w-4 h-4" viewBox="0 0 122.88 98.34" fill="currentColor">
        <path fill-rule="evenodd" d="M.71,24.6H8.8V0H40q14.7,0,22.74,7.64c4.4,4.19,7,9.86,7.77,17H81.81a.73.73,0,0,1,.71.72v11a.73.73,0,0,1-.71.72H70.15Q67.79,50.36,56.74,56.45L73.72,96V97h-21L38.1,61.65H28.37V97H8.8V37.07H.71A.73.73,0,0,1,0,36.35v-11a.71.71,0,0,1,.71-.72ZM107.86,81.4A4.84,4.84,0,0,0,106,77.76c-1.26-1.12-4.06-2.61-8.39-4.52q-9.57-3.88-13.16-8a15.28,15.28,0,0,1-3.58-10.33,16.56,16.56,0,0,1,5.62-12.82Q92.06,37,101.29,37c6.5,0,11.68,1.68,15.58,5s5.86,7.85,5.86,13.49H107.26q0-7.22-6-7.22a5.54,5.54,0,0,0-4,1.53,5.67,5.67,0,0,0-1.57,4.27,4.52,4.52,0,0,0,1.73,3.47c1.18,1,3.92,2.45,8.27,4.29,6.31,2.34,10.75,5,13.36,7.8s3.9,6.57,3.9,11.12a15.56,15.56,0,0,1-5.95,12.75c-4,3.21-9.18,4.81-15.64,4.81A24.81,24.81,0,0,1,89.82,95.8,19.2,19.2,0,0,1,82,88.71a18.23,18.23,0,0,1-2.8-9.77h14.7c.08,2.68.69,4.72,1.89,6.06s3.11,2,5.82,2c4.14,0,6.23-1.88,6.23-5.63ZM28.37,24.6H50.51q-2.13-8.24-10.68-8.23H28.37V24.6ZM50.42,37.07h-22v8.21H39.49q5.86,0,8.8-3.88a13.79,13.79,0,0,0,2.13-4.33Z"/>
    </svg>
                            </div>
                            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">{{__('app.Monthly Spend')}}</p>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{__('app.Rs')}}
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
                            <p class="text-[10px] uppercase tracking-widest text-gray-500 font-bold">{{__('app.Recent Entries')}}</p>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $recentCount }}</h3>
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
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{__('app.Spending Velocity')}}</h3>
                    </div>
                    <div class="h-[250px] w-full">
                        <canvas id="expenseChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 flex flex-col gap-4">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm ml-2 uppercase tracking-widest">{{__('app.Spending Velocity')}}</h3>
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
                        <p class="text-xs font-bold  dark:text-white uppercase ">{{__('app.Submit New Expense')}}</p>
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
                        <p class="text-xs font-bold dark:text-white uppercase tracking-tighter">{{__('app.View Expense History')}}
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
                            {{__('app.Transaction Logs')}}</h3>
                    </div>

                    {{-- VIEW ALL BUTTON --}}
                    <a href="{{ route('manager.expenses.history') }}"
                        class="group flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-pink-500/10 rounded-xl transition-all border border-transparent hover:border-pink-500/30">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-gray-500 group-hover:text-pink-500 transition-colors">{{__('app.View All History')}}</span>
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
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.Title')}} </th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.Created Date')}}
                                    </th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.AMOUNT')}}</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.Actual Date')}}
                                    </th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px] text-right">
                                        {{__('app.STATUS')}}</th>
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
                                            -{{__('app.Rs')}}. {{ number_format($item->amount) }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-400 dark:text-gray-500 text-[10px] font-medium">
                                            {{-- Since it's cast in the Model, just call format directly --}}
                                            {{ $item->expense_date ? $item->expense_date->format('d M, Y') : 'No Date Set' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
    @php
        $status = strtolower($item->status);

        $statusClasses = [
            'approved' => 'bg-emerald-500/10 text-emerald-500',
            'pending' => 'bg-orange-500/10 text-orange-500',
            'rejected' => 'bg-red-500/10 text-red-500',
        ];
    @endphp

    <span
        class="inline-flex px-2 py-1 rounded-lg text-[9px] font-bold uppercase {{ $statusClasses[$status] ?? 'bg-gray-500/10 text-gray-500' }}">

        {{ __('app.status.' . $status) }}
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
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-widest">{{__('app.TopupHistory')}}</h3>
                    </div>

                    {{-- VIEW ALL BUTTON --}}
                    <a href="{{ route('manager.topup.history') }}"
                        class="group flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-white/5 hover:bg-emerald-500/10 rounded-xl transition-all border border-transparent hover:border-emerald-500/30">
                        <span
                            class="text-[10px] font-bold uppercase tracking-widest text-gray-500 group-hover:text-emerald-500 transition-colors">{{__('app.View Records')}}</span>
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
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.Topup')}}</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.DATE')}}</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px]">{{__('app.AMOUNT')}}</th>
                                    <th class="px-6 py-4 font-bold tracking-wider uppercase text-[10px] text-right">
                                        {{__('app.STATUS')}}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @forelse($topUpLogs as $log)
                                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.03] transition-all">
                                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{__('app.Wallet Top-up')}}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-[11px] font-medium">
                                            {{ \Carbon\Carbon::parse($log->created_at)->format('d M, Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                            +{{__('app.Rs')}}. {{ number_format($log->amount) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span
                                                class="inline-flex px-2 py-1 rounded-lg text-[9px] font-bold uppercase bg-emerald-500 text-white">
                                                {{__('app.CLEARED')}}
                                            </span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 text-xs">{{__('app.No top-up records.')}}.</td>
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
                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{__('app.Request Funds')}}
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
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{__('app.Amount (Rs.)')}}</label>
                    <input type="number" name="amount" required placeholder="0.00"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent outline-none transition">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">
                        {{__('app.Reason / Note')}}
                    </label>

                    <div class="relative">
                        <textarea id="speechText" name="remarks" rows="3" placeholder="{{__('app.WhyDo')}}"
                            class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 pr-12 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500 focus:border-transparent outline-none transition"></textarea>

                        <!-- 🎤 Mic Button -->
                        <button type="button" id="micBtn"
                            class="absolute right-3 top-3 p-2 rounded-md bg-pink-500/70 text-pink-500 hover:bg-pink-500 text-white transition ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                                <line x1="12" y1="19" x2="12" y2="23" />
                                <line x1="8" y1="23" x2="16" y2="23" />
                            </svg>
                        </button>

                    </div>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-pink-500 hover:bg-pink-600 text-white font-bold uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-pink-500/30 transition-all active:scale-95">
                    {{__('app.Submit Request')}}
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            let shouldOpen = "{{ session('openRequestModal') }}";

            if (shouldOpen) {
                const modal = document.getElementById('requestModal');

                if (modal) {
                    // Small delay ensures DOM is fully ready
                    setTimeout(() => {
                        modal.showModal();

                        // Autofill shortage amount
                        let amountInput = modal.querySelector('input[name="amount"]');
                        if (amountInput) {
                            amountInput.value = "{{ session('shortage') }}";
                            amountInput.focus();
                        }
                    }, 300);
                }
            }

        });
    </script>
    <script>
     document.addEventListener('DOMContentLoaded', function () {

    const micBtn = document.getElementById('micBtn');
    const textArea = document.getElementById('speechText');

    let recognition;
    let isListening = false;

    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        micBtn.style.display = 'none';
        return;
    }

    recognition = new SpeechRecognition();

    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';

    recognition.onstart = () => {
        isListening = true;
        micBtn.classList.add('animate-pulse', 'bg-pink-600');
        micBtn.classList.remove('bg-pink-500/70');
    };

    recognition.onresult = (event) => {
        let transcript = '';

        for (let i = event.resultIndex; i < event.results.length; i++) {
            transcript += event.results[i][0].transcript;
        }

        textArea.value = textArea.value + transcript + ' ';
        textArea.focus();
    };

    recognition.onerror = (event) => {
        console.log("Speech error:", event.error);
        stopMic();
    };

    recognition.onend = () => {
        stopMic();
    };

    micBtn.addEventListener('click', function () {
        if (!isListening) {
            recognition.start();
        } else {
            recognition.stop();
        }
    });

    function stopMic() {
        isListening = false;
        micBtn.classList.remove('animate-pulse', 'bg-pink-600');
        micBtn.classList.add('bg-pink-500/70');
    }
});
    </script>
</x-app-layout>
