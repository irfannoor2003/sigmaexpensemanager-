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
    class="fixed top-6 left-1/2 -translate-x-1/2 z-50
    flex flex-col sm:flex-row items-center gap-3 sm:gap-4
    px-3 sm:px-4 py-3 rounded-2xl
    bg-white/70 dark:bg-white/5 backdrop-blur-xl
    border border-gray-200 dark:border-white/10
    shadow-lg text-xs
    opacity-0 -translate-y-10 pointer-events-none
    transition-all duration-500 w-[92%] sm:w-auto">

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

<script>
    /* =========================
   SCROLL ANIMATION
========================= */
    const logoutBtn = document.getElementById('logout-btn');
    const systemInfo = document.getElementById('system-info');

    window.addEventListener('scroll', () => {
        const show = window.scrollY > 120;

        logoutBtn?.classList.toggle('opacity-100', show);
        logoutBtn?.classList.toggle('translate-y-0', show);
        logoutBtn?.classList.toggle('pointer-events-none', !show);

        logoutBtn?.classList.toggle('opacity-0', !show);
        logoutBtn?.classList.toggle('translate-y-10', !show);

        systemInfo?.classList.toggle('opacity-100', show);
        systemInfo?.classList.toggle('translate-y-0', show);
        systemInfo?.classList.toggle('pointer-events-none', !show);

        systemInfo?.classList.toggle('opacity-0', !show);
        systemInfo?.classList.toggle('-translate-y-10', !show);
    });


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
</script>
