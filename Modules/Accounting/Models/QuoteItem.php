<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    protected $fillable = [
        'quote_id',
        'service_id',
        'title',
        'feature_price_id',
        'description',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->quantity * $item->unit_price;
        });

        static::saved(function ($item) {
            $item->quote->calculateTotals();
        });

        static::deleted(function ($item) {
            $item->quote->calculateTotals();
        });
    }

    /**
     * Get the quote that owns this item.
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the service for this item.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
