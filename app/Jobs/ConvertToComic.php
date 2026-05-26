<?php

namespace App\Jobs;

use App\Models\PhotoJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;

class ConvertToComic implements ShouldQueue
{
    use Queueable;

    public int $timeout = 0;

    public function __construct(public PhotoJob $photoJob) {}

    public function handle(): void
    {
        $absolutePath = Storage::disk('public')->path($this->photoJob->image_path);
        $script = base_path('convert_to_comic.sh');

        $result = Process::timeout(0)->run('bash ' . escapeshellarg($script) . ' ' . escapeshellarg($absolutePath));

        $this->photoJob->update([
            'status' => $result->successful() ? 'done' : 'failed',
        ]);
    }
}
