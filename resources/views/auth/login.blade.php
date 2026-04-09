<x-app-layout>

    {{-- Background Blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute w-[300px] h-[300px] sm:w-[500px] sm:h-[500px] bg-pink-500/10 dark:bg-pink-500/20 blur-[100px] sm:blur-[120px] top-[-120px] left-[-120px] rounded-full"></div>
        <div class="absolute w-[250px] h-[250px] sm:w-[400px] sm:h-[400px] bg-purple-500/10 dark:bg-[#d6007b]/20 blur-[100px] sm:blur-[120px] bottom-[-120px] right-[-120px] rounded-full"></div>
    </div>

    {{-- Main Container --}}
    <div class="min-h-screen flex items-center justify-center transition-colors duration-500 bg-gray-50 dark:bg-[#050505] overflow-x-hidden p-4">
        <div class="relative w-full max-w-md p-8 sm:p-10 rounded-3xl
                    bg-white/70 backdrop-blur-2xl border border-white/20 shadow-xl
                    dark:bg-white/5 dark:border-white/10 dark:shadow-[0_10px_40px_rgba(255,0,204,0.15)]
                    text-center transition-all duration-300">

            {{-- Logo --}}
            <div class="text-2xl sm:text-5xl font-extrabold
                        bg-gradient-to-r from-white via-pink-400 to-purple-400
                        bg-clip-text text-transparent flex items-center justify-center gap-2 mb-4">
                <img src="{{ asset('images/logo.png') }}" class="w-[40px] invert-0 dark:invert">
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white mb-2">
                Sigma Expense <span class="text-pink-600 dark:text-pink-400">Manager</span>
            </h1>

            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">
                Please enter your security PIN
            </p>

            {{-- Form --}}
            <form method="POST" action="/login" class="space-y-6" id="pin-form">
                @csrf
                <div class="flex justify-between gap-2 w-full">
                    @for ($i = 0; $i < 5; $i++)
                        <input type="password" name="pin[]" maxlength="1" class="premium-input pin" required inputmode="numeric" pattern="[0-9]*">
                    @endfor
                </div>


            </form>

            {{-- Footer --}}
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-white/5">
                <p class="text-[11px] uppercase tracking-widest text-gray-400 dark:text-gray-500">
                    © 2026 Sigma Engineering Services
                </p>
            </div>
        </div>
    </div>

    {{-- JS for PIN navigation & numeric only --}}
    <script>
    const inputs = document.querySelectorAll('.pin');
    const form = document.getElementById('pin-form');

    inputs.forEach((input, index) => {
        input.addEventListener('input', () => {
            // Allow only numbers
            input.value = input.value.replace(/[^0-9]/g, '');

            // Move to next input
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }

            // ✅ Check if all fields are filled
            const allFilled = [...inputs].every(i => i.value.length === 1);

            if (allFilled) {
                form.submit(); // 🔥 auto login
            }
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Paste support (auto fill all inputs)
    form.addEventListener('paste', (e) => {
        const paste = (e.clipboardData || window.clipboardData).getData('text');

        if (/^\d{5}$/.test(paste)) {
            e.preventDefault();
            paste.split('').forEach((char, i) => {
                if (inputs[i]) inputs[i].value = char;
            });

            form.submit(); // 🔥 auto submit on paste
        }
    });
</script>

</x-app-layout>
