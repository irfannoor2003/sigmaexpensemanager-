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
    @php
    $totalUsers = $users->total();
    $adminCount = $users->getCollection()->where('role', 'admin')->count();
    $managerCount = $users->getCollection()->where('role', 'expense_manager')->count();
    $hrCount = $users->getCollection()->where('role', 'hr')->count();
@endphp
        {{-- Header --}}
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] uppercase tracking-widest text-emerald-600 dark:text-emerald-400 font-bold">System Live</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">User Management</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Sigma Engineering Services • {{ $totalUsers }} system operators</p>
            </div>

            <a href="{{ route('admin.create-user') }}"
                class="px-4 py-2 bg-pink-500 hover:bg-pink-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-pink-500/20 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New Member
            </a>
        </div>



        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

            {{-- Total Members --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">Total Members</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white font-mono">{{ $totalUsers }}</span>
                    <span class="text-xs text-emerald-500 font-bold">Live</span>
                </div>
                <div class="mt-4 h-1 w-full bg-gray-100 dark:bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-pink-500 w-full animate-pulse"></div>
                </div>
            </div>

            {{-- Admins --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">Admins</p>
                <span class="text-3xl font-bold text-gray-900 dark:text-white font-mono">{{ $adminCount  }}</span>
                <div class="mt-3">
                    <span class="px-2.5 py-1 rounded-lg border bg-purple-500/10 text-purple-500 border-purple-500/20 text-[10px] font-bold uppercase">Admin</span>
                </div>
            </div>

            {{-- Expense Managers --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm">
                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">Exp. Managers</p>
                <span class="text-3xl font-bold text-gray-900 dark:text-white font-mono">{{ $managerCount }}</span>
                <div class="mt-3">
                    <span class="px-2.5 py-1 rounded-lg border bg-amber-500/10 text-amber-500 border-amber-500/20 text-[10px] font-bold uppercase">Manager</span>
                </div>
            </div>

            {{-- HR Team + Mini Donut --}}
            <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm flex items-center justify-between gap-4">
                <div>
                    <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1">HR Team</p>
                    <span class="text-3xl font-bold text-gray-900 dark:text-white font-mono">{{ $hrCount  }}</span>
                    <div class="mt-3">
                        <span class="px-2.5 py-1 rounded-lg border bg-emerald-500/10 text-emerald-500 border-emerald-500/20 text-[10px] font-bold uppercase">HR</span>
                    </div>
                </div>
                <div class="w-16 h-16 flex-shrink-0">
                    <canvas id="roleDistributionChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Users Table --}}
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Personnel Directory</h3>
        </div>

        <div class="p-6 rounded-3xl bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm overflow-hidden">

            {{-- Desktop Table --}}
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-gray-400 border-b border-gray-100 dark:border-white/5">
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">User Profile</th>
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">Access Role</th>
                            <th class="pb-4 font-bold text-[10px] uppercase tracking-[0.15em]">Security PIN</th>
                            <th class="pb-4 font-bold text-right text-[10px] uppercase tracking-[0.15em]">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                        @foreach($users as $user)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-white/[0.03] transition-all duration-300">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-[10px]">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono">SIGMA-{{ 1000 + $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                        'hr' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                        'expense_manager' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    ];
                                    $colorClass = $roleColors[$user->role] ?? 'bg-gray-500/10 text-gray-500 border-gray-500/20';
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg border {{ $colorClass }} text-[10px] font-bold uppercase">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-2 group/pin">
                                    <code class="font-mono text-gray-400 dark:text-gray-500 tracking-widest text-xs bg-gray-100 dark:bg-white/5 px-2 py-1 rounded-md">
                                        •••••
                                    </code>
                                    <span class="text-[10px] text-gray-400 opacity-0 group-hover/pin:opacity-100 transition-opacity">Encrypted</span>
                                </div>
                            </td>
                            <td class="py-4 text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.edit-user', $user->id) }}"
                                        class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-500/10 rounded-xl transition-all"
                                        title="Edit User">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" onsubmit="return confirm('Permanently remove this user?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-xl transition-all"
                                            title="Delete User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
            <div class="mt-6">
                {{ $users->links() }}
            </div>
            </div>

            {{-- Mobile Card View --}}
            <div class="sm:hidden divide-y divide-gray-50 dark:divide-white/5">
                @foreach($users as $user)
                <div class="py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-[10px]">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $user->name }}</p>
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                                    'hr' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'expense_manager' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                ];
                                $colorClass = $roleColors[$user->role] ?? 'bg-gray-500/10 text-gray-500 border-gray-500/20';
                            @endphp
                            <span class="px-2 py-0.5 rounded-lg border {{ $colorClass }} text-[9px] font-bold uppercase">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex gap-1">
                        <a href="{{ route('admin.edit-user', $user->id) }}"
                            class="p-2 text-gray-400 hover:text-indigo-500 hover:bg-indigo-500/10 rounded-xl transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <form action="{{ route('admin.delete-user', $user->id) }}" method="POST" onsubmit="return confirm('Permanently remove this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-xl transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('roleDistributionChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        {{ $adminCount  }},
                        {{ $managerCount }},
                        {{ $hrCount  }}
                    ],
                    backgroundColor: ['#a855f7', '#f59e0b', '#10b981'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });
    </script>
</x-app-layout>
