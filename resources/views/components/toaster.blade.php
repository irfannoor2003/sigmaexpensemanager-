@php
    // Look for any of these 3 session keys
    $message = session('success') ?? session('error') ?? session('info');

    // Determine the color theme based on the session key
    $typeClass = session('success') ? 'bg-emerald-500/90 border-emerald-400/50' :
                (session('error') ? 'bg-red-500/90 border-red-400/50' :
                'bg-blue-500/90 border-blue-400/50');
@endphp

@if($message)
    <div id="toaster"
        class="fixed top-6 right-[-400px] max-w-xs w-full p-4 rounded-2xl
               backdrop-blur-xl border shadow-2xl z-[9999]
               text-white text-sm font-medium flex justify-between items-center
               transform transition-all duration-700 ease-out
               {{ $typeClass }}">

        <div class="flex items-center gap-3">
            @if(session('success'))
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            @endif
            <span>{{ $message }}</span>
        </div>

        <button onclick="closeToaster()" class="hover:rotate-90 transition-transform duration-300 text-xl leading-none">×</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toaster = document.getElementById('toaster');
            // Slide In
            setTimeout(() => {
                toaster.style.right = '1.5rem';
            }, 500);

            // Auto Hide after 5 seconds
            setTimeout(() => {
                closeToaster();
            }, 5000);
        });

        function closeToaster() {
            const toaster = document.getElementById('toaster');
            if(toaster) {
                toaster.style.right = '-400px';
                setTimeout(() => { toaster.remove(); }, 700);
            }
        }
    </script>
@endif
