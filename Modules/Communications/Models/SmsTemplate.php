<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
