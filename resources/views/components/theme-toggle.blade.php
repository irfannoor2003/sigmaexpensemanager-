@auth
    @if(auth()->user()->role === 'expense_manager')

        <!-- FLOATING ACTION BUTTONS -->
        <div id="expense-fab"
            class="fixed bottom-20 right-6 z-50 flex flex-col gap-3
            opacity-0 translate-y-10 pointer-events-none transition-all duration-500">

            <!-- ADD BUTTON -->
            <div class="group relative flex justify-end">

                <span class="absolute right-14 top-1/2 -translate-y-1/2
                    bg-black/80 text-white text-xs px-2 py-1 rounded-lg
                    opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                    Add Expense
                </span>

                <a href="{{ route('manager.create-expense') }}"
   class="h-12 w-12 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500
   shadow-lg shadow-pink-500/30
   flex items-center justify-center
   transition-all duration-300
   hover:scale-110 active:scale-95">


    <svg xmlns="http://www.w3.org/2000/svg"
     class="h-6 w-6 text-white"
     viewBox="0 0 640 640"
     fill="currentColor">
    <path d="M505 122.9L517.1 135C526.5 144.4 526.5 159.6 517.1 168.9L488 198.1L441.9 152L471 122.9C480.4 113.5 495.6 113.5 504.9 122.9zM273.8 320.2L408 185.9L454.1 232L319.8 366.2C316.9 369.1 313.3 371.2 309.4 372.3L250.9 389L267.6 330.5C268.7 326.6 270.8 323 273.7 320.1zM437.1 89L239.8 286.2C231.1 294.9 224.8 305.6 221.5 317.3L192.9 417.3C190.5 425.7 192.8 434.7 199 440.9C205.2 447.1 214.2 449.4 222.6 447L322.6 418.4C334.4 415 345.1 408.7 353.7 400.1L551 202.9C579.1 174.8 579.1 129.2 551 101.1L538.9 89C510.8 60.9 465.2 60.9 437.1 89zM152 128C103.4 128 64 167.4 64 216L64 488C64 536.6 103.4 576 152 576L424 576C472.6 576 512 536.6 512 488L512 376C512 362.7 501.3 352 488 352C474.7 352 464 362.7 464 376L464 488C464 510.1 446.1 528 424 528L152 528C129.9 528 112 510.1 112 488L112 216C112 193.9 129.9 176 152 176L264 176C277.3 176 288 165.3 288 152C288 138.7 277.3 128 264 128L152 128z"/>
</svg>

</a>
            </div>

            <!-- CALCULATOR BUTTON -->
            <div class="group relative flex justify-end">

                <span class="absolute right-14 top-1/2 -translate-y-1/2
                    bg-black/80 text-white text-xs px-2 py-1 rounded-lg
                    opacity-0 group-hover:opacity-100 transition whitespace-nowrap">
                    Calculator
                </span>

                <button
    id="calc-toggle-btn"
    class="h-12 w-12 rounded-2xl
    bg-gradient-to-br from-gray-900 to-gray-700
    shadow-lg shadow-black/30
    backdrop-blur-md
    flex items-center justify-center
    transition-all duration-300
    hover:scale-110 hover:shadow-black/50
    active:scale-95
    focus:ring-2 focus:ring-gray-400 focus:outline-none">

    <!-- ICON CONTAINER -->
    <span id="calc-icon">

        <!-- calculator icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <rect x="4" y="3" width="16" height="18" rx="2"/>
            <path d="M8 7h8M8 11h2m4 0h2m-6 4h2m4 0h2"/>
        </svg>

    </span>

    <!-- CLOSE ICON -->
    <span id="calc-close-icon" class="hidden">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12"/>
        </svg>

    </span>

</button>
            </div>

        </div>

    @endif
@endauth
@auth
    <div id="logout-btn"
        class="fixed bottom-6 right-6 z-50 opacity-0 translate-y-10 pointer-events-none transition-all duration-500">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                class="flex items-center justify-center h-12 w-12 rounded-2xl
            bg-gradient-to-br from-red-500 to-pink-500
            shadow-lg hover:scale-110 active:scale-95 transition-all">

                <!-- Power Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v9M6.22 6.22a8 8 0 1011.56 0" />
                </svg>

            </button>
        </form>
    </div>
@endauth


<!-- SYSTEM INFO BAR -->
<div id="system-info"
    class="fixed top-3 left-1/2 -translate-x-1/2 z-50
    flex flex-row  items-center gap-3 sm:gap-4
    px-3 sm:px-4 py-2 rounded-2xl
    bg-white/70 dark:bg-white/5 backdrop-blur-xl
    border border-gray-200 dark:border-white/10
    shadow-lg text-xs
    opacity-0 -translate-y-10 pointer-events-none
    transition-all duration-500 w-[42%] sm:w-auto  justify-center">

    <!-- TIME -->
    <div class="md:flex flex-col leading-tight text-center sm:text-left  hidden ">
        <span id="time" class="font-bold text-gray-900 dark:text-white"></span>
        <span id="date" class="text-gray-500 dark:text-gray-400"></span>
    </div>

    <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-white/10 "></div>

    <!-- WEATHER -->
    <div class="md:flex flex-col leading-tight text-center sm:text-left hidden ">
        <span id="temp" class="font-bold text-pink-600 dark:text-pink-400"></span>
        <span id="city" class="text-gray-500 dark:text-gray-400 text-[10px]"></span>
    </div>

    <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-white/10"></div>

    <!-- LANGUAGE TOGGLE -->
    <div class="flex items-center border border-gray-200 dark:border-white/10 rounded-xl overflow-hidden">

        <a href="{{ route('lang.switch', 'en') }}"
            class="px-3 py-2 text-xs font-bold transition-all
            {{ app()->getLocale() == 'en'
                ? 'bg-pink-500 text-white'
                : 'bg-white/80 dark:bg-gray-900/80 text-gray-700 dark:text-gray-300' }}">
            EN
        </a>

        <a href="{{ route('lang.switch', 'ur') }}"
            class="px-3 py-2 text-xs font-bold transition-all
            {{ app()->getLocale() == 'ur'
                ? 'bg-pink-500 text-white'
                : 'bg-white/80 dark:bg-gray-900/80 text-gray-700 dark:text-gray-300' }}">
            اردو
        </a>

    </div>

    <!-- THEME TOGGLE -->
    <button onclick="toggleTheme()"
        class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200
        bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm transition-all hover:scale-110">

        <!-- SUN -->
        <svg xmlns="http://www.w3.org/2000/svg"
            id="sunIcon"
            class="w-6 h-6 text-yellow-500 hidden"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <circle cx="12" cy="12" r="5" stroke-width="2" />
            <path stroke-width="2" stroke-linecap="round"
                d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>

        <!-- MOON -->
        <svg id="moonIcon"
            class="w-6 h-6 text-gray-700 dark:text-gray-300"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor">

            <path stroke-width="2"
                d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
        </svg>

    </button>
</div>


@auth
    @if(auth()->user()->role === 'expense_manager')
        <div id="calc-container"
            class="fixed md:bottom-2 bottom-0.5  right-20 z-[60]
            opacity-0 translate-y-10 pointer-events-none
            transition-all duration-300">

            <x-calculator />
        </div>
    @endif
@endauth
<script>
   const logoutBtn = document.getElementById('logout-btn');
const systemInfo = document.getElementById('system-info');
const expenseFab = document.getElementById('expense-fab');

function toggleUI(show) {

    // LOGOUT
    logoutBtn?.classList.toggle('opacity-100', show);
    logoutBtn?.classList.toggle('translate-y-0', show);
    logoutBtn?.classList.toggle('pointer-events-auto', show);

    logoutBtn?.classList.toggle('opacity-0', !show);
    logoutBtn?.classList.toggle('translate-y-10', !show);
    logoutBtn?.classList.toggle('pointer-events-none', !show);

    // SYSTEM INFO
    systemInfo?.classList.toggle('opacity-100', show);
    systemInfo?.classList.toggle('translate-y-0', show);
    systemInfo?.classList.toggle('pointer-events-auto', show);

    systemInfo?.classList.toggle('opacity-0', !show);
    systemInfo?.classList.toggle('-translate-y-10', !show);
    systemInfo?.classList.toggle('pointer-events-none', !show);

    // EXPENSE FAB (ONLY FOR ROLE USERS)
    if (expenseFab) {
        expenseFab.classList.toggle('opacity-100', show);
        expenseFab.classList.toggle('translate-y-0', show);
        expenseFab.classList.toggle('pointer-events-auto', show);

        expenseFab.classList.toggle('opacity-0', !show);
        expenseFab.classList.toggle('translate-y-10', !show);
        expenseFab.classList.toggle('pointer-events-none', !show);
    }
}

window.addEventListener('scroll', () => {
    toggleUI(window.scrollY > 120);
});

// ✅ IMPORTANT: run once on load
toggleUI(window.scrollY > 120);


    /* =========================
       TIME + DATE
    ========================= */
    function updateTime() {
        const now = new Date();

        document.getElementById('time').innerText =
            now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

        document.getElementById('date').innerText =
            now.toLocaleDateString([], {
                weekday: 'short',
                day: 'numeric',
                month: 'short'
            });
    }
    setInterval(updateTime, 1000);
    updateTime();


    /* =========================
       WEATHER
    ========================= */
    async function getWeather() {
        try {
            const res = await fetch(
                "https://api.open-meteo.com/v1/forecast?latitude=31.5204&longitude=74.3587&current_weather=true"
                );
            const data = await res.json();

            document.getElementById('temp').innerText = data.current_weather.temperature + "°C";
            document.getElementById('city').innerText = "Lahore";
        } catch {
            document.getElementById('temp').innerText = "--°C";
            document.getElementById('city').innerText = "Offline";
        }
    }
    getWeather();


    /* =========================
       THEME TOGGLE (FIXED)
    ========================= */
    const sunIcon = document.getElementById('sunIcon');
    const moonIcon = document.getElementById('moonIcon');

    function applyTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        sunIcon.classList.toggle('hidden', !isDark);
        moonIcon.classList.toggle('hidden', isDark);
    }

    function toggleTheme() {
        document.documentElement.classList.toggle('dark');

        localStorage.setItem(
            'theme',
            document.documentElement.classList.contains('dark') ? 'dark' : 'light'
        );

        applyTheme();
    }

    /* INIT THEME */
    (function initTheme() {
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        applyTheme();
    })();


    const calcBtn = document.getElementById('calc-toggle-btn');
const calcContainer = document.getElementById('calc-container');

let calcOpen = false;

calcBtn?.addEventListener('click', (e) => {
    e.stopPropagation(); // prevent outside click close

    calcOpen = !calcOpen;

    calcContainer.classList.toggle('opacity-100', calcOpen);
    calcContainer.classList.toggle('translate-y-0', calcOpen);
    calcContainer.classList.toggle('pointer-events-auto', calcOpen);

    calcContainer.classList.toggle('opacity-0', !calcOpen);
    calcContainer.classList.toggle('translate-y-10', !calcOpen);
    calcContainer.classList.toggle('pointer-events-none', !calcOpen);

    document.getElementById('calc-icon')?.classList.toggle('hidden', calcOpen);
    document.getElementById('calc-close-icon')?.classList.toggle('hidden', !calcOpen);
});

document.addEventListener('click', (e) => {
    if (!calcContainer || !calcBtn) return;

    if (!calcContainer.contains(e.target) && !calcBtn.contains(e.target)) {
        calcOpen = false;

        calcContainer.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
        calcContainer.classList.add('opacity-0', 'translate-y-10', 'pointer-events-none');
    }
});
</script>
