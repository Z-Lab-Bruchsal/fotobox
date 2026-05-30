<div class="flex flex-col items-center w-full"
     x-data="{ activeTab: 'current', lightbox: null }"
     x-on:keydown.escape.window="lightbox = null">

    {{-- Camera preview --}}
    <div class="relative w-full max-w-2xl">
        <video id="video" autoplay playsinline
               class="w-full rounded-2xl shadow-2xl bg-gray-900 aspect-video object-cover" style="transform: scaleX(-1);"></video>

        <canvas id="canvas" class="hidden"></canvas>

        <div id="flash" class="absolute inset-0 rounded-2xl bg-white opacity-0 pointer-events-none transition-opacity duration-150"></div>

        <div id="countdown" class="absolute inset-0 rounded-2xl hidden items-center justify-center bg-black/40">
            <span id="countdown-num" class="text-white font-bold drop-shadow-lg" style="font-size: 8rem; line-height: 1;"></span>
        </div>
    </div>

    <button id="capture-btn"
            wire:loading.attr="disabled"
            @disabled($hasProcessing)
            class="mt-8 px-10 py-4 bg-white text-gray-950 font-semibold text-lg rounded-full shadow-lg hover:bg-gray-200 active:scale-95 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
        <span wire:loading.remove>{{ $hasProcessing ? 'Erstelle Varianten...' : 'Foto aufnehmen' }}</span>
        <span wire:loading>Speichern…</span>
    </button>

    <div class="mt-4 text-sm text-gray-400 h-6">{{ $status }}</div>

    {{-- Tabbed photo sessions --}}
    @if ($currentSession || $previousSessions->isNotEmpty())
        <div class="mt-10 w-full max-w-4xl" @if($hasProcessing) wire:poll.2000ms @endif>

            {{-- Tab navigation --}}
            <div class="flex gap-1 mb-4 border-b border-gray-800 overflow-x-auto">
                <button x-on:click="activeTab = 'current'"
                        :class="activeTab === 'current' ? 'border-b-2 border-white text-white' : 'text-gray-500 hover:text-gray-300'"
                        class="pb-2 px-4 text-sm font-medium whitespace-nowrap transition-colors">
                    Aktuell
                </button>
                @foreach ($previousSessions as $session)
                    <button x-on:click="activeTab = 'session{{ $session->id }}'"
                            :class="activeTab === 'session{{ $session->id }}' ? 'border-b-2 border-white text-white' : 'text-gray-500 hover:text-gray-300'"
                            class="pb-2 px-4 text-sm font-medium whitespace-nowrap transition-colors">
                        #{{ $session->id }}
                    </button>
                @endforeach
            </div>

            {{-- Current session tab --}}
            <div x-show="activeTab === 'current'">
                @if ($currentSession)
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">

                        {{-- Original for comparison --}}
                        <div class="relative group">
                            <img src="{{ Storage::disk('public')->url($currentSession->original_image_path) }}"
                                 alt="Original"
                                 class="w-full aspect-square object-cover rounded-xl border-2 border-blue-500 cursor-pointer"
                                 x-on:click="lightbox = {{ Js::from(['url' => Storage::disk('public')->url($currentSession->original_image_path)]) }}">
                            <span class="absolute bottom-2 left-2 text-xs px-2 py-0.5 rounded-full bg-blue-600/80 text-white font-medium">
                                Original
                            </span>
                        </div>

                        {{-- Variants --}}
                        @foreach ($currentSession->jobs as $job)
                            <div class="relative group cursor-pointer"
                                 wire:key="job-{{ $job->id }}"
                                 @if($job->status === 'done') x-on:click="$wire.selectVariant({{ $currentSession->id }}, {{ $job->id }})" @endif>

                                @if ($job->status === 'processing')
                                    <div class="w-full aspect-square rounded-xl border border-gray-700 bg-gray-900 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                        </svg>
                                    </div>
                                @elseif ($job->status === 'failed')
                                    <div class="w-full aspect-square rounded-xl border border-red-900 bg-gray-900 flex items-center justify-center">
                                        <span class="text-red-500 text-xs">Fehler</span>
                                    </div>
                                @else
                                    <img src="{{ Storage::disk('public')->url($job->image_path) }}?v={{ $job->updated_at->timestamp }}"
                                         alt="{{ $job->setting?->name }}"
                                         class="w-full aspect-square object-cover rounded-xl border-2 transition-colors {{ $currentSession->selected_job_id === $job->id ? 'border-green-500' : 'border-gray-700 group-hover:border-gray-400' }}">
                                    @if ($currentSession->selected_job_id === $job->id)
                                        <div class="absolute top-2 right-2 p-1 rounded-full bg-green-500 shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    @endif
                                @endif

                                <span class="absolute bottom-2 left-2 text-xs px-2 py-0.5 rounded-full bg-black/60 text-yellow-400 font-medium">
                                    {{ $job->setting?->name ?? 'Filter' }}
                                </span>
                            </div>
                        @endforeach

                    </div>

                    <button wire:click="deleteSession({{ $currentSession->id }})" wire:confirm="Diese Session löschen?"
                            class="mt-4 px-4 py-1.5 text-xs rounded-lg bg-gray-900 text-gray-500 hover:text-red-400 hover:bg-gray-800 transition-colors border border-gray-800">
                        Session löschen
                    </button>
                @else
                    <p class="text-gray-600 text-sm text-center py-12">Noch kein Foto aufgenommen.</p>
                @endif
            </div>

            {{-- Previous session tabs --}}
            @foreach ($previousSessions as $session)
                <div x-show="activeTab === 'session{{ $session->id }}'">
                    @php
                        $displayJob = $session->selected_job_id
                            ? $session->jobs->firstWhere('id', $session->selected_job_id)
                            : $session->jobs->where('status', 'done')->first();
                    @endphp

                    @if ($displayJob)
                        <img src="{{ Storage::disk('public')->url($displayJob->image_path) }}?v={{ $displayJob->updated_at->timestamp }}"
                             alt="Session #{{ $session->id }}"
                             class="w-full max-w-lg mx-auto rounded-2xl border border-gray-700 cursor-pointer mb-4"
                             x-on:click="lightbox = {{ Js::from(['url' => Storage::disk('public')->url($displayJob->image_path) . '?v=' . $displayJob->updated_at->timestamp]) }}">
                    @endif

                    {{-- Variant thumbnails --}}
                    @if ($session->jobs->count() > 1)
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-2">
                            @foreach ($session->jobs->where('status', 'done') as $job)
                                <div class="relative cursor-pointer group"
                                     wire:key="prev-job-{{ $job->id }}"
                                     x-on:click="$wire.selectVariant({{ $session->id }}, {{ $job->id }})">
                                    <img src="{{ Storage::disk('public')->url($job->image_path) }}?v={{ $job->updated_at->timestamp }}"
                                         alt="{{ $job->setting?->name }}"
                                         class="w-full aspect-square object-cover rounded-lg border-2 transition-colors {{ $session->selected_job_id === $job->id ? 'border-green-500' : 'border-gray-700 group-hover:border-gray-500' }}">
                                    <span class="absolute bottom-1 left-1 text-[10px] px-1 py-0.5 rounded bg-black/60 text-yellow-400 truncate max-w-[90%]">
                                        {{ $job->setting?->name ?? 'Filter' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <button wire:click="deleteSession({{ $session->id }})" wire:confirm="Session #{{ $session->id }} löschen?"
                            class="mt-4 px-4 py-1.5 text-xs rounded-lg bg-gray-900 text-gray-500 hover:text-red-400 hover:bg-gray-800 transition-colors border border-gray-800">
                        Session löschen
                    </button>
                </div>
            @endforeach

        </div>
    @endif

    {{-- Lightbox --}}
    <div x-show="lightbox"
         wire:ignore
         x-on:click.self="lightbox = null"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-6"
         style="display:none">
        <div class="relative max-w-2xl w-full">
            <img x-bind:src="lightbox?.url" alt="Photo" class="w-full rounded-2xl shadow-2xl">
            <div class="absolute bottom-4 right-4 rounded-xl overflow-hidden shadow-2xl bg-white p-1">
                <canvas x-ref="qrCanvas"
                        x-effect="lightbox && $nextTick(() => window.generateQR(lightbox.url, $refs.qrCanvas))"></canvas>
            </div>
            <button x-on:click="lightbox = null"
                    class="absolute top-3 right-3 p-1.5 rounded-full bg-black/60 text-white hover:bg-black/80">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>

    @script
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const btn = document.getElementById('capture-btn');
        const flash = document.getElementById('flash');
        const countdown = document.getElementById('countdown');
        const countdownNum = document.getElementById('countdown-num');

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 640, height: 480 }, audio: false });
                video.srcObject = stream;
            } catch {
                $wire.set('status', 'Camera access denied or unavailable.');
                btn.disabled = true;
            }
        }

        function triggerFlash() {
            flash.style.opacity = '0.8';
            setTimeout(() => flash.style.opacity = '0', 150);
        }

        function delay(ms) {
            return new Promise(r => setTimeout(r, ms));
        }

        btn.addEventListener('click', async () => {
            if (!video.srcObject || btn.disabled) return;

            btn.disabled = true;
            countdown.classList.remove('hidden');
            countdown.classList.add('flex');

            for (let i = 5; i >= 1; i--) {
                countdownNum.textContent = i;
                await delay(1000);
            }

            countdown.classList.add('hidden');
            countdown.classList.remove('flex');

            triggerFlash();

            canvas.width = 640;
            canvas.height = 480;
            canvas.getContext('2d').drawImage(video, 0, 0, 640, 480);
            const imageData = canvas.toDataURL('image/jpeg', 0.9);

            await $wire.capture(imageData);
        });

        startCamera();
    </script>
    @endscript
</div>
