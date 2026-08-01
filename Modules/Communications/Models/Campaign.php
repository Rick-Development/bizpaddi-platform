<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'template_id',
        'contact_list_id',
        'content',
        'status',
        'scheduled_at',
        'sent_at',
        'from_name',
        'from_email',
        'reply_to',
        'total_recipients',
        'total_sent',
        'total_opened',
        'total_clicked',
        'total_bounced',
        'total_unsubscribed',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'total_recipients' => 'integer',
        'total_sent' => 'integer',
        'total_opened' => 'integer',
        'total_clicked' => 'integer',
        'total_bounced' => 'integer',
        'total_unsubscribed' => 'integer',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id');
    }

    public function contactList(): BelongsTo
    {
        return $this->belongsTo(ContactList::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CampaignLog::class);
    }

    public function getOpenRateAttribute(): float
    {
        if ($this->total_sent == 0) return 0;
        return round(($this->total_opened / $this->total_sent) * 100, 2);
    }

    public function getClickRateAttribute(): float
    {
        if ($this->total_sent == 0) return 0;
        return round(($this->total_clicked / $this->total_sent) * 100, 2);
    }

    public function getBounceRateAttribute(): float
    {
        if ($this->total_sent == 0) return 0;
        return round(($this->total_bounced / $this->total_sent) * 100, 2);
    }
}
