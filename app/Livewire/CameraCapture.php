<?php

namespace App\Livewire;

use App\Jobs\ConvertToComic;
use App\Models\PhotoJob;
use App\Models\Photoprofile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class CameraCapture extends Component
{
    public string $status = '';
    public ?int $photoprofileId = null;

    public function mount(): void
    {
        $this->photoprofileId = Photoprofile::where('active', 1)->value('id');
    }

    public function delete(int $jobId): void
    {
        $job = PhotoJob::find($jobId);
        if (!$job) return;

        Storage::disk('public')->delete($job->image_path);
        $job->delete();

        $this->status = "Job #{$jobId} deleted";
    }

    public function capture(string $imageData): void
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $this->status = 'Error: Invalid image data';
            return;
        }

        $extension = strtolower($type[1]);
        $filename = 'photos/' . Str::uuid() . '.' . $extension;
        $raw = base64_decode(substr($imageData, strpos($imageData, ',') + 1));

        Storage::disk('public')->put($filename, $raw);

        $job = PhotoJob::create([
            'image_path' => $filename,
            'status' => 'processing',
            'photoprofile_id' => $this->photoprofileId,
        ]);

        ConvertToComic::dispatch($job);

        $this->status = "Processing photo…";
    }

    public function render()
    {
        $recentPhotos = PhotoJob::latest()->take(10)->get()->map(fn($job) => [
            'id'     => $job->id,
            'url'    => Storage::disk('public')->url($job->image_path) . '?v=' . $job->updated_at->timestamp,
            'status' => $job->status,
        ]);

        $hasProcessing = $recentPhotos->contains('status', 'processing');
        $photoprofiles = Photoprofile::where('active', 1)->pluck('name', 'id');

        return view('livewire.camera-capture', compact('recentPhotos', 'hasProcessing', 'photoprofiles'));
    }
}
