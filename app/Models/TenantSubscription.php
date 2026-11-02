<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TenantSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'seats_purchased',
        'amount_paid',
        'currency',
        'current_period_start',
        'current_period_end',
        'status',
        'payment_method',
        'billway_customer_code',
        'billway_subscription_code',
        'last_payment_reference',
        'grace_period_ends_at',
    ];

    protected $casts = [
        'seats_purchased' => 'integer',
        'amount_paid' => 'decimal:2',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'grace_period_ends_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'plan_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(TenantPayment::class, 'subscription_id');
    }

    public function isValid(): bool
    {
        if ($this->status === 'active') {
            return $this->current_period_end && $this->current_period_end->isFuture();
        }

        if ($this->status === 'past_due' && $this->grace_period_ends_at && $this->grace_period_ends_at->isFuture()) {
            return true;
        }

        return false;
    }
}
