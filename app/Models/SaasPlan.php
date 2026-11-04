<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaasPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'billing_type',
        'price',
        'price_per_user',
        'billing_interval',
        'included_users',
        'max_users',
        'enable_ai',
        'enable_custom_domain',
        'features',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_per_user' => 'decimal:2',
        'included_users' => 'integer',
        'max_users' => 'integer',
        'enable_ai' => 'boolean',
        'enable_custom_domain' => 'boolean',
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get slug alias for code.
     */
    public function getSlugAttribute(): string
    {
        return (string) $this->code;
    }

    /**
     * Scope for active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Subscriptions under this plan.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class, 'plan_id');
    }

    /**
     * Human-readable feature labels dictionary.
     */
    public const AVAILABLE_FEATURES = [
        'invoicing' => 'Unlimited Invoices & Receipts (₦0 per invoice)',
        'quotes' => 'Unlimited Quotes, Estimates & Bids',
        'projects' => 'Project Management & Milestone Delivery',
        'tasks_kanban' => 'Drag-and-Drop Kanban Task Pipelines',
        'team_attendance' => 'Staff Clock-in, Attendance & Time Logs',
        'payroll' => 'Automated Staff Payroll & Salary Remittance',
        'client_portal' => 'Client Self-Service Portal with Bank Transfers',
        'crm_leads' => 'CRM Sales Leads & Client Pipeline',
        'storefront_website' => 'Public Digital Storefront (slug.bizpaddi.com)',
        'custom_domain' => 'Custom Domain Integration (e.g. yourbrand.com)',
        'contracts_management' => 'Digital Contracts & Electronic Signatures',
        'social_media_hub' => 'Omnichannel Social Media Lead Inbox',
        'recruitment_ats' => 'Recruitment & Job Board Management',
        'ai_center' => 'AI Business Assistant & Copywriting Copilot',
        'ai_workforce' => 'Autonomous AI Employees & Department Copilots ✨',
    ];

    /**
     * Dynamically assemble plan feature bullets from database settings.
     */
    public function getFeatureListAttribute(): array
    {
        $features = $this->features ?? [];
        $list = [];

        // Always show seat count first
        $list[] = 'Includes <strong>' . $this->included_users . ' active team ' . ($this->included_users > 1 ? 'seats' : 'seat') . '</strong>';

        foreach (self::AVAILABLE_FEATURES as $key => $label) {
            if (!empty($features[$key])) {
                $list[] = $label;
            }
        }

        // Add custom domain if enabled
        if ($this->enable_custom_domain && !in_array(self::AVAILABLE_FEATURES['custom_domain'], $list, true)) {
            $list[] = self::AVAILABLE_FEATURES['custom_domain'];
        }

        // Add AI workforce if enabled
        if ($this->enable_ai && !in_array(self::AVAILABLE_FEATURES['ai_workforce'], $list, true)) {
            $list[] = self::AVAILABLE_FEATURES['ai_workforce'];
        }

        // If no module features checked, fall back to core business capabilities
        if (count($list) <= 1) {
            $list[] = 'Unlimited Invoices & Receipts (₦0 per invoice)';
            $list[] = 'Project Management & Kanban Workflows';
            $list[] = 'Digital Storefront (slug.bizpaddi.com)';
        }

        return $list;
    }

    /**
     * Calculate cost for a given number of seats.
     */
    public function calculateTotal(int $seatCount = 1): float
    {
        if ($this->billing_type === 'per_seat') {
            return (float) ($this->price + ($this->price_per_user * max(0, $seatCount - $this->included_users)));
        }

        return (float) $this->price;
    }
}
