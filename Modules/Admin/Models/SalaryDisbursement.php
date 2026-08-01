<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class SalaryDisbursement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'bank_code',
        'bank_name',
        'account_number',
        'account_name',
        'session_id',
        'reference',
        'status',
        'description',
        'payment_date',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
