<x-app-layout>
    <x-toaster />

    {{-- Minimal Floating Back Button --}}
    <a href="{{ route('manager.dashboard') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full">
                <div class="w-full sm:w-auto">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-widest text-pink-600 dark:text-pink-400 font-bold">
                            {{ __('app.Diary') }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('app.Profiles') }}
                    </h1>
                </div>

                <button onclick="document.getElementById('profileModal').showModal()"
                    class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold uppercase rounded-xl transition-all shadow-lg shadow-pink-500/25 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    {{ __('app.Add Profile') }}
                </button>
            </div>

            {{-- Profiles Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($profiles as $profile)
                    <div class="group relative">
                        <div class="block p-6 rounded-[2rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm hover:shadow-xl hover:border-pink-500/30 transition-all duration-300 overflow-hidden relative">
                            
                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-pink-500/10 text-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300 shadow-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                    </div>
                                    <div class="flex flex-col items-end gap-1">
                                        <span class="px-3 py-1 rounded-full bg-pink-500/10 text-[9px] font-bold uppercase text-pink-600 dark:text-pink-400 tracking-wider">
                                            {{ $profile->entries_count }} {{ __('app.Items') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 group/title">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-pink-500 transition-colors">
                                        {{ $profile->name }}
                                    </h3>
                                    <button onclick="openEditProfileModal({{ $profile }})"
                                        class="p-1.5 rounded-lg bg-gray-100 dark:bg-white/5 text-gray-400 hover:text-indigo-500 hover:bg-indigo-500/10 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                        </svg>
                                    </button>
                                </div>

                                <a href="{{ route('manager.diary.profile.show', $profile) }}" class="block mt-4 space-y-3">
                                    <div class="p-4 bg-gray-50 dark:bg-black/20 rounded-2xl border border-gray-100 dark:border-white/5">
                                        <p class="text-[9px] uppercase font-bold text-gray-400 tracking-widest mb-1">{{ __('app.Total Amount') }}</p>
                                        <p class="text-xl font-bold text-gray-900 dark:text-white">
                                            <span class="text-pink-500 text-sm font-bold">{{ __('app.Rs') }}.</span>
                                            {{ number_format($profile->entries_sum_price ?? 0) }}
                                        </p>
                                    </div>

                                    <div class="space-y-1">
                                        @if($profile->email)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2 font-medium truncate">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-indigo-500 shrink-0">
                                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                                                </svg>
                                                {{ $profile->email }}
                                            </p>
                                        @endif
                                        @if($profile->phone)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-pink-500 shrink-0">
                                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                                </svg>
                                                {{ $profile->phone }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 text-pink-500 text-[10px] font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-all transform translate-y-2 group-hover:translate-y-0">
                                        {{ __('app.View Records') }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <path d="M5 12h14m-7-7 7 7-7 7" />
                                        </svg>
                                    </div>
                                </a>
                            </div>

                            {{-- Decorative background elements --}}
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-pink-500/5 rounded-full blur-3xl group-hover:bg-pink-500/10 transition-all duration-500"></div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 flex flex-col items-center justify-center text-center space-y-4">
                        <div class="w-20 h-20 rounded-3xl bg-gray-100 dark:bg-white/5 flex items-center justify-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <p class="text-gray-500 font-medium">{{ __('app.No profiles found') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $profiles->links() }}
            </div>
        </div>
    </div>

    {{-- Add Profile Modal --}}
    <dialog id="profileModal"
        class="modal p-0 rounded-[2rem] bg-transparent backdrop:bg-black/80 backdrop:backdrop-blur-sm fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 m-0 overflow-visible">
        <div class="bg-white dark:bg-[#0f0f0f] w-[90vw] max-w-md p-8 border border-gray-200 dark:border-white/10 shadow-2xl rounded-[2rem]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('app.Add Profile') }}</h3>
                <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-pink-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" x2="6" y1="6" y2="18" /><line x1="6" x2="18" y1="6" y2="18" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('manager.diary.profile.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Person Name') }}</label>
                    <input type="text" name="name" required placeholder="Hammad Malik"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">Email (Optional)</label>
                    <input type="email" name="email" placeholder="hammad@example.com"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Phone Number') }}</label>
                    <input type="text" name="phone" placeholder="03XXXXXXXXX"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-pink-500 hover:bg-pink-600 text-white font-bold uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-pink-500/30 transition-all active:scale-95">
                    {{ __('app.Add Profile') }}
                </button>
            </form>
        </div>
    </dialog>

    {{-- Edit Profile Modal --}}
    <dialog id="editProfileModal"
        class="modal p-0 rounded-[2rem] bg-transparent backdrop:bg-black/80 backdrop:backdrop-blur-sm fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 m-0 overflow-visible">
        <div class="bg-white dark:bg-[#0f0f0f] w-[90vw] max-w-md p-8 border border-gray-200 dark:border-white/10 shadow-2xl rounded-[2rem]">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">Edit Profile</h3>
                <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-pink-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" x2="6" y1="6" y2="18" /><line x1="6" x2="18" y1="6" y2="18" />
                    </svg>
                </button>
            </div>

            <form id="editProfileForm" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Person Name') }}</label>
                    <input type="text" id="edit-profile-name" name="name" required
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">Email</label>
                    <input type="email" id="edit-profile-email" name="email"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Phone Number') }}</label>
                    <input type="text" id="edit-profile-phone" name="phone"
                        class="w-full bg-gray-50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl px-4 py-3 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>

                <button type="submit"
                    class="w-full py-4 bg-indigo-500 hover:bg-indigo-600 text-white font-bold uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                    Save Changes
                </button>
            </form>
        </div>
    </dialog>

    <script>
        function openEditProfileModal(profile) {
            const modal = document.getElementById('editProfileModal');
            const form = document.getElementById('editProfileForm');
            const nameInput = document.getElementById('edit-profile-name');
            const emailInput = document.getElementById('edit-profile-email');
            const phoneInput = document.getElementById('edit-profile-phone');

            form.action = `/manager/diary/profile/${profile.id}`;
            nameInput.value = profile.name;
            emailInput.value = profile.email || '';
            phoneInput.value = profile.phone || '';

            modal.showModal();
        }
    </script>

    <style>
        .modal::backdrop {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</x-app-layout>
