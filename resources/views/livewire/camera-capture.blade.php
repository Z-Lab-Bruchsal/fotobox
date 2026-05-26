<div class="flex flex-col items-center w-full">
    <div class="relative w-full max-w-2xl">
        <video id="video" autoplay playsinline
               class="w-full rounded-2xl shadow-2xl bg-gray-900 aspect-video object-cover"></video>

        <canvas id="canvas" class="hidden"></canvas>

        <div id="flash" class="absolute inset-0 rounded-2xl bg-white opacity-0 pointer-events-none transition-opacity duration-150"></div>
    </div>

    <button id="capture-btn"
            wire:loading.attr="disabled"
            class="mt-8 px-10 py-4 bg-white text-gray-950 font-semibold text-lg rounded-full shadow-lg hover:bg-gray-200 active:scale-95 transition-all duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
        <span wire:loading.remove>Take Photo</span>
        <span wire:loading>Saving…</span>
    </button>

    <div class="mt-4 text-sm text-gray-400 h-6">{{ $status }}</div>

    @if ($recentPhotos->isNotEmpty())
        <div class="mt-10 w-full max-w-2xl">
            <h2 class="text-sm font-medium text-gray-400 mb-3 tracking-wide uppercase">Recent Photos</h2>
            <div class="grid grid-cols-5 gap-2" @if($hasProcessing) wire:poll.2000ms @endif>
                @foreach ($recentPhotos as $photo)
                    <div class="relative">
                        @if ($photo['status'] === 'processing')
                            <div class="w-full aspect-square rounded-lg border border-gray-700 bg-gray-900 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-500 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                                </svg>
                            </div>
                        @else
                            <img src="{{ $photo['url'] }}" alt="Photo #{{ $photo['id'] }}"
                                 class="w-full aspect-square object-cover rounded-lg border border-gray-700">
                            <button wire:click="delete({{ $photo['id'] }})" wire:confirm="Delete this photo?"
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

    @script
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const btn = document.getElementById('capture-btn');
        const flash = document.getElementById('flash');

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

        btn.addEventListener('click', async () => {
            if (!video.srcObject) return;

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
