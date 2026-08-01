<?php

namespace Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ContractTemplate extends Model
{
    protected $fillable = [
        'name',
        'contract_type',
        'description',
        'content',
        'default_terms',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by contract type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('contract_type', $type);
    }
}
