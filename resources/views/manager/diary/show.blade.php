<x-app-layout>
    <x-toaster />

    {{-- Minimal Floating Back Button --}}
    <a href="{{ route('manager.diary.index') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen transition-colors duration-500">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="flex h-2 w-2 rounded-full bg-pink-500 animate-pulse"></span>
                        <span class="text-[10px] uppercase tracking-widest font-bold text-pink-600 dark:text-pink-400">
                            {{ __('app.Diary Entries') }}
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $profile->name }}
                    </h1>
                    <div class="mt-4 flex flex-wrap gap-4">
                        @php
                            $summaryQuery = $profile->entries()->where('is_cleared', false);
                            $clearedQuery = $profile->entries()->where('is_cleared', true);

                            if(request('month')) {
                                $date = \Carbon\Carbon::parse(request('month'));
                                $summaryQuery->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year);
                                $clearedQuery->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year);
                            }
                        @endphp
                        <div class="px-4 py-3 bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
                            <p class="text-[9px] uppercase font-bold text-gray-400 tracking-widest mb-1">
                                {{ request('month') ? \Carbon\Carbon::parse(request('month'))->format('M Y') : '' }} {{ __('app.Pending Balance') }}
                            </p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white leading-none">
                                <span class="text-pink-500 text-sm font-bold">{{ __('app.Rs') }}.</span>
                                {{ number_format($summaryQuery->sum('price')) }}
                            </p>
                        </div>
                        <div class="px-4 py-3 bg-white dark:bg-white/5 rounded-2xl border border-gray-100 dark:border-white/10 shadow-sm">
                            <p class="text-[9px] uppercase font-bold text-gray-400 tracking-widest mb-1">
                                {{ request('month') ? \Carbon\Carbon::parse(request('month'))->format('M Y') : '' }} {{ __('app.Total Cleared') }}
                            </p>
                            <p class="text-2xl font-bold text-emerald-500 leading-none">
                                <span class="text-emerald-500/50 text-sm font-bold">{{ __('app.Rs') }}.</span>
                                {{ number_format($clearedQuery->sum('price')) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <a href="{{ route('manager.diary.export', ['profile' => $profile, 'status' => request('status'), 'month' => request('month')]) }}"
                        class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 text-gray-700 dark:text-gray-300 text-[11px] font-bold uppercase rounded-xl transition-all hover:bg-gray-50 dark:hover:bg-white/10 shadow-sm active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>
                        </svg>
                        {{ __('app.Export Filtered') }}
                    </a>

                    <button onclick="document.getElementById('entryModal').showModal()"
                        class="w-full sm:w-auto flex justify-center items-center gap-2 px-5 py-2.5 bg-pink-500 hover:bg-pink-600 text-white text-[11px] font-bold uppercase rounded-xl transition-all shadow-lg shadow-pink-500/25 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        {{ __('app.Add Entry') }}
                    </button>
                </div>
            </div>

            {{-- Filter Bar --}}
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                {{-- Status Tabs --}}
                <div class="flex items-center gap-2 p-1.5 bg-gray-100 dark:bg-white/5 rounded-2xl w-fit">
                    <a href="{{ route('manager.diary.profile.show', ['profile' => $profile, 'month' => request('month')]) }}"
                        class="px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ !request('status') ? 'bg-white dark:bg-white/10 text-pink-500 shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                        All
                    </a>
                    <a href="{{ route('manager.diary.profile.show', ['profile' => $profile, 'status' => 'pending', 'month' => request('month')]) }}"
                        class="px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ request('status') === 'pending' ? 'bg-white dark:bg-white/10 text-pink-500 shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                        Pending
                    </a>
                    <a href="{{ route('manager.diary.profile.show', ['profile' => $profile, 'status' => 'cleared', 'month' => request('month')]) }}"
                        class="px-6 py-2 rounded-xl text-[10px] font-bold uppercase tracking-widest transition-all {{ request('status') === 'cleared' ? 'bg-white dark:bg-white/10 text-pink-500 shadow-sm' : 'text-gray-500 hover:text-gray-900 dark:hover:text-white' }}">
                        Cleared
                    </a>
                </div>

                {{-- Month Filter --}}
                <div class="flex items-center gap-3">
                    <div class="relative group">
                        <input type="month" id="monthFilter" value="{{ request('month') }}"
                            onchange="updateMonthFilter(this.value)"
                            class="bg-gray-100 dark:bg-white/5 border-none rounded-2xl px-5 py-2.5 text-[10px] font-bold uppercase tracking-widest text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-pink-500/20 outline-none transition-all">
                    </div>

                    <a href="{{ route('manager.diary.profile.show', $profile) }}"
                        class="flex items-center gap-2 px-5 py-2.5 bg-gray-200 dark:bg-white/10 text-gray-600 dark:text-gray-400 text-[10px] font-bold uppercase tracking-widest rounded-2xl hover:bg-rose-500 hover:text-white transition-all shadow-sm group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="group-hover:rotate-180 transition-transform duration-500">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset Filters
                    </a>
                </div>
            </div>

            <script>
                function updateMonthFilter(month) {
                    const url = new URL(window.location.href);
                    url.searchParams.set('month', month);
                    window.location.href = url.toString();
                }
            </script>

            {{-- Entries Desktop Table --}}
            <div class="hidden sm:block bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[2rem] overflow-hidden shadow-xl backdrop-blur-md relative">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead>
                            <tr class="text-gray-400 border-b border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/[0.02]">
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[10px]">{{ __('app.Photo') }}</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[10px]">{{ __('app.Title') }}</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[10px]">{{ __('app.Price') }}</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[10px]">{{ __('app.STATUS') }}</th>
                                <th class="px-8 py-6 font-bold tracking-widest uppercase text-[10px] text-right">{{ __('app.ACTIONS') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            @forelse($entries as $entry)
                                <tr class="group hover:bg-pink-500/[0.02] transition-all duration-300 {{ $entry->is_cleared ? 'opacity-60 bg-emerald-500/[0.02]' : '' }}">
                                    <td class="px-8 py-6">
                                        @if($entry->image)
                                            <div class="relative w-16 h-16 rounded-2xl overflow-hidden border-2 border-white dark:border-white/10 shadow-lg group-hover:scale-105 transition-all cursor-pointer" onclick="openLightbox('{{ asset('storage/' . $entry->image) }}')">
                                                <img src="{{ asset('storage/' . $entry->image) }}" class="w-full h-full object-cover">
                                            </div>
                                        @else
                                            <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-300 border border-gray-200 dark:border-white/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900 dark:text-white text-base leading-tight {{ $entry->is_cleared ? 'line-through text-emerald-600/70' : '' }}">{{ $entry->title }}</span>
                                            <span class="text-[9px] uppercase text-gray-400 font-bold tracking-widest mt-1">{{ __('app.Parchi') }} #{{ $entry->id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-pink-500/5 border border-pink-500/10">
                                            <span class="text-pink-500 text-[10px] font-bold">{{ __('app.Rs') }}.</span>
                                            <span class="font-bold text-gray-900 dark:text-white text-lg {{ $entry->is_cleared ? 'line-through opacity-50' : '' }}">{{ number_format($entry->price) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @if($entry->is_cleared)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-500 text-[10px] font-bold uppercase tracking-wider shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                    <polyline points="20 6 9 17 4 12"/>
                                                </svg>
                                                {{ __('Cleared') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-bold uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                {{ __('Pending') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex justify-end gap-3">
                                            @if(!$entry->is_cleared)
                                                <form action="{{ route('manager.diary.entry.toggle-clear', $entry) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" title="Mark as Cleared"
                                                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white hover:scale-110 active:scale-95 transition-all shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                                            <polyline points="20 6 9 17 4 12"/>
                                                        </svg>
                                                    </button>
                                                </form>

                                                <button onclick="openEditModal({{ $entry }})" class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 hover:bg-indigo-500 hover:text-white hover:scale-110 active:scale-95 transition-all shadow-sm">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                                    </svg>
                                                </button>

                                                <form action="{{ route('manager.diary.entry.destroy', $entry) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white hover:scale-110 active:scale-95 transition-all shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                            <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="h-10 px-4 flex items-center justify-center rounded-xl bg-emerald-500/5 text-emerald-500/50 text-[10px] font-bold uppercase tracking-widest border border-emerald-500/10 italic">
                                                    Permanent Record
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-32 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm font-bold uppercase tracking-widest">{{ __('app.No records for this filter') }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Entries Mobile Cards --}}
            <div class="sm:hidden space-y-6">
                @forelse($entries as $entry)
                    <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-[2rem] p-6 shadow-sm space-y-5 transition-all {{ $entry->is_cleared ? 'opacity-80 bg-emerald-500/[0.01]' : '' }}">
                        <div class="flex items-center gap-4">
                            @if($entry->image)
                                <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-white dark:border-white/10 shadow-md" onclick="openLightbox('{{ asset('storage/' . $entry->image) }}')">
                                    <img src="{{ asset('storage/' . $entry->image) }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/10 flex items-center justify-center text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-gray-900 dark:text-white {{ $entry->is_cleared ? 'line-through text-emerald-600/70' : '' }}">{{ $entry->title }}</h4>
                                    @if($entry->is_cleared)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-500 text-[8px] font-bold uppercase tracking-widest border border-emerald-500/20">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Cleared
                                        </span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ __('app.Parchi') }} #{{ $entry->id }}</p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-4 border-y border-gray-50 dark:border-white/5">
                            <div>
                                <p class="text-[9px] uppercase font-bold text-gray-400 tracking-widest">{{ __('app.Price') }}</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white {{ $entry->is_cleared ? 'line-through opacity-50' : '' }}">
                                    <span class="text-pink-500 text-xs font-bold">{{ __('app.Rs') }}.</span>
                                    {{ number_format($entry->price) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] uppercase font-bold text-gray-400 tracking-widest">{{ __('app.DATE') }}</p>
                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $entry->created_at->format('d M, Y') }}</p>
                            </div>
                        </div>

                        @if(!$entry->is_cleared)
                            <div class="grid grid-cols-3 gap-3">
                                <form action="{{ route('manager.diary.entry.toggle-clear', $entry) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full py-3 flex items-center justify-center rounded-xl bg-emerald-500/10 text-emerald-500 border border-emerald-500/20 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </button>
                                </form>
                                <button onclick="openEditModal({{ $entry }})" class="w-full py-3 flex items-center justify-center rounded-xl bg-indigo-500/10 text-indigo-500 border border-indigo-500/20 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/>
                                    </svg>
                                </button>
                                <form action="{{ route('manager.diary.entry.destroy', $entry) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-3 flex items-center justify-center rounded-xl bg-rose-500/10 text-rose-500 border border-rose-500/20 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="py-3 text-center rounded-xl bg-emerald-500/5 text-emerald-500 text-[10px] font-bold uppercase tracking-widest border border-emerald-500/10 shadow-inner">
                                Transaction Finalized
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="py-20 text-center opacity-30">
                        <p class="text-sm font-bold uppercase tracking-widest">{{ __('app.No records for this filter') }}</p>
                    </div>
                @endforelse
            </div>
            {{-- Pagination --}}
            <div class="mt-10 px-4">
                {{ $entries->links() }}
            </div>
        </div>
    </div>

    {{-- Add Entry Modal --}}
    <dialog id="entryModal"
        class="modal p-0 rounded-[2.5rem] bg-transparent backdrop:bg-black/90 backdrop:backdrop-blur-md fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 m-0 overflow-visible">
        <div class="bg-white dark:bg-[#0f0f0f] w-[95vw] max-w-xl p-8 border border-gray-200 dark:border-white/10 shadow-2xl rounded-[2.5rem]">
            <div class="flex justify-between items-center mb-4">
                <div class="flex flex-col">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('app.Add Entry') }}</h3>
                    <span class="text-[10px] font-bold text-pink-500 uppercase tracking-[0.2em] mt-1">
                        {{ __('app.Parchi') }} #{{ \App\Models\DiaryEntry::max('id') + 1 }}
                    </span>
                </div>
                <button onclick="this.closest('dialog').close()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-white/10 text-gray-400 hover:text-pink-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" x2="6" y1="6" y2="18" /><line x1="6" x2="18" y1="6" y2="18" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('manager.diary.entry.store', $profile) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="relative">
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Title') }}</label>
                    <div class="relative flex items-center">
                        <input type="text" id="entry-title" name="title" required placeholder="{{ __('app.New Expense') }}"
                            class="w-full bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl px-5 py-4 pr-16 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all font-bold">
                        <button type="button" onclick="startDictation('entry-title')" class="absolute right-3 w-10 h-10 flex items-center justify-center rounded-xl bg-pink-500 text-white shadow-lg hover:bg-pink-600 transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" /><path d="M19 10v2a7 7 0 0 1-14 0v-2" /><line x1="12" y1="19" x2="12" y2="23" /><line x1="8" y1="23" x2="16" y2="23" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Price') }}</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-pink-500 font-bold text-sm">{{ __('app.Rs') }}.</span>
                            <input type="number" name="price" required placeholder="0.00"
                                class="w-full bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-5 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 outline-none transition-all font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Photo') }}</label>
                        <div class="flex gap-2">
                            <input type="file" id="fileInput" name="image" accept="image/*" class="hidden">
                            <button type="button" onclick="document.getElementById('fileInput').click()" class="flex-1 bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl px-4 py-4 text-[10px] font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-100 transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-pink-500">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                                </svg>
                                <span class="truncate" id="fileNameDisplay">Upload</span>
                            </button>
                            <button type="button" onclick="openCamera('fileInput', 'fileNameDisplay')" class="w-14 h-14 rounded-2xl bg-indigo-500 text-white flex items-center justify-center shadow-lg hover:bg-indigo-600 transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-pink-500 hover:bg-pink-600 text-white font-bold uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-pink-500/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    {{ __('app.Add Entry') }}
                </button>
            </form>
        </div>
    </dialog>

    {{-- Edit Entry Modal --}}
    <dialog id="editEntryModal"
        class="modal p-0 rounded-[2.5rem] bg-transparent backdrop:bg-black/90 backdrop:backdrop-blur-md fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 m-0 overflow-visible">
        <div class="bg-white dark:bg-[#0f0f0f] w-[95vw] max-w-xl p-8 border border-gray-200 dark:border-white/10 shadow-2xl rounded-[2.5rem]">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ __('app.Update Entry') }}</h3>
                <button onclick="this.closest('dialog').close()" class="text-gray-400 hover:text-pink-500 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                        <line x1="18" x2="6" y1="6" y2="18" /><line x1="6" x2="18" y1="6" y2="18" />
                    </svg>
                </button>
            </div>

            <form id="editEntryForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Title') }}</label>
                    <input type="text" id="edit-entry-title" name="title" required class="w-full bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl px-5 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-bold">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Price') }}</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-indigo-500 font-bold text-sm">{{ __('app.Rs') }}.</span>
                            <input type="number" id="edit-entry-price" name="price" required class="w-full bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-white/10 rounded-2xl pl-12 pr-5 py-4 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all font-bold">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-2 ml-1">{{ __('app.Replace Receipt (Optional)') }}</label>
                        <div class="flex gap-2">
                            <input type="file" id="editFileInput" name="image" accept="image/*" class="hidden">
                            <button type="button" onclick="document.getElementById('editFileInput').click()" class="flex-1 bg-gray-50 dark:bg-white/5 border-2 border-dashed border-gray-200 dark:border-white/10 rounded-2xl px-4 py-4 text-[10px] font-bold text-gray-500 dark:text-gray-400 hover:bg-gray-100 transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="text-indigo-500">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/>
                                </svg>
                                <span class="truncate" id="editFileNameDisplay">Update Photo</span>
                            </button>
                            <button type="button" onclick="openCamera('editFileInput', 'editFileNameDisplay')" class="w-14 h-14 rounded-2xl bg-indigo-500 text-white flex items-center justify-center shadow-lg hover:bg-indigo-600 transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full py-4 bg-indigo-500 hover:bg-indigo-600 text-white font-bold uppercase text-xs tracking-widest rounded-2xl shadow-lg shadow-indigo-500/30 transition-all active:scale-95">
                    {{ __('app.Update Entry') }}
                </button>
            </form>
        </div>
    </dialog>

    {{-- Camera Modal --}}
    <dialog id="cameraModal" class="modal p-0 rounded-[2.5rem] bg-black border border-white/10 overflow-hidden shadow-2xl fixed left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 m-0">
        <div class="relative w-[95vw] max-w-2xl aspect-[3/4] bg-black">
            <video id="cameraStream" autoplay playsinline class="w-full h-full object-cover"></video>
            <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-black/80 to-transparent flex flex-col items-center gap-6">
                <div class="flex items-center justify-between w-full">
                    <button onclick="closeCamera()" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18" /><line x1="6" x2="18" y1="6" y2="18" /></svg></button>
                    <button onclick="capturePhoto()" class="w-20 h-20 rounded-full bg-white p-1 shadow-2xl transition-transform active:scale-90"><div class="w-full h-full rounded-full border-4 border-black/10 flex items-center justify-center"><div class="w-14 h-14 rounded-full bg-pink-500 border-2 border-white shadow-inner"></div></div></button>
                    <button onclick="switchCamera()" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-md"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6M1 20v-6h6M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" /></svg></button>
                </div>
            </div>
        </div>
    </dialog>

    {{-- Lightbox --}}
    <div id="lightbox" class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-2xl hidden items-center justify-center p-4 overflow-hidden" onclick="closeLightbox()">
        <img id="lightbox-img" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl scale-95 transition-transform duration-300 border-4 border-white/10">
    </div>

    <script>
        function startDictation(fieldId) {
            if (window.hasOwnProperty('webkitSpeechRecognition')) {
                const recognition = new webkitSpeechRecognition();
                recognition.onresult = (e) => { document.getElementById(fieldId).value = e.results[0][0].transcript; recognition.stop(); };
                recognition.start();
            }
        }
        let currentStream = null, useFrontCamera = false, targetInputId = '', targetDisplayId = '';
        async function openCamera(inputId, displayId) {
            targetInputId = inputId; targetDisplayId = displayId;
            document.getElementById('cameraModal').showModal(); await startStream();
        }
        async function startStream() {
            if (currentStream) currentStream.getTracks().forEach(track => track.stop());
            const constraints = { video: { facingMode: useFrontCamera ? "user" : "environment" } };
            try {
                currentStream = await navigator.mediaDevices.getUserMedia(constraints);
                document.getElementById('cameraStream').srcObject = currentStream;
            } catch (err) { alert("Camera error"); }
        }
        function switchCamera() { useFrontCamera = !useFrontCamera; startStream(); }
        function closeCamera() { if (currentStream) currentStream.getTracks().forEach(track => track.stop()); document.getElementById('cameraModal').close(); }
        function capturePhoto() {
            const video = document.getElementById('cameraStream'), canvas = document.createElement('canvas');
            canvas.width = video.videoWidth; canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            canvas.toBlob(blob => {
                const file = new File([blob], "capture.jpg", { type: "image/jpeg" }), dataTransfer = new DataTransfer();
                dataTransfer.items.add(file); document.getElementById(targetInputId).files = dataTransfer.files;
                document.getElementById(targetDisplayId).innerText = "Captured ✅"; closeCamera();
            }, 'image/jpeg');
        }
        function openEditModal(entry) {
            const modal = document.getElementById('editEntryModal');
            document.getElementById('editEntryForm').action = `/manager/diary/entry/${entry.id}`;
            document.getElementById('edit-entry-title').value = entry.title;
            document.getElementById('edit-entry-price').value = entry.price;
            modal.showModal();
        }
        function openLightbox(src) {
            const lb = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = src;
            lb.classList.remove('hidden'); lb.classList.add('flex');
            setTimeout(() => document.getElementById('lightbox-img').classList.remove('scale-95'), 10);
        }
        function closeLightbox() {
            document.getElementById('lightbox-img').classList.add('scale-95');
            setTimeout(() => { document.getElementById('lightbox').classList.add('hidden'); document.getElementById('lightbox').classList.remove('flex'); }, 300);
        }
    </script>
</x-app-layout>
