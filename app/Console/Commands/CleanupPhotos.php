<?php

namespace App\Console\Commands;

use App\Models\PhotoJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupPhotos extends Command
{
    protected $signature = 'photos:cleanup';
    protected $description = 'Delete photos and their jobs older than the configured retention period';

    public function handle(): void
    {
        $hours = config('fotobox.photo_retention_hours');
        $cutoff = now()->subHours($hours);

        $jobs = PhotoJob::where('created_at', '<', $cutoff)->get();

        foreach ($jobs as $job) {
            Storage::disk('public')->delete($job->image_path);
            $job->delete();
        }

        $this->info("Deleted {$jobs->count()} photo(s) older than {$hours} hour(s).");
    }
}
