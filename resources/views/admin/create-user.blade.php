<x-app-layout>
    <x-toaster />

    <a
        href="{{ route('admin.users') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400"
        aria-label="Go Back"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>

    <div class="p-4 sm:p-8  min-h-screen flex items-center justify-center">

        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-500/10 text-pink-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">New Identity</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Sigma Engineering Access Control</p>
            </div>

            <div class="rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-2xl shadow-indigo-500/5 p-8 backdrop-blur-sm">
                <form action="{{ route('admin.store-user') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 ml-1">Legal Name</label>
                        <input type="text" name="name" required placeholder="Hammad Malik"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
    <label class="block text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 ml-1">
        Access Level
    </label>
    <div class="relative">
        <select name="role" required
            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white appearance-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">

            {{-- We apply bg-white and text-black to the options --}}
            <option value="" disabled selected class="bg-white text-black">Select Authorization</option>
            <option value="admin" class="bg-white text-black">Administrator</option>
            <option value="hr" class="bg-white text-black">HR</option>
            <option value="expense_manager" class="bg-white text-black">Expense Manager</option>

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
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 block ml-1">Security PIN</label>
                        <div class="flex justify-between gap-2">
                            @for($i=0;$i<5;$i++)
                            <input type="password" name="pin[]" maxlength="1" required
                                class="pin-input w-full aspect-square text-center text-gray-900 dark:text-white bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl text-xl font-bold focus:ring-2 focus:ring-pink-500 focus:border-indigo-500 transition-all outline-none">
                            @endfor
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-pink-400 to-pink-400
                                dark:from-pink-400 dark:to-[#ff00cc] text-white rounded-2xl font-bold shadow-xl shadow-pink-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                        Initialize Account
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // PIN Auto-focus Logic
        const inputs = document.querySelectorAll('.pin-input');
        inputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < inputs.length - 1) inputs[index + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === "Backspace" && !e.target.value && index > 0) inputs[index - 1].focus();
            });
        });
    </script>
</x-app-layout>
