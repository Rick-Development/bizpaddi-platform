<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class StaffSalary extends Model
{
    use HasFactory;

    protected $table = 'staff_salaries';

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_cycle',
        'payment_day',
        'effective_date',
        'status',
        'designation',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'effective_date' => 'date',
        'payment_day' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFormattedAmountAttribute(): string
    {
        $symbol = match(strtoupper($this->currency)) {
            'NGN' => '₦',
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            default => $this->currency . ' ',
        };

        return $symbol . number_format((float) $this->amount, 2);
    }

    public function getAnnualAmountAttribute(): float
    {
        return (float) $this->amount * 12;
    }

    public function getFormattedAnnualAmountAttribute(): string
    {
        $symbol = match(strtoupper($this->currency)) {
            'NGN' => '₦',
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            default => $this->currency . ' ',
        };

        return $symbol . number_format($this->annual_amount, 2);
    }
}
