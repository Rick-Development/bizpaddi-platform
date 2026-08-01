<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturePrice extends Model
{
    protected $fillable = [
        'feature_name',
        'feature_code',
        'description',
        'price',
        'currency',
        'billing_type',
        'is_active',
        'parameters',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'parameters' => 'array',
    ];

    /**
     * Scope for active features.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
