<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoSession extends Model
{
    protected $fillable = ['original_image_path', 'selected_job_id'];

    public function jobs(): HasMany
    {
        return $this->hasMany(PhotoJob::class)->with('setting')->orderBy('photo_setting_id');
    }

    public function selectedJob(): BelongsTo
    {
        return $this->belongsTo(PhotoJob::class, 'selected_job_id');
    }
}
