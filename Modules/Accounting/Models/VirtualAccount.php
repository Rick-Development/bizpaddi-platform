<?php

namespace Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\BelongsToTenant;

class VirtualAccount extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'payable_type',
        'payable_id',
        'bank_name',
        'account_name',
        'account_number',
        'currency_code',
        'reference',
        'session_id',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
