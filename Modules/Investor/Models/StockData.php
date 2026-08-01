<?php

namespace Modules\Investor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockData extends Model
{
    use HasFactory;

    protected $table = 'stock_data';

    protected $fillable = [
        'date',
        'opening_price',
        'closing_price',
        'high_price',
        'low_price',
        'volume',
        'market_cap',
    ];

    protected $casts = [
        'date' => 'date',
        'opening_price' => 'decimal:2',
        'closing_price' => 'decimal:2',
        'high_price' => 'decimal:2',
        'low_price' => 'decimal:2',
        'volume' => 'integer',
        'market_cap' => 'decimal:2',
    ];

    /**
     * Scope for date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for latest data
     */
    public function scopeLatest($query, $limit = 30)
    {
        return $query->orderBy('date', 'desc')->limit($limit);
    }

    /**
     * Get price change
     */
    public function getPriceChangeAttribute()
    {
        if ($this->opening_price && $this->closing_price) {
            return $this->closing_price - $this->opening_price;
        }
        return null;
    }

    /**
     * Get price change percentage
     */
    public function getPriceChangePercentAttribute()
    {
        if ($this->opening_price && $this->price_change) {
            return ($this->price_change / $this->opening_price) * 100;
        }
        return null;
    }
}
