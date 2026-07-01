<div class="flex flex-col items-center w-full"
     x-data="{ lightbox: null, deleteConfirmId: null }"
     x-on:keydown.escape.window="lightbox = null; deleteConfirmId = null">

    <div class="flex flex-col lg:flex-row lg:items-start gap-6 w-full max-w-6xl justify-center">
        <div class="flex flex-col items-center w-full lg:w-auto">
            <div class="relative w-full max-w-2xl">
                <video id="video" autoplay playsinline
                       class="w-full rounded-2xl shadow-2xl bg-gray-900 aspect-video object-cover" style="transform: scaleX(-1);"></video>

                <canvas id="canvas" class="hidden"></canvas>

                <div id="flash" class="absolute inset-0 rounded-2xl bg-white opacity-0 pointer-events-none transition-opacity duration-150"></div>

                <div id="countdown" class="absolute inset-0 rounded-2xl hidden items-center justify-center bg-black/40">
                    <span id="countdown-num" class="text-white font-bold drop-shadow-lg" style="font-size: 8rem; line-height: 1;"></span>
                </div>
            </div>

            <div class="mt-6 w-full max-w-xs">
                <select wire:model="photoprofileId"
                        class="w-full rounded-full bg-black/50 text-white border border-white/20 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none text-center">
                    @foreach ($photoprofiles as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="camera-select-wrap" class="mt-3 w-full max-w-xs hidden">
                <select id="camera-select"
                        class="w-full rounded-full bg-black/50 text-white border border-white/20 px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-white/40 appearance-none text-center"></select>
            </div>

            <button id="capture-btn"
                    wire:loading.attr="disabled"
                    @disabled($hasProcessing)
                    class="mt-8 px-10 py-4 bg-white text-gray-950 font-semibold text-lg rounded-full shadow-lg hover:bg-gray-200 active:scale-95 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove>{{ $hasProcessing ? 'Erstelle Bild...' : 'Foto aufnehmen' }}</span>
                <span wire:loading>Speichere…</span>
            </button>

            <div class="mt-4 text-sm text-gray-400 h-6">{{ $status }}</div>
        </div>

        @if ($recentPhotos->isNotEmpty())
            <div class="w-full lg:w-64 lg:shrink-0">
                <h2 class="text-sm font-medium text-gray-400 mb-3 tracking-wide uppercase">Fotos</h2>
                <div class="grid grid-cols-5 lg:grid-cols-3 gap-2" @if($hasProcessing) wire:poll.2000ms @endif>
                    @foreach ($recentPhotos as $photo)
                        <div class="relative">
                            @if ($photo['status'] === 'processing')
                                <div class="relative w-full aspect-square">
                                    <img src="{{ $photo['url'] }}" alt="Photo #{{ $photo['id'] }}"
                                         class="w-full aspect-square object-cover rounded-lg border border-gray-700">
                                    <div class="absolute inset-0 rounded-lg bg-black/50 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                        </svg>
                                    </div>
                                </div>
                            @else
                                <img src="{{ $photo['url'] }}" alt="Photo #{{ $photo['id'] }}"
                                     class="w-full aspect-square object-cover rounded-lg border border-gray-700 cursor-pointer"
                                     x-on:click="lightbox = {{ Js::from($photo) }}">
                                <button x-on:click="deleteConfirmId = {{ $photo['id'] }}"
                                        class="absolute top-1 right-1 p-1 rounded bg-black/60 text-white hover:bg-red-600/80 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            @endif
                            <span class="absolute bottom-1 left-1 text-[10px] px-1.5 py-0.5 rounded bg-black/60 text-yellow-400">
                                {{ $photo['status'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Delete confirmation --}}
    <div x-show="deleteConfirmId"
         wire:ignore
         x-on:click.self="deleteConfirmId = null"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-6"
         style="display:none">
        <div class="max-w-sm w-full rounded-2xl bg-gray-900 border border-white/10 p-6 shadow-2xl text-center">
            <p class="text-white mb-6">Foto wirklich löschen?</p>
            <div class="flex justify-center gap-3">
                <button x-on:click="deleteConfirmId = null"
                        class="px-5 py-2 rounded-full bg-white/10 text-white hover:bg-white/20 transition-colors">
                    Abbrechen
                </button>
                <button x-on:click="$wire.delete(deleteConfirmId); deleteConfirmId = null"
                        class="px-5 py-2 rounded-full bg-red-600 text-white hover:bg-red-500 transition-colors">
                    Löschen
                </button>
            </div>
        </div>
    </div>

    {{-- Lightbox --}}
    <div x-show="lightbox"
         wire:ignore
         x-on:click.self="lightbox = null"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-6"
         style="display:none">
        <div class="relative max-w-2xl w-full">
            <img x-bind:src="lightbox?.url" alt="Photo"
                 class="w-full rounded-2xl shadow-2xl">
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
        const cameraSelectWrap = document.getElementById('camera-select-wrap');
        const cameraSelect = document.getElementById('camera-select');

        const CAMERA_STORAGE_KEY = 'photobox_camera_id';

        function videoConstraints(deviceId) {
            const base = { width: { ideal: 2048 }, height: { ideal: 1080 } };
            return deviceId ? { ...base, deviceId: { exact: deviceId } } : base;
        }

        async function startCamera(deviceId) {
            if (video.srcObject) {
                video.srcObject.getTracks().forEach(track => track.stop());
            }

            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: videoConstraints(deviceId),
                    audio: false,
                });
                video.srcObject = stream;
                btn.disabled = false;
            } catch (err) {
                if (deviceId && err.name === 'OverconstrainedError') {
                    localStorage.removeItem(CAMERA_STORAGE_KEY);
                    return startCamera(null);
                }
                $wire.set('status', 'Camera access denied or unavailable.');
                btn.disabled = true;
            }
        }

        async function populateCameraList() {
            let devices;
            try {
                devices = await navigator.mediaDevices.enumerateDevices();
            } catch {
                return;
            }

            const cameras = devices.filter(d => d.kind === 'videoinput');
            if (cameras.length < 2) {
                cameraSelectWrap.classList.add('hidden');
                return;
            }

            cameraSelect.innerHTML = '';
            cameras.forEach((cam, index) => {
                const option = document.createElement('option');
                option.value = cam.deviceId;
                option.textContent = cam.label || `Camera ${index + 1}`;
                cameraSelect.appendChild(option);
            });

            const storedId = localStorage.getItem(CAMERA_STORAGE_KEY);
            if (storedId && cameras.some(cam => cam.deviceId === storedId)) {
                cameraSelect.value = storedId;
            }

            cameraSelectWrap.classList.remove('hidden');
        }

        cameraSelect.addEventListener('change', () => {
            localStorage.setItem(CAMERA_STORAGE_KEY, cameraSelect.value);
            startCamera(cameraSelect.value);
        });

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

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
            const imageData = canvas.toDataURL('image/jpeg', 0.9);

            await $wire.capture(imageData);
        });

        (async () => {
            await startCamera(localStorage.getItem(CAMERA_STORAGE_KEY));
            await populateCameraList();
        })();
    </script>
    @endscript
</div>
