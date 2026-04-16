<x-app-layout>
    <x-toaster />

    <a href="{{ route('admin.users') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400"
        aria-label="Go Back">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8  min-h-screen flex items-center justify-center">

        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-500/10 text-pink-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{__('app.ACCESS LEVEL')}}</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">{{__('app.Sigma Engineering Access Control')}}</p>
            </div>

            <div
                class="rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-2xl shadow-indigo-500/5 p-8 backdrop-blur-sm">
                <form action="{{ route('admin.store-user') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label
                            class="block text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 ml-1">{{__('app.LEGAL NAME')}}</label>
                        <input type="text" name="name" required placeholder="{{ __('app.Hammad Malik') }}"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 ml-1">
                             {{__('app.ACCESS LEVEL')}}
                        </label>
                        <div class="relative">
                            <select name="role" required
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white appearance-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">

                                {{-- We apply bg-white and text-black to the options --}}
                                <option value="" disabled selected class="bg-white text-black">{{__('app.Select Authorization')}}</option>
                                <option value="admin" class="bg-white text-black">{{__('app.Administrator')}}</option>
                                <option value="hr" class="bg-white text-black">{{__('app.HR')}}</option>
                                <option value="expense_manager" class="bg-white text-black">{{__('app.Expense Manager')}}</option>

                            </select>

                            {{-- Custom Arrow Icon --}}
                            <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label
                            class="text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold mb-2 block ml-1">
                            {{__('app.SECURITY PIN')}}
                        </label>

                        <div class="relative">
                            <!-- Inputs -->
                            <div class="flex justify-between gap-2 pr-10">
                                @for ($i = 0; $i < 5; $i++)
                                    <input type="password" name="pin[]" maxlength="1" required
                                        class="pin-input w-full aspect-square text-center text-gray-900 dark:text-white bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl text-xl font-bold focus:ring-2 focus:ring-pink-500 focus:border-indigo-500 transition-all outline-none">
                                @endfor
                            </div>

                            <!-- Eye Icon -->
                            <button type="button" onclick="togglePin()"
                                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-pink-500 transition">

                                <!-- Eye Open -->
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                       -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>

                                <!-- Eye Closed -->
                                <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                       c-4.478 0-8.27-2.943-9.544-7a9.956 9.956 0 012.223-3.592M6.223 6.223A9.956 9.956 0 0112 5
                       c4.478 0 8.27 2.943 9.544 7a9.956 9.956 0 01-4.293 5.196M15 12a3 3 0 00-3-3
                       m0 0a3 3 0 00-3 3m3-3v6m9 3L3 3" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-pink-400 to-pink-400
                                dark:from-pink-400 dark:to-[#ff00cc] text-white rounded-2xl font-bold shadow-xl shadow-pink-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                        {{__('app.Initialize Account')}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
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
    <script>
        function togglePin() {
            const inputs = document.querySelectorAll('.pin-input');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClose = document.getElementById('eyeClose');

            inputs.forEach(input => {
                input.type = input.type === 'password' ? 'text' : 'password';
            });

            eyeOpen.classList.toggle('hidden');
            eyeClose.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
