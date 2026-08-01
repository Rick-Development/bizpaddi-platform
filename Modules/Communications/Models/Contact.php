<?php

namespace Modules\Communications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'phone',
        'company',
        'status',
        'subscribed_at',
        'role',
        'unsubscribed_at',
        'metadata',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function lists(): BelongsToMany
    {
        return $this->belongsToMany(ContactList::class, 'contact_list_contacts')
            ->using(ContactListContact::class)
            ->withPivot('id', 'created_at');
    }

    public function campaignLogs(): HasMany
    {
        return $this->hasMany(CampaignLog::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function unsubscribe(): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }

    public function subscribe(): void
    {
        $this->update([
            'status' => 'active',
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ]);
    }
}
