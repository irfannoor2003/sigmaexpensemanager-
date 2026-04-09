<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sigma Expense Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Tailwind & Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark mode initialization -->
    <script>
        if (localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased bg-gray-50 dark:bg-[#050505] transition-colors duration-500 font-poppins">

    <x-theme-toggle />

    {{-- 🌈 Global Background Blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute w-[300px] h-[300px] sm:w-[500px] sm:h-[500px]
            bg-pink-500/2 dark:bg-pink-500/5 blur-[100px] sm:blur-[120px]
            top-[-120px] left-[-120px] rounded-full">
        </div>

        <div class="absolute w-[250px] h-[250px] sm:w-[400px] sm:h-[400px]
            bg-purple-500/2 dark:bg-[#d6007b]/5 blur-[100px] sm:blur-[120px]
            bottom-[-120px] right-[-120px] rounded-full">
        </div>
    </div>

    {{-- 🔔 Toaster --}}
    <x-toaster />

    {{-- 🌐 Main Content --}}
    <main class="relative z-10 min-h-screen">
        {{ $slot }}
    </main>

</body>

</html>

