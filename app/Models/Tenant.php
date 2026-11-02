<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'custom_domain',
        'email',
        'phone',
        'logo_path',
        'currency',
        'country',
        'status',
        'trial_ends_at',
        'ai_enabled',
        'ai_monthly_token_quota',
        'ai_tokens_used',
        'primary_color',
        'secondary_color',
        'accent_color',
        'settings',
        'billing_model',
        'wallet_balance',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ai_enabled' => 'boolean',
        'ai_monthly_token_quota' => 'integer',
        'ai_tokens_used' => 'integer',
        'wallet_balance' => 'decimal:2',
        'settings' => 'array',
    ];

    /**
     * Users belonging to this business.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Products catalog in digital storefront.
     */
    public function products(): HasMany
    {
        return $this->hasMany(\App\Models\StoreProduct::class);
    }

    /**
     * Check if tenant has custom domain / dedicated storefront access enabled on their plan.
     */
    public function hasCustomDomainAccess(): bool
    {
        $plan = $this->activeSubscription?->plan;
        return (bool) ($plan && $plan->enable_custom_domain);
    }

    /**
     * Get the live storefront URL for this business.
     */
    public function getStorefrontUrl(): string
    {
        if ($this->hasCustomDomainAccess() && !empty($this->custom_domain)) {
            return 'https://' . $this->custom_domain;
        }
        return url('/store/' . $this->slug);
    }

    /**
     * All subscriptions for this business.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class);
    }

    /**
     * Current active subscription.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(TenantSubscription::class)
            ->whereIn('status', ['active', 'trialing'])
            ->latestOfMany();
    }

    /**
     * All subscription payment logs.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(TenantPayment::class);
    }

    /**
     * Operating wallet transactions.
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(TenantWalletTransaction::class)->latest();
    }

    /**
     * Check if tenant is currently on free trial.
     */
    public function isOnTrial(): bool
    {
        return $this->status === 'trialing' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if tenant has valid platform access.
     */
    public function hasActiveAccess(): bool
    {
        if ($this->billing_model === 'payg') {
            return $this->status === 'active';
        }

        if ($this->isOnTrial()) {
            return true;
        }

        $subscription = $this->activeSubscription;
        return $subscription && $subscription->isValid();
    }

    /**
     * Credit business operating wallet.
     */
    public function creditWallet(float $amount, string $action, string $description, ?string $reference = null, ?array $metadata = null)
    {
        $before = (float) $this->wallet_balance;
        $after = $before + $amount;
        $this->update(['wallet_balance' => $after]);

        return $this->walletTransactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => $after,
            'action' => $action,
            'description' => $description,
            'reference' => $reference,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Debit business operating wallet for micro-actions.
     */
    public function debitWallet(float $amount, string $action, string $description, ?string $reference = null, ?array $metadata = null): bool
    {
        $before = (float) $this->wallet_balance;
        if ($before < $amount) {
            return false;
        }

        $after = $before - $amount;
        $this->update(['wallet_balance' => $after]);

        $this->walletTransactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => $after,
            'action' => $action,
            'description' => $description,
            'reference' => $reference,
            'metadata' => $metadata,
        ]);

        return true;
    }

    /**
     * Count how many user seats are currently occupied.
     */
    public function usedSeatsCount(): int
    {
        return $this->users()->count();
    }

    /**
     * Check if tenant can use the AI Center / AI Workforce suite.
     */
    public function canUseAi(): bool
    {
        if (!$this->ai_enabled) {
            return false;
        }

        if ($this->ai_monthly_token_quota > 0 && $this->ai_tokens_used >= $this->ai_monthly_token_quota) {
            return false;
        }

        return true;
    }
}
