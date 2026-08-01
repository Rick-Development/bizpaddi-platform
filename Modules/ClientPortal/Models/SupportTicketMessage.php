<?php

namespace Modules\ClientPortal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SupportTicketMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'support_ticket_id',
        'sender_id',
        'sender_type',
        'message',
        'attachment_path',
        'is_admin',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function sender(): MorphTo
    {
        return $this->morphTo();
    }
}
