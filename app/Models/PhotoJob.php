<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Photoprofile;

class PhotoJob extends Model
{
    protected $fillable = ['image_path', 'status', 'photoprofile_id'];

    public function photoprofile()
    {
        return $this->belongsTo(Photoprofile::class);
    }
}
