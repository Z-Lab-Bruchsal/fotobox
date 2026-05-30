<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhotoJob extends Model
{
    protected $fillable = ['image_path', 'status', 'photo_session_id', 'photo_setting_id'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(PhotoSession::class, 'photo_session_id');
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(PhotoSetting::class, 'photo_setting_id');
    }
}
