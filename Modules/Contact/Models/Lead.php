<?php

namespace Modules\Contact\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use Modules\Communications\Models\Contact;
use App\Traits\BelongsToTenant;

class Lead extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'company',
        'company_details',
        'status',
        'source',
        'score',
        'value',
        'expected_close_date',
        'assigned_to',
        'ai_employee_id',
        'last_contacted_at',
        'converted_to_contact_id',
        'converted_at',
        'metadata',
        'notes',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_contacted_at' => 'datetime',
        'converted_at' => 'datetime',
        'score' => 'integer',
    ];

    /**
     * Get the user assigned to this lead
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the AI Employee who generated this lead
     */
    public function aiEmployee(): BelongsTo
    {
        return $this->belongsTo(\Modules\AiEmployees\Models\AiEmployee::class, 'ai_employee_id');
    }

    /**
     * Get the contact this lead was converted to
     */
    public function convertedToContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'converted_to_contact_id');
    }

    /**
     * Scope: Get new leads
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope: Get qualified leads
     */
    public function scopeQualified($query)
    {
        return $query->where('status', 'qualified');
    }

    /**
     * Scope: Get leads assigned to a specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope: Get converted leads
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    /**
     * Mark lead as contacted
     */
    public function markAsContacted(): void
    {
        $this->update([
            'status' => 'contacted',
            'last_contacted_at' => now(),
        ]);
    }

    /**
     * Convert lead to contact
     */
    public function convertToContact(): Contact
    {
        // Create contact from lead data
        $contact = Contact::create([
            'email' => $this->email,
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'phone' => $this->phone,
            'company' => $this->company,
            'status' => 'active',
            'subscribed_at' => now(),
            'metadata' => array_merge($this->metadata ?? [], [
                'converted_from_lead_id' => $this->id,
                'lead_source' => $this->source,
                'lead_score' => $this->score,
            ]),
        ]);

        // Update lead status
        $this->update([
            'status' => 'converted',
            'converted_to_contact_id' => $contact->id,
            'converted_at' => now(),
        ]);

        return $contact;
    }

    /**
     * Update lead score
     */
    public function updateScore(int $score): void
    {
        $this->update([
            'score' => max(0, min(100, $score)), // Clamp between 0-100
        ]);
    }

    /**
     * Get first name from full name
     */
    protected function getFirstName(): ?string
    {
        $parts = explode(' ', $this->name, 2);
        return $parts[0] ?? null;
    }

    /**
     * Get last name from full name
     */
    protected function getLastName(): ?string
    {
        $parts = explode(' ', $this->name, 2);
        return $parts[1] ?? null;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'contacted' => 'yellow',
            'nurturing' => 'purple',
            'qualified' => 'green',
            'converted' => 'emerald',
            'lost' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }
}
