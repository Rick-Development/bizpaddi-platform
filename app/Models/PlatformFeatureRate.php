<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PlatformFeatureRate extends Model
{
    use HasFactory;

    protected $table = 'platform_feature_rates';

    protected $fillable = [
        'action_key',
        'name',
        'category',
        'fee',
        'currency',
        'unit_label',
        'description',
        'is_active',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Default platform feature definitions.
     */
    public const DEFAULTS = [
        'product_create' => [
            'name' => 'Add Product to Catalog',
            'category' => 'Storefront & Products',
            'fee' => 50.00,
            'unit_label' => 'per product',
            'description' => 'Adding a physical or digital product to your digital storefront catalog.',
        ],
        'storefront_publish' => [
            'name' => 'Publish Digital Storefront',
            'category' => 'Storefront & Products',
            'fee' => 100.00,
            'unit_label' => 'per publish',
            'description' => 'Updating and publishing storefront catalog and theme modifications online.',
        ],
        'invoice_generation' => [
            'name' => 'Smart Invoice Generation',
            'category' => 'Accounting & Billing',
            'fee' => 50.00,
            'unit_label' => 'per invoice',
            'description' => 'Issuing and sending a smart VAT invoice to a client with Billway payment links.',
        ],
        'quote_generation' => [
            'name' => 'Client Quotation & Estimate',
            'category' => 'Accounting & Billing',
            'fee' => 30.00,
            'unit_label' => 'per quote',
            'description' => 'Generating and sending a formal quotation/estimate to a client.',
        ],
        'client_create' => [
            'name' => 'Onboard Client / Contact',
            'category' => 'CRM & Clients',
            'fee' => 30.00,
            'unit_label' => 'per client',
            'description' => 'Adding a new client profile with client portal credentials.',
        ],
        'lead_create' => [
            'name' => 'Capture Sales Lead',
            'category' => 'CRM & Clients',
            'fee' => 20.00,
            'unit_label' => 'per lead',
            'description' => 'Registering and tracking a new CRM sales lead.',
        ],
        'project_create' => [
            'name' => 'Initialize Project',
            'category' => 'Project Management',
            'fee' => 50.00,
            'unit_label' => 'per project',
            'description' => 'Creating a new client or internal project with milestone tracking.',
        ],
        'task_create' => [
            'name' => 'Create Workflow Task',
            'category' => 'Project Management',
            'fee' => 10.00,
            'unit_label' => 'per task',
            'description' => 'Assigning and tracking a Kanban task card across departments.',
        ],
        'team_member_add' => [
            'name' => 'Provision Team Member Seat',
            'category' => 'Team & Workforce',
            'fee' => 200.00,
            'unit_label' => 'per member',
            'description' => 'Adding a staff or contractor account to the Team Portal.',
        ],
        'team_operation' => [
            'name' => 'Daily Staff Clock-In / Attendance',
            'category' => 'Team & Workforce',
            'fee' => 20.00,
            'unit_label' => 'per clock-in',
            'description' => 'Logging verified daily team attendance and work logs.',
        ],
        'contract_create' => [
            'name' => 'Draft Client Contract',
            'category' => 'Legal & Contracts',
            'fee' => 100.00,
            'unit_label' => 'per contract',
            'description' => 'Creating an electronic agreement for client signature.',
        ],
        'social_post' => [
            'name' => 'Publish Social Media Post',
            'category' => 'Social Media Hub',
            'fee' => 20.00,
            'unit_label' => 'per post',
            'description' => 'Broadcasting scheduled posts across connected social channels.',
        ],
        'ai_query' => [
            'name' => 'Autonomous AI Workforce Task',
            'category' => 'AI Center',
            'fee' => 20.00,
            'unit_label' => 'per query',
            'description' => 'Executing an automated task via autonomous AI agents.',
        ],
    ];

    /**
     * Clear rate cache on change.
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('platform_feature_rates_map');
        });
        static::deleted(function () {
            Cache::forget('platform_feature_rates_map');
        });
    }

    /**
     * Get all active feature rates mapped by action key.
     */
    public static function getRatesMap(): array
    {
        return Cache::remember('platform_feature_rates_map', 3600, function () {
            try {
                return static::where('is_active', true)
                    ->get()
                    ->keyBy('action_key')
                    ->map(fn ($r) => [
                        'fee' => (float) $r->fee,
                        'name' => $r->name,
                        'category' => $r->category,
                        'unit_label' => $r->unit_label,
                        'currency' => $r->currency,
                        'description' => $r->description,
                    ])
                    ->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });
    }

    /**
     * Get dynamic fee for a specific feature action.
     */
    public static function getFeeForAction(string $action): float
    {
        $rates = static::getRatesMap();

        if (isset($rates[$action])) {
            return (float) $rates[$action]['fee'];
        }

        // Fallback to defaults
        if (isset(static::DEFAULTS[$action])) {
            return (float) static::DEFAULTS[$action]['fee'];
        }

        return 0.00;
    }

    /**
     * Ensure default rates exist in the database.
     */
    public static function seedDefaults(): void
    {
        foreach (static::DEFAULTS as $key => $data) {
            static::updateOrCreate(
                ['action_key' => $key],
                [
                    'name' => $data['name'],
                    'category' => $data['category'],
                    'fee' => $data['fee'],
                    'currency' => 'NGN',
                    'unit_label' => $data['unit_label'],
                    'description' => $data['description'],
                    'is_active' => true,
                ]
            );
        }
        Cache::forget('platform_feature_rates_map');
    }
}
