<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'billing_type',
        'price',
        'price_per_user',
        'billing_interval',
        'included_users',
        'max_users',
        'enable_ai',
        'enable_custom_domain',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_user' => 'decimal:2',
        'included_users' => 'integer',
        'max_users' => 'integer',
        'enable_ai' => 'boolean',
        'enable_custom_domain' => 'boolean',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get slug alias for code.
     */
    public function getSlugAttribute(): string
    {
        return (string) $this->code;
    }

    /**
     * Scope for active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Subscriptions under this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class, 'plan_id');
    }

    /**
     * Calculate cost for a given number of seats.
     */
    public function calculateTotal(int $seatCount = 1): float
    {
        if ($this->billing_type === 'per_seat') {
            return (float) ($this->price + ($this->price_per_user * max(0, $seatCount - $this->included_users)));
        }

        return (float) $this->price;
    }
}
