<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhotoSetting extends Model
{
    protected $fillable = ['name', 'gmic_command', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function jobs(): HasMany
    {
        return $this->hasMany(PhotoJob::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
