<?php

namespace App\Livewire;

use App\Jobs\ConvertToComic;
use App\Models\PhotoJob;
use App\Models\PhotoSession;
use App\Models\PhotoSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class CameraCapture extends Component
{
    public string $status = '';

    public function capture(string $imageData): void
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $this->status = 'Error: Invalid image data';
            return;
        }

        $extension = strtolower($type[1]);
        $raw = base64_decode(substr($imageData, strpos($imageData, ',') + 1));

        $originalPath = 'photos/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($originalPath, $raw);

        $session = PhotoSession::create(['original_image_path' => $originalPath]);

        $settings = PhotoSetting::active()->get();

        foreach ($settings as $setting) {
            $variantPath = 'photos/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($variantPath, $raw);

            $job = PhotoJob::create([
                'image_path'       => $variantPath,
                'status'           => 'processing',
                'photo_session_id' => $session->id,
                'photo_setting_id' => $setting->id,
            ]);

            ConvertToComic::dispatch($job);
        }

        $this->status = count($settings) . ' Varianten werden erstellt…';
    }

    public function selectVariant(int $sessionId, int $jobId): void
    {
        PhotoSession::where('id', $sessionId)->update(['selected_job_id' => $jobId]);
    }

    public function deleteSession(int $sessionId): void
    {
        $session = PhotoSession::with('jobs')->find($sessionId);
        if (!$session) return;

        foreach ($session->jobs as $job) {
            Storage::disk('public')->delete($job->image_path);
            $job->delete();
        }

        Storage::disk('public')->delete($session->original_image_path);
        $session->delete();

        $this->status = "Session #{$sessionId} gelöscht";
    }

    public function render()
    {
        $sessions = PhotoSession::latest()->take(10)->get();

        foreach ($sessions as $session) {
            $session->setRelation('jobs', $session->jobs()->with('setting')->orderBy('photo_setting_id')->get());
        }

        $currentSession  = $sessions->first();
        $previousSessions = $sessions->skip(1);

        $hasProcessing = $currentSession && $currentSession->jobs->contains('status', 'processing');

        return view('livewire.camera-capture', compact('currentSession', 'previousSessions', 'hasProcessing'));
    }
}
