<?php

namespace App\Jobs;

use App\Models\PhotoJob;
use App\Models\Photoprofile;
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

        $photoprofile = Photoprofile::where('active', 1)->first();
        $photocommands = [];
        foreach (explode(PHP_EOL, $photoprofile->commands) as $command) {
            if (count($photocommands) > 0) $photocommands[] = ' && gmic ' . escapeshellarg($absolutePath) . ' ' . $command . ' -o ' . escapeshellarg($absolutePath);
            else $photocommands[] = 'gmic ' . escapeshellarg($absolutePath) . ' ' . $command . ' -o ' . escapeshellarg($absolutePath);
        }
        $command = implode($photocommands);

        $result = Process::timeout(0)->run($command);

        $this->photoJob->update([
            'status' => $result->successful() ? 'done' : 'failed',
        ]);
    }
}
