<x-app-layout>
    <x-toaster />

    {{-- Floating Back Button --}}
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
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-pink-500/10 text-pink-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modify Identity</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Updating SIGMA-{{ 1000 + $user->id }}</p>
            </div>

            {{-- Form Card --}}
            <div class="rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-2xl shadow-pink-500/5 p-8 backdrop-blur-sm">
                <form action="{{ route('admin.update-user', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Name Input --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 ml-1">Legal Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all outline-none">
                    </div>

                    {{-- Role Selection --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 ml-1">Access Level</label>
                        <div class="relative">
                            <select name="role" required class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white appearance-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="hr" {{ $user->role == 'hr' ? 'selected' : '' }}>Human Resources</option>
                                <option value="expense_manager" {{ $user->role == 'expense_manager' ? 'selected' : '' }}>Expense Manager</option>
                            </select>
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- PIN Section --}}
                    <div>
                        <label class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 block ml-1">
                            Security PIN <span class="lowercase opacity-60">(Leave empty to keep current)</span>
                        </label>
                        <div class="flex justify-between gap-2">
                            @for($i=0;$i<5;$i++)
                            <input type="password" name="pin[]" maxlength="1"
                                placeholder="•"
                                class="pin-input w-full aspect-square text-center text-gray-900 dark:text-white bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl text-xl font-bold focus:ring-2 focus:ring-pink-500 focus:border-pink-500 transition-all outline-none">
                            @endfor
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-pink-500 to-rose-500 text-white rounded-2xl font-bold shadow-xl shadow-pink-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                        Commit Changes
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
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
