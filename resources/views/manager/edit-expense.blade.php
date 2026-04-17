<x-app-layout>
    <x-toaster />

    {{-- Back Button --}}
    <a href="{{ route('manager.my-expenses') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen flex items-center justify-center">

        <div class="max-w-md w-full">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-500/10 text-pink-500 mb-4">
                    ✏️
                </div>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{__('app.Edit_Expense')}}
                </h1>

                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                    {{__('app.Update_your')}}
                </p>
            </div>

            {{-- Card --}}
            <div
                class="rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-2xl shadow-indigo-500/5 p-8 backdrop-blur-sm">

                <form action="{{ route('manager.expense.update', $expense->id) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="space-y-6">

                    @csrf
                    @method('PUT')

                    {{-- Category --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            {{__('app.Expense Category')}}
                        </label>

                        @php
    $selectedCategory = old('category_id', $expense->category_id);
@endphp

<select name="category_id" required
    class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-[#1a1a1a] text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 outline-none">

    <option value="" disabled {{ $selectedCategory ? '' : 'selected' }}>
        {{ __('app.Select Category') }}
    </option>

    @foreach ($categories as $cat)
        <option value="{{ $cat->id }}"
            {{ $selectedCategory == $cat->id ? 'selected' : '' }}>

            {{-- multilingual label --}}
            {{ __('app.categories.' . $cat->name) }}

        </option>
    @endforeach

</select>
                    </div>

                    {{-- Title --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            {{__('app.TITLE')}}
                        </label>

                        <input type="text" name="title"
                            value="{{ old('title', $expense->title) }}"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 outline-none"
                            required>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                           {{__('app.Amount (Rs.)')}}
                        </label>

                        <input type="number" name="amount"
                            value="{{ old('amount', $expense->amount) }}"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 outline-none"
                            required>
                    </div>

                    {{-- Date --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            {{__('app.Transaction Date')}}
                        </label>

                        <input type="date" name="expense_date"
                            value="{{ old('expense_date', \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d')) }}"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-white dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 outline-none"
                            required>
                    </div>

                    {{-- Remarks --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            {{__('app.Remarks')}}
                        </label>

                        <textarea name="remarks" rows="3"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-pink-500/20 outline-none">{{ old('remarks', $expense->description) }}</textarea>
                    </div>

                    {{-- Current Image --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            {{__('app.Current_Receipt')}}
                        </label>

                        <img src="{{ asset('storage/' . $expense->image) }}"
                            class="w-full h-40 object-cover rounded-2xl border border-white/10">
                    </div>

                    {{-- New Image --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            {{__('app.Replace Receipt (Optional)')}}
                        </label>

                        <input type="file" name="image"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white">
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-pink-400 to-pink-500 text-white rounded-2xl font-bold shadow-xl shadow-pink-500/20 transition-all hover:-translate-y-0.5 active:scale-95">
                        Update Expense
                    </button>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>
