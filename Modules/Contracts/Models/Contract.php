<?php

namespace Modules\Contracts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Traits\BelongsToTenant;

class Contract extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'contract_number',
        'title',
        'contract_type',
        'party_type',
        'party_id',
        'party_name',
        'party_email',
        'party_address',
        'start_date',
        'end_date',
        'duration_months',
        'duration_weeks',
        'auto_renew',
        'value',
        'currency_id',
        'status',
        'content',
        'terms',
        'notes',
        'signed_at',
        'signed_by',
        'signature_file',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'auto_renew' => 'boolean',
        'value' => 'decimal:2',
        'signed_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($contract) {
            if (empty($contract->contract_number)) {
                $contract->contract_number = static::generateContractNumber($contract->contract_type);
            }
        });

        // Create version on update
        static::updated(function ($contract) {
            if ($contract->wasChanged('content')) {
                $contract->createVersion();
            }
        });
    }

    /**
     * Generate a unique contract number.
     */
    public static function generateContractNumber(string $type): string
    {
        $prefix = match($type) {
            'sla' => 'SLA',
            'nda' => 'NDA',
            'employment' => 'EMP',
            'service_agreement' => 'SVC',
            'partnership' => 'PTN',
            'vendor' => 'VND',
            default => 'CNT',
        };

        $year = date('Y');
        $lastContract = static::where('contract_type', $type)
            ->whereYear('created_at', $year)
            ->latest('id')
            ->first();
        
        $number = $lastContract ? ((int) substr($lastContract->contract_number, -4)) + 1 : 1;
        
        return $prefix . '-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Create a version snapshot.
     */
    public function createVersion()
    {
        return $this->versions()->create([
            'version_number' => $this->versions()->count() + 1,
            'content' => $this->content,
            'created_by' => auth('admin')->id(),
        ]);
    }

    /**
     * Replace template variables with actual contract data
     */
    public function replaceVariables($content = null)
    {
        $text = $content ?? $this->content;
        
        // Build duration string
        $durationParts = [];
        if ($this->duration_months) {
            $durationParts[] = $this->duration_months . ' month' . ($this->duration_months > 1 ? 's' : '');
        }
        if ($this->duration_weeks) {
            $durationParts[] = $this->duration_weeks . ' week' . ($this->duration_weeks > 1 ? 's' : '');
        }
        $durationString = !empty($durationParts) ? implode(' ', $durationParts) : 'N/A';
        
        $variables = [
            '{{ party_name }}' => $this->party_name,
            '{{party_name}}' => $this->party_name,
            '{{ start_date }}' => $this->start_date ? $this->start_date->format('F d, Y') : '',
            '{{start_date}}' => $this->start_date ? $this->start_date->format('F d, Y') : '',
            '{{ end_date }}' => $this->end_date ? $this->end_date->format('F d, Y') : '',
            '{{end_date}}' => $this->end_date ? $this->end_date->format('F d, Y') : '',
            '{{ value }}' => $this->value ? number_format($this->value, 2) : '',
            '{{value}}' => $this->value ? number_format($this->value, 2) : '',
            '{{ currency }}' => $this->currency ? $this->currency->code : '',
            '{{currency}}' => $this->currency ? $this->currency->code : '',
            '{{ duration }}' => $durationString,
            '{{duration}}' => $durationString,
            '{{ contract_number }}' => $this->contract_number,
            '{{contract_number}}' => $this->contract_number,
            '{{ title }}' => $this->title,
            '{{title}}' => $this->title,
            '{{ party_email }}' => $this->party_email ?? '',
            '{{party_email}}' => $this->party_email ?? '',
            '{{ party_address }}' => $this->party_address ?? '',
            '{{party_address}}' => $this->party_address ?? '',
            '{{ company_name }}' => \Modules\Settings\Models\Setting::get('site_name', config('app.name')),
            '{{company_name}}' => \Modules\Settings\Models\Setting::get('site_name', config('app.name')),
        ];
        
        return str_replace(array_keys($variables), array_values($variables), $text);
    }

    /**
     * Replace placeholders in content.
     */
    public function replacePlaceholders(): string
    {
        $content = $this->content;
        
        $placeholders = [
            '{{contract_number}}' => $this->contract_number,
            '{{title}}' => $this->title,
            '{{party_name}}' => $this->party_name,
            '{{party_email}}' => $this->party_email ?? '',
            '{{party_address}}' => $this->party_address ?? '',
            '{{start_date}}' => $this->start_date?->format('F d, Y') ?? '',
            '{{end_date}}' => $this->end_date?->format('F d, Y') ?? 'N/A',
            '{{duration}}' => $this->duration_months ? $this->duration_months . ' months' : 'N/A',
            '{{value}}' => $this->value ? ($this->currency->symbol ?? '$') . number_format($this->value, 2) : 'N/A',
            '{{company_name}}' => \Modules\Settings\Models\Setting::get('site_name', config('app.name')),
            '{{company_address}}' => \Modules\Settings\Models\Setting::get('contact_address', ''),
            '{{current_date}}' => now()->format('F d, Y'),
            '{{signed_by}}' => $this->signed_by ?? '',
            '{{signed_date}}' => $this->signed_at?->format('F d, Y') ?? '',
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $content);
    }

    /**
     * Check if contract is expired.
     */
    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date->isPast() && $this->status === 'active';
    }

    /**
     * Check if contract is expiring soon (within 30 days).
     */
    public function isExpiringSoon(): bool
    {
        return $this->end_date && 
               $this->end_date->isFuture() && 
               $this->end_date->diffInDays(now()) <= 30 &&
               $this->status === 'active';
    }

    /**
     * Get the creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the approver.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the currency.
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(\Modules\Accounting\Models\Currency::class);
    }

    /**
     * Get attachments.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(ContractAttachment::class);
    }

    /**
     * Get versions.
     */
    public function versions(): HasMany
    {
        return $this->hasMany(ContractVersion::class);
    }

    /**
     * Scope for active contracts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for expiring soon.
     */
    public function scopeExpiringSoon($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('end_date')
                    ->whereDate('end_date', '>', now())
                    ->whereDate('end_date', '<=', now()->addDays(30));
    }

    /**
     * Scope for expired contracts.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
                    ->whereNotNull('end_date')
                    ->whereDate('end_date', '<', now());
    }
}
