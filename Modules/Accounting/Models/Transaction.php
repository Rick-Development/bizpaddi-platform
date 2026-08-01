<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Transaction extends Model
{
    protected $fillable = [
        'account_id',
        'transaction_number',
        'transaction_date',
        'payable_type',
        'payable_id',
        'reference',
        'amount',
        'currency_code',
        'status',
        'type',
        'source',
        'created_by',
        'provider',
        'provider_reference',
        'description',
        'metadata',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date',
        'metadata' => 'array',
        'paid_at' => 'datetime',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function account()
    {
        return $this->belongsTo(\Modules\Accounting\Models\Account::class);
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }
}
