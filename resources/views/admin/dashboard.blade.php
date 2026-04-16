<x-app-layout>
    <x-toaster />
    {{-- <div class="p-4 sm:p-8 transition-colors duration-500 bg-gray-50 dark:bg-[#050505] min-h-screen"> --}}
    <div class="p-4 sm:p-8 min-h-screen">

        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span
                        class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">{{ __('app.system_live') }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ __('app.manage_expense') }}
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('app.sigmaadmindash', ['year' => date('Y')]) }}
                </p>
            </div>
        </div>


        <div class="mb-10">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <a href="/admin/expenses"
                    class="group flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-white/10">
                    <div class="p-3 rounded-xl bg-pink-500/10 text-pink-500"><svg class="w-5 h-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg></div>
                    <span class="font-semibold text-sm dark:text-gray-200">{{ __('app.expense_log_admin') }}</span>
                </a>
                <a href="/admin/users"
                    class="group flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-white/10">
                    <div class="p-3 rounded-xl bg-indigo-500/10 text-indigo-500"><svg class="w-5 h-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg></div>
                    <span class="font-semibold text-sm dark:text-gray-200">{{ __('app.manage_user_admin') }}</span>
                </a>
                <a href="/admin/analytics"
                    class="group flex items-center gap-4 p-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm transition-all hover:bg-gray-100 dark:hover:bg-white/10">
                    <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-500"><svg class="w-5 h-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg></div>
                    <span class="font-semibold text-sm dark:text-gray-200">{{ __('app.deep_analytics_admin') }}</span>
                </a>

            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div
                class="lg:col-span-2 p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900 dark:text-white">{{ __('app.Expense Flow (PKR)') }}</h3>
                    <select id="timeframeFilter"
                        onchange="window.location.href = '{{ route('admin.dashboard') }}?timeframe=' + this.value"
                        class="bg-transparent text-xs border border-gray-200 dark:border-white/10 rounded-lg p-1 dark:text-white cursor-pointer hover:border-pink-500 transition-colors">
                        <option value="3" {{ request('timeframe') == '3' ? 'selected' : '' }}
                            class="dark:text-black">{{ __('app.Last 3 Months') }}
                        </option>
                        <option value="6" {{ request('timeframe') == '6' ? 'selected' : '' }}
                            class="dark:text-black">{{ __('app.Last 6 Months') }}
                        </option>
                        <option value="12" {{ request('timeframe') == '12' ? 'selected' : '' }}
                            class="dark:text-black">{{ __('app.Last 12 Months') }}
                        </option>
                    </select>
                    @if (request('timeframe'))
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-[10px] text-gray-400 hover:text-pink-500 border border-gray-200 dark:border-white/10 rounded-lg px-2 py-1 transition-colors">
                            {{ __('app.Reset') }}
                        </a>
                    @endif
                </div>
                <canvas id="monthlyChart" class="max-h-[300px]"></canvas>
            </div>

            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <h3 class="font-bold text-gray-900 dark:text-white mb-6">{{ __('app.Expense Distribution') }}</h3>

                <div class="relative">
                    <canvas id="categoryChart" class="max-h-[250px]"></canvas>
                </div>

                @php
                    // Group and calculate totals
                    $grouped = $expenses->groupBy(function ($item) {
                        return is_array($item->category)
                            ? $item->category['name'] ?? 'General'
                            : $item->category->name ?? 'General';
                    });
                    $totalCount = $expenses->count();

                    // Sigma Palette for both Chart and Legend
                    $palette = ['#ff5733', '#ffbd33', '#33ff57', '#3357ff', '#ff33a6'];
                @endphp

                <div class="mt-6 space-y-3 max-h-[150px] overflow-y-auto custom-scrollbar pr-2">
                    @foreach ($grouped as $name => $items)
    @php
        $percentage = $totalCount > 0 ? round(($items->count() / $totalCount) * 100) : 0;

        $color = $palette[$loop->index % count($palette)];

        $key = strtolower(str_replace([' ', '/'], '_', $name));
        $langKey = $key;
    @endphp

                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                            <span class="flex items-center gap-2 font-medium">
                                <i class="w-2 h-2 rounded-full" style="background-color: {{ $color }}"></i>

                                {{ __('app.categories.' . $langKey) }}
                            </span>

                            <span class="font-mono font-bold">{{ $percentage }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="mt-8">
            <div class="flex justify-between items-center mb-6">
    <h3 class="font-bold text-lg text-gray-900 dark:text-white tracking-tight">
        {{ __('app.Live Activity Stream') }}
    </h3>

    <a href="/admin/expenses"
        class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-pink-500/20 transition-all flex items-center gap-2">

        <!-- Eye Icon -->
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5
                   c4.477 0 8.268 2.943 9.542 7
                   -1.274 4.057-5.065 7-9.542 7
                   -4.477 0-8.268-2.943-9.542-7z" />
        </svg>

        {{ __('app.View All Logs') }}
    </a>
</div>

            <div
                class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="overflow-x-auto hidden md:block">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-100 dark:border-white/5">
                                <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">
                                    {{ __('app.MEMBER NAME') }}</th>
                                <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">
                                    {{ __('app.TITLE') }}</th>
                                <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">
                                    {{ __('app.CATEGORY') }}</th>
                                <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">
                                    {{ __('app.AMOUNT') }}</th>
                                <th class="pb-4 font-bold text-right text-[10px] uppercase tracking-[0.15em]">
                                    {{ __('app.STATUS') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($recentExpenses as $expense)
                                <tr class="group transition-colors hover:bg-gray-50/50 dark:hover:bg-white/5">
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            {{-- Dynamic Initials Avatar --}}
                                            <div
                                                class="w-8 h-8 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[10px] font-black uppercase">
                                                {{ strtoupper(substr($expense->user->display_name, 0, 2)) }}
                                            </div>
                                            <span
                                                class="font-semibold text-gray-900 dark:text-white">{{ optional($expense->user)->display_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-500 dark:text-gray-400">
                                        {{ $expense->title }}
                                    </td>
                                    <td class="py-4 text-gray-500 dark:text-gray-400">
                                        {{ __('app.categories.' . $expense->category->name ?? 'site_general') }}
                                    </td>
                                    <td class="py-4 font-mono font-bold text-pink-600 dark:text-pink-400">
                                        {{ number_format($expense->amount) }} PKR
                                    </td>
                                    <td class="py-4 text-right">
                                        @php
                                            $statusClasses = [
                                                'approved' => 'bg-emerald-500/10 text-emerald-500',
                                                'pending' => 'bg-amber-500/10 text-amber-500',
                                                'rejected' => 'bg-rose-500/10 text-rose-500',
                                            ];

                                            // normalize DB value
                                            $status = strtolower($expense->status);
                                        @endphp

                                        <span
                                            class="px-3 py-1 {{ $statusClasses[$status] ?? 'bg-gray-500/10 text-gray-500' }}
        text-[10px] font-bold rounded-full uppercase tracking-tighter">

                                            {{ __('app.status.' . $status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <p class="text-gray-400 italic text-sm">No live activity recorded yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- 📱 Mobile Activity Cards -->
                <div class="md:hidden space-y-4">
                    @forelse($recentExpenses as $expense)
                        <div
                            class="p-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">

                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-8 h-8 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center text-[10px] font-black uppercase">
                                        {{ strtoupper(substr($expense->user->display_name, 0, 2)) }}
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white text-sm">
                                        {{ optional($expense->user)->display_name }}
                                    </span>
                                </div>

                                <span
                                    class="text-[10px] px-2 py-1 rounded-full
                    {{ $statusClasses[$expense->status] ?? 'bg-gray-500/10 text-gray-500' }}">
                                    {{ $expense->status }}
                                </span>
                            </div>

                            <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                <div><span class="font-semibold">Title:</span> {{ $expense->title }}</div>
                                <div><span class="font-semibold">Category:</span>
                                    {{ is_array($expense->category) ? $expense->category['name'] ?? 'General' : $expense->category->name ?? 'General' }}
                                </div>
                                <div class="font-mono text-pink-500 font-bold">
                                    PKR {{ number_format($expense->amount) }}
                                </div>
                            </div>

                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm">No activity found</p>
                    @endforelse
                </div>
            </div>

            <!-- User Table -->
            <div class="mt-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('app.User Management') }}</h3>
                    <a href="{{ route('admin.create-user') }}"
                        class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-pink-500/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('app.Add New User') }}
                    </a>
                </div>

                <div
                    class="overflow-hidden rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                    <div class="overflow-x-auto hidden md:block">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr
                                    class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">
                                        {{ __('app.USER PROFILE') }}
                                    </th>
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">
                                        {{ __('app.ACCESS ROLE') }}
                                    </th>
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px]">
                                        {{ __('app.SECURITY PIN') }}
                                    </th>
                                    <th class="px-6 py-4 font-medium tracking-wider uppercase text-[10px] text-right">
                                        {{ __('app.ACTIONS') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                                @foreach ($users as $user)
                                    <tr
                                        class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.03] transition-all duration-300">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-[10px]">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <span
                                                    class="font-semibold text-gray-900 dark:text-white">{{ $user->display_name }}</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @php
                                                $roleColors = [
                                                    'admin' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                                    'hr' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                                    'expense_manager' =>
                                                        'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                                ];

                                                $role = strtolower($user->role);
                                                $colorClass =
                                                    $roleColors[$role] ??
                                                    'bg-gray-500/10 text-gray-500 border-gray-500/20';
                                            @endphp

                                            <span
                                                class="px-2.5 py-1 rounded-lg border {{ $colorClass }} text-[10px] font-bold uppercase">
                                                {{ __('app.roles.' . $role) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2 group/pin">
                                                <code
                                                    class="font-mono text-gray-400 dark:text-gray-500 tracking-widest text-xs bg-gray-100 dark:bg-white/5 px-2 py-1 rounded-md">
                                                    •••••
                                                </code>
                                                <span
                                                    class="text-[10px] text-gray-400 opacity-0 group-hover/pin:opacity-100 transition-opacity">Encrypted</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <a href="{{ route('admin.edit-user', $user->id) }}"
                                                    class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-500/10 rounded-xl transition-all"
                                                    title="Edit User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>


                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- 📱 Mobile User Cards -->
                    <div class="md:hidden space-y-4 mt-4 p-4">
                        @foreach ($users as $user)
                            <div
                                class="p-4 rounded-2xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">

                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-[10px] font-bold">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="font-semibold text-gray-900 dark:text-white text-sm">
                                            {{ $user->display_name }}
                                        </span>
                                    </div>

                                    <a href="{{ route('admin.edit-user', $user->id) }}"
                                        class="text-xs text-indigo-500 font-bold">
                                        Edit
                                    </a>
                                </div>

                                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                    <div>
                                        <span class="font-semibold">Role:</span>
                                        <span class="uppercase">{{ str_replace('_', ' ', $user->role) }}</span>
                                    </div>

                                    <div>
                                        <span class="font-semibold">PIN:</span> •••••
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#9ca3af' : '#4b5563';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0,0,0,0.05)';

                // 1. Line Chart (Cash Flow)
                // Line Chart (Cash Flow)
                new Chart(document.getElementById('monthlyChart'), {
                    type: 'line',
                    data: {
                        // Inject dynamic labels and data from PHP
                        labels: @json($monthlyLabels),
                        datasets: [{
                            label: 'Total Expenses',
                            data: @json($monthlyData),
                            borderColor: '#ec4899',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#ec4899',
                            fill: true,
                            backgroundColor: (context) => {
                                const bg = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                                bg.addColorStop(0, 'rgba(236, 72, 153, 0.2)');
                                bg.addColorStop(1, 'rgba(236, 72, 153, 0)');
                                return bg;
                            }
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
                                callbacks: {
                                    label: function(context) {
                                        return `PKR ${new Intl.NumberFormat().format(context.raw)}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: textColor
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: gridColor
                                },
                                ticks: {
                                    color: textColor,
                                    callback: function(value) {
                                        if (value >= 1000) return value / 1000 + 'k';
                                        return value;
                                    }
                                }
                            }
                        }
                    }
                });
                // 2. DYNAMIC Doughnut Chart (Expense Distribution)
                const catCtx = document.getElementById('categoryChart');

                // Data passed from your Blade @php block
                const labels = @json($grouped->keys());
                const dataValues = @json($grouped->map->count()->values());

                // Sigma Palette
                const sigmaPalette = ['#ff5733', '#ffbd33', '#33ff57', '#3357ff', '#ff33a6'];

                new Chart(catCtx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataValues,
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
                                enabled: true,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.raw || 0;
                                        return ` ${label}: ${value} Items`;
                                    }
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
