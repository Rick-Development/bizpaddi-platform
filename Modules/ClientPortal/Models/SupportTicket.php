<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Clients\Models\Client;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'subject',
        'status',
        'priority',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class);
    }
}
