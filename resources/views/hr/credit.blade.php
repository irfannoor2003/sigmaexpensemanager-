<x-app-layout>
    <x-toaster />

    {{-- Floating Back Button --}}
    <a href="{{ route('hr.dashboard') }}" class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>

    <div class="p-4 sm:p-8  min-h-screen flex items-center justify-center">
        <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-2 gap-8">

            {{-- Form Side --}}
            <div class="order-2 lg:order-1">
                <div class="text-center lg:text-left mb-8">
                    <h1 class="text-3xl font-black dark:text-white">Wallet <span class="text-pink-500">Funding</span></h1>
                    <p class="text-gray-500 text-sm">Distribute operational credits to managers</p>
                </div>

                <div class="rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-2xl shadow-pink-500/5 p-8 backdrop-blur-sm">
                    <form action="{{ route('hr.store-credit') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">Target Manager</label>
                            <div class="relative">
    <select name="user_id" required
        class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white appearance-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all">

        <option value="" disabled selected class="bg-white text-black">Select Manager</option>

        @foreach($users as $user)
            <option value="{{ $user->id }}" class="bg-white text-black">
                {{ $user->name }}
            </option>
        @endforeach
    </select>

    {{-- Custom Arrow Icon --}}
    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</div>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">Credit Amount (PKR)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-pink-500 font-bold">Rs.</span>
                                <input type="number" name="amount" required placeholder="0.00" step="0.01"
                                    class="w-full pl-12 pr-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">Allocation Remarks</label>
                            <textarea name="remarks" rows="2" placeholder="e.g., Weekly fuel allowance"
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-pink-500 to-pink-500 text-white rounded-2xl font-bold shadow-xl shadow-pink-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                            Initialize Transfer
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Balance Sidebar --}}
            <div class="order-1 lg:order-2">
                <div class="bg-gray-100/50 dark:bg-white/5 rounded-[2.5rem] p-6 border border-gray-200 dark:border-white/10 h-full">
                    <h3 class="text-sm font-bold dark:text-white mb-6 px-2">Live Balances</h3>
                    <div class="space-y-3">
                        @foreach($users as $user)
                            <div class="flex items-center justify-between p-4 bg-white dark:bg-black/20 rounded-2xl border border-gray-100 dark:border-white/5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-pink-500/10 text-pink-500 flex items-center justify-center font-bold text-xs">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-medium dark:text-gray-300">{{ $user->name }}</span>
                                </div>
                                <span class="text-sm font-black dark:text-white">Rs. {{ number_format($user->wallet) }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Quick Tip --}}
                    <div class="mt-8 p-4 bg-pink-500/5 border border-pink-500/10 rounded-2xl">
                        <p class="text-[10px] text-pink-600 dark:text-pink-400 font-bold uppercase tracking-widest mb-1">System Note</p>
                        <p class="text-xs text-gray-500 leading-relaxed">Credits are applied instantly. Every transaction is logged with your ID for the monthly audit trail.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
