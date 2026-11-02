<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToTenant;

class Account extends Model
{
    use BelongsToTenant;
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'code',
        'name',
        'description',
        'balance',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category for this account.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(AccountCategory::class, 'category_id');
    }

    /**
     * Get the transactions for this account.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Calculate current balance from transactions.
     */
    public function calculateBalance(): float
    {
        $credits = $this->transactions()->credit()->completed()->sum('amount');
        $debits = $this->transactions()->debit()->completed()->sum('amount');
        
        return $credits - $debits;
    }

    /**
     * Update balance from transactions.
     */
    public function updateBalance(): void
    {
        $this->balance = $this->calculateBalance();
        $this->save();
    }

    /**
     * Scope for active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
