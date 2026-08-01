<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'currency_id',
        'name',
        'description',
        'base_price',
        'unit',
        'is_active',
        'slug',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the service.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    /**
     * Get the currency for the service.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Get the invoice items for this service.
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the quote items for this service.
     */
    public function quoteItems(): HasMany
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
