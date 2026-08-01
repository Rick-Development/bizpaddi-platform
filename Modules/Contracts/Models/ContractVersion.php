<?php

namespace Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ContractVersion extends Model
{
    protected $fillable = [
        'contract_id',
        'version_number',
        'content',
        'changes_summary',
        'created_by',
    ];

    /**
     * Get the contract.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
