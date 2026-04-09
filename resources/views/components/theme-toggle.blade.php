@auth
<div id="logout-btn"
     class="fixed bottom-6 right-6 z-50 opacity-0 translate-y-10 pointer-events-none transition-all duration-500">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button
            class="flex items-center justify-center h-12 w-12 rounded-2xl
                   bg-gradient-to-br from-red-500 to-pink-500
                   shadow-lg hover:scale-110 active:scale-95
                   transition-all duration-300"
        >
            <!-- Power Icon -->
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6 text-white"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v9" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6.22 6.22a8 8 0 1 0 11.56 0" />
            </svg>
        </button>
    </form>
</div>
@endauth


<div id="system-info"
     class="sm:flex fixed top-6 left-1/2 -translate-x-1/2 z-50 items-center gap-4
            px-4 py-2 rounded-2xl
            bg-white/70 dark:bg-white/5 backdrop-blur-xl
            border border-gray-200 dark:border-white/10
            shadow-lg text-xs
            opacity-0 -translate-y-10 pointer-events-none transition-all duration-500">

    <!-- Time -->
    <div class="flex flex-col leading-tight hidden sm:flex">
        <span id="time" class="font-bold text-gray-900 dark:text-white"></span>
        <span id="date" class="text-gray-500 dark:text-gray-400"></span>
    </div>

    <!-- Divider -->
    <div class="w-px h-8 bg-gray-200 dark:bg-white/10 hidden sm:flex"></div>

    <!-- Weather -->
    <div class="flex flex-col leading-tight hidden sm:flex">
        <span id="temp" class="font-bold text-pink-600 dark:text-pink-400"></span>
        <span id="city" class="text-gray-500 dark:text-gray-400 text-[10px]"></span>
    </div>

    <!-- Theme Toggle -->
    <button
        onclick="toggleTheme()"
        class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80"
        aria-label="Toggle Theme"
    >
        <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        <svg id="theme-toggle-light-icon" class="hidden h-5 w-5 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
        </svg>
    </button>
</div>


<script>
/* =========================
   SCROLL SHOW / HIDE
========================= */
const logoutBtn = document.getElementById('logout-btn');
const systemInfo = document.getElementById('system-info');

window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;

    if (scrollY > 120) {
        // SHOW
        logoutBtn?.classList.remove('opacity-0','translate-y-10','pointer-events-none');
        logoutBtn?.classList.add('opacity-100','translate-y-0');

        systemInfo?.classList.remove('opacity-0','-translate-y-10','pointer-events-none');
        systemInfo?.classList.add('opacity-100','translate-y-0');
    } else {
        // HIDE
        logoutBtn?.classList.add('opacity-0','translate-y-10','pointer-events-none');
        logoutBtn?.classList.remove('opacity-100','translate-y-0');

        systemInfo?.classList.add('opacity-0','-translate-y-10','pointer-events-none');
        systemInfo?.classList.remove('opacity-100','translate-y-0');
    }
});
</script>


<script>
/* =========================
   TIME + DATE
========================= */
function updateTime() {
    const now = new Date();

    document.getElementById('time').innerText =
        now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    document.getElementById('date').innerText =
        now.toLocaleDateString([], { weekday: 'short', day: 'numeric', month: 'short' });
}
setInterval(updateTime, 1000);
updateTime();


/* =========================
   WEATHER
========================= */
async function getWeather() {
    try {
        const res = await fetch("https://api.open-meteo.com/v1/forecast?latitude=31.5204&longitude=74.3587&current_weather=true");
        const data = await res.json();

        document.getElementById('temp').innerText =
            data.current_weather.temperature + "°C";

        document.getElementById('city').innerText = "Lahore";
    } catch {
        document.getElementById('temp').innerText = "--°C";
        document.getElementById('city').innerText = "Offline";
    }
}
getWeather();


/* =========================
   THEME TOGGLE
========================= */
const darkIcon = document.getElementById('theme-toggle-dark-icon');
const lightIcon = document.getElementById('theme-toggle-light-icon');

function reflectTheme() {
    if (document.documentElement.classList.contains('dark')) {
        darkIcon.classList.remove('hidden');
        lightIcon.classList.add('hidden');
    } else {
        lightIcon.classList.remove('hidden');
        darkIcon.classList.add('hidden');
    }
}

reflectTheme();

function toggleTheme() {
    if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }
    reflectTheme();
}
</script>
