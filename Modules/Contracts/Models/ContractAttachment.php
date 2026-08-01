<?php

namespace Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class ContractAttachment extends Model
{
    protected $fillable = [
        'contract_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'uploaded_by',
    ];

    /**
     * Get the contract.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the uploader.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get file size in human readable format.
     */
    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
