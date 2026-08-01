<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'exchange_rate' => 'decimal:8',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
