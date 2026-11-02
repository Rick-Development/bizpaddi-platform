<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tenants / Businesses Table
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique(); // Subdomain: {slug}.bizpaddi.com
                $table->string('custom_domain')->unique()->nullable(); // e.g. company.com
                $table->string('email');
                $table->string('phone', 50)->nullable();
                $table->string('logo_path')->nullable();
                $table->string('currency', 10)->default('NGN');
                $table->string('country', 10)->default('NG');
                $table->enum('status', ['trialing', 'active', 'past_due', 'suspended', 'cancelled'])->default('trialing');
                $table->timestamp('trial_ends_at')->nullable();
                
                // AI Quota & Capabilities
                $table->boolean('ai_enabled')->default(false);
                $table->unsignedInteger('ai_monthly_token_quota')->default(0);
                $table->unsignedInteger('ai_tokens_used')->default(0);

                // Tenant Branding
                $table->string('primary_color', 20)->default('#0E5E4A');
                $table->string('secondary_color', 20)->default('#10231C');
                $table->string('accent_color', 20)->default('#F2A900');
                $table->json('settings')->nullable(); // General company info, address, tax ID, etc.

                $table->timestamps();
                $table->softDeletes();
            });
        }

        // 2. SaaS Plans Table (Both Tiered & Per-Seat configurable from Super Admin)
        if (!Schema::hasTable('saas_plans')) {
            Schema::create('saas_plans', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->enum('billing_type', ['flat_tiered', 'per_seat', 'hybrid'])->default('flat_tiered');
                $table->decimal('price', 15, 2)->default(0.00); // Base price
                $table->decimal('price_per_user', 15, 2)->default(0.00); // Additional user seat price
                $table->enum('billing_interval', ['monthly', 'yearly'])->default('monthly');
                $table->unsignedInteger('included_users')->default(1);
                $table->unsignedInteger('max_users')->nullable(); // Null means unlimited
                $table->boolean('enable_ai')->default(false);
                $table->boolean('enable_custom_domain')->default(false);
                $table->json('features')->nullable(); // Module toggles & limits
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Tenant Subscriptions Table
        if (!Schema::hasTable('tenant_subscriptions')) {
            Schema::create('tenant_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
                $table->foreignId('plan_id')->constrained('saas_plans')->onDelete('restrict');
                $table->unsignedInteger('seats_purchased')->default(1);
                $table->decimal('amount_paid', 15, 2)->default(0.00);
                $table->string('currency', 10)->default('NGN');
                $table->timestamp('current_period_start')->nullable();
                $table->timestamp('current_period_end')->nullable();
                $table->enum('status', ['trialing', 'active', 'past_due', 'cancelled', 'expired'])->default('trialing');
                $table->string('payment_method', 50)->default('billway');
                $table->string('billway_customer_code')->nullable();
                $table->string('billway_subscription_code')->nullable();
                $table->string('last_payment_reference')->nullable();
                $table->timestamp('grace_period_ends_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Tenant Subscription Invoices / Payments (Billway History)
        if (!Schema::hasTable('tenant_payments')) {
            Schema::create('tenant_payments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
                $table->foreignId('subscription_id')->nullable()->constrained('tenant_subscriptions')->onDelete('set null');
                $table->string('reference')->unique();
                $table->decimal('amount', 15, 2);
                $table->string('currency', 10)->default('NGN');
                $table->string('gateway', 50)->default('billway');
                $table->string('gateway_reference')->nullable();
                $table->enum('status', ['pending', 'successful', 'failed', 'refunded'])->default('pending');
                $table->json('metadata')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenant_payments');
        Schema::dropIfExists('tenant_subscriptions');
        Schema::dropIfExists('saas_plans');
        Schema::dropIfExists('tenants');
    }
};
