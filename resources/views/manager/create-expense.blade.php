<x-app-layout>
    <x-toaster />

    {{-- Back Button (minimal style) --}}
    <a href="{{ route('manager.dashboard') }}"
        class="fixed top-6 left-6 z-50 flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white/80 backdrop-blur-md shadow-sm transition-all hover:scale-110 active:scale-95 dark:border-gray-800 dark:bg-gray-900/80 text-gray-600 dark:text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
    </a>

    <div class="p-4 sm:p-8 min-h-screen flex items-center justify-center">

        <div class="max-w-md w-full">

            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-500/10 text-pink-500 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0-6v2m0 16v2m10-10h-2M4 12H2m15.364 6.364l-1.414-1.414M6.05 6.05 4.636 4.636m12.728 0-1.414 1.414M6.05 17.95l-1.414 1.414" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    New Expense
                </h1>

                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                    Sigma Engineering Access Control
                </p>
            </div>

            <div
                class="mb-6 p-5 rounded-[2rem] bg-white dark:bg-white/5 border border-pink-500/20 shadow-xl backdrop-blur-md relative overflow-hidden group">
                <div
                    class="absolute -right-4 -top-4 w-20 h-20 bg-pink-500/10 rounded-full blur-2xl group-hover:bg-pink-500/20 transition-all">
                </div>

                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-pink-500 font-black">Submission Window</p>
                        <p class="text-xs text-gray-400 mt-0.5">Time left for previous month</p>
                    </div>
                    <div id="timer"
                        class="text-xl font-mono font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-white/10 px-4 py-2 rounded-xl shadow-inner border border-white/5">
                        00d 00h 00m 00s
                    </div>
                </div>
            </div>

            {{-- Card --}}
            <div
                class="rounded-[2.5rem] bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-2xl shadow-indigo-500/5 p-8 backdrop-blur-sm">

                <form action="{{ route('manager.expense') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    {{-- Category --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            Expense Category
                        </label>
                        <select name="category_id" required
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-[#1a1a1a] text-gray-900 dark:text-white focus:ring-2 focus:ring-[#ea258e]/20 focus:border-[#ea258e] outline-none appearance-none transition-all cursor-pointer">

                            <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>
                                Select Category
                            </option>

                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id') == $cat->id ? 'selected' : '' }}
                                    class="bg-white text-black dark:bg-[#1a1a1a] dark:text-white py-2">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Title with Voice --}}
                    <div class="relative">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            Title
                        </label>
                        <input type="text" id="title-field" name="title" required
                            onkeyup="aiSuggestLive(this.value, 'title-field')" placeholder="Fuel"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 outline-none pr-12"
                            value="{{ old('title') }}">
                        <button type="button" onclick="startDictation('title-field')"
                            class="absolute right-3 top-[50px] -translate-y-1/2 h-5 w-5 flex items-center justify-center bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                                <line x1="12" y1="19" x2="12" y2="23" />
                                <line x1="8" y1="23" x2="16" y2="23" />
                            </svg>
                        </button>
                    </div>

                    {{-- Amount --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            Amount (Rs.)
                        </label>
                        <input type="number" name="amount" required placeholder="0.00"
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 outline-none"
                            value="{{ old('amount') }}">
                    </div>

                    {{-- Date --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            Transaction Date
                        </label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}"
                            required
                            class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10
    bg-white dark:bg-black/20
    text-gray-900 dark:text-white
    focus:ring-2 focus:ring-indigo-500/20 outline-none
    [color-scheme:light] dark:[color-scheme:dark]">
                    </div>

                    {{-- Remarks with Voice --}}
                    <div class="relative">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            Remarks
                        </label>
                        <textarea id="remarks-area" name="remarks" rows="3"
    placeholder="Optional notes..."
    class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 outline-none pr-12">{{ old('remarks') }}</textarea>
                        <button type="button" onclick="startDictation('remarks-area')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 h-10 w-10 flex items-center justify-center bg-pink-500 text-white rounded-xl hover:bg-pink-600 transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                                <line x1="12" y1="19" x2="12" y2="23" />
                                <line x1="8" y1="23" x2="16" y2="23" />
                            </svg>
                        </button>
                    </div>

                    {{-- Upload --}}
                    <div>
                        <label class="block text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2 ml-1">
                            Proof of Purchase
                        </label>

                        <div class="flex gap-3">
                            <!-- Upload -->
                            <input type="file" id="fileInput" name="image" accept="image/*"
                                class="w-full px-5 py-4 rounded-2xl border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/20 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500/20 outline-none"
                                required>

                            <!-- 📸 Capture Button -->
                            <button type="button" onclick="openCamera()"
                                class="px-4 py-3 bg-pink-500 text-white rounded-2xl hover:bg-pink-600 transition shadow-md flex items-center justify-center">
                                📸
                            </button>

                        </div>
                        @error('image')
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-pink-400 to-pink-500 text-white rounded-2xl font-bold shadow-xl shadow-pink-500/20 transition-all hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                        Submit Expense
                    </button>

                </form>
            </div>
        </div>
    </div>
    <dialog id="cameraModal" class="p-0 rounded-3xl bg-black/80 backdrop-blur-md border border-white/10">

        <div class="p-5 w-[90vw] max-w-md text-center">
            <video id="camera" autoplay class="w-full rounded-2xl mb-4"></video>

            <div class="flex gap-3">
                <button onclick="capturePhoto()" class="flex-1 py-3 bg-pink-500 text-white rounded-xl font-bold">
                    Capture
                </button>

                <button onclick="closeCamera()" class="flex-1 py-3 bg-gray-300 dark:bg-gray-700 rounded-xl">
                    Cancel
                </button>
            </div>
        </div>
    </dialog>

    {{-- Voice Dictation Script --}}
    <script>
        function startDictation(fieldId) {
            if (window.hasOwnProperty('webkitSpeechRecognition')) {
                const recognition = new webkitSpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = 'en-US';
                recognition.start();

                recognition.onresult = function(e) {
                    const transcript = e.results[0][0].transcript;
                    document.getElementById(fieldId).value = transcript;
                    recognition.stop();
                };
                recognition.onerror = function() {
                    recognition.stop();
                };
            } else {
                alert("Your browser does not support speech recognition.");
            }
        }
    </script>

    <script>
        // Ensure we have a valid ISO string for JS
        const deadline = new Date("{{ \Carbon\Carbon::parse($deadline)->toIso8601String() }}").getTime();
        const timerElement = document.getElementById("timer");
        const submitBtn = document.querySelector('button[type="submit"]');

        function updateTimer() {
            const now = new Date().getTime();
            const distance = deadline - now;

            if (distance < 0) {
                timerElement.innerHTML = "CLOSED";
                timerElement.classList.add('text-red-500');
                // Optional: Block adding previous month dates here via JS if needed
                clearInterval(timerInterval);
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timerElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>

    <script>
        let stream;

        async function openCamera() {
            const modal = document.getElementById('cameraModal');
            const video = document.getElementById('camera');

            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                video.srcObject = stream;
                modal.showModal();
            } catch (err) {
                alert("Camera access denied or not supported.");
            }
        }

        function closeCamera() {
            const modal = document.getElementById('cameraModal');
            modal.close();

            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        }

        function capturePhoto() {
            const video = document.getElementById('camera');
            const canvas = document.createElement('canvas');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);

            canvas.toBlob(blob => {
                const file = new File([blob], "capture.jpg", {
                    type: "image/jpeg"
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);

                document.getElementById('fileInput').files = dataTransfer.files;

                closeCamera();
            }, 'image/jpeg');
        }
    </script>

</x-app-layout>
