<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photoprofile extends Model
{
    protected $casts = [
        'commands' => 'array',
    ];
}
