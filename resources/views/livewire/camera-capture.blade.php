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
            <div class="grid grid-cols-5 gap-2">
                @foreach ($recentPhotos as $photo)
                    <div class="relative group">
                        <img src="{{ $photo['url'] }}" alt="Photo #{{ $photo['id'] }}"
                             class="w-full aspect-square object-cover rounded-lg border border-gray-700">
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
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
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

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            const imageData = canvas.toDataURL('image/jpeg', 0.9);

            await $wire.capture(imageData);
        });

        startCamera();
    </script>
    @endscript
</div>
