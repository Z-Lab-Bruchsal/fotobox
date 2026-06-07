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

        $result = Process::timeout(0)->run(
            'gmic ' . escapeshellarg($absolutePath) .
            ' simplelocalcontrast_p 16,2,0,1,1,1,1,1,1,1,1,0' .
            ' -o ' . escapeshellarg($absolutePath) .
            ' && gmic ' . escapeshellarg($absolutePath) .
            ' cl_comic 4,1,0,0,1,15,15,1,10,20,6,2,0,0,0,0,0,0,50,50' .
            ' -o ' . escapeshellarg($absolutePath)
        );

        $this->photoJob->update([
            'status' => $result->successful() ? 'done' : 'failed',
        ]);
    }
}
