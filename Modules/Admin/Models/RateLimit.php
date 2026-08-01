<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RateLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'route_name',
        'route_path',
        'max_attempts',
        'decay_minutes',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'max_attempts' => 'integer',
        'decay_minutes' => 'integer',
    ];
}
