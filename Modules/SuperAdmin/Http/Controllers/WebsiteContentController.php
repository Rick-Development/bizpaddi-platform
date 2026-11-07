<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;
use Illuminate\Support\Facades\Cache;

class WebsiteContentController extends Controller
{
    /**
     * Default values for website copy
     */
    public static function defaults(): array
    {
        return [
            // Hero: Focus on Business Outcomes & Operational Freedom
            'home_hero_headline_1' => 'Run Your Business on Autopilot.',
            'home_hero_headline_gradient' => 'Zero Chaos. Faster Growth.',
            'home_hero_headline_2' => 'Built for African Commerce.',
            'home_hero_subheadline' => 'Stop losing revenue to untracked payments and operational bottlenecks. Bizpaddi digitizes your entire business—automating client payments, project deliverables, staff accountability, and your branded digital storefront in one unified platform.',
            'home_hero_primary_cta_text' => 'Get Started Now',
            'home_hero_secondary_cta_text' => 'See How It Works',
            'home_trust_badge_1' => 'Zero setup fees',
            'home_trust_badge_2' => 'Live in under 60 seconds',
            'home_trust_badge_3' => 'Eliminates payment leakages',
            'home_trust_badge_4' => '256-Bit Bank-Grade Security',

            // Metrics: Focus on Results
            'home_metric_1_val' => '₦500M+',
            'home_metric_1_label' => 'Revenue Tracked & Collected Seamlessly',
            'home_metric_2_val' => '10x Faster',
            'home_metric_2_label' => 'Operations & Client Delivery Velocity',
            'home_metric_3_val' => '99.98%',
            'home_metric_3_label' => 'Uptime & Continuous Business Availability',
            'home_metric_4_val' => '100% Digital',
            'home_metric_4_label' => 'No More Paper Slips or Lost Receipts',

            // Advantage / Comparison: Business Outcome
            'home_advantage_badge' => 'The Business Outcome',
            'home_advantage_title' => 'From Operational Chaos to Predictable Daily Profits',
            'home_advantage_subtitle' => 'Most African businesses leak time and revenue across fragmented chat apps and spreadsheets. Bizpaddi puts your cash flow, deliverables, and team on total autopilot.',

            // Features: Framed as Outcomes (Not just tool names)
            'home_features_badge' => 'High-Velocity Operations',
            'home_features_title' => 'Everything Needed to Make Your Business Smoother & Faster',
            'home_features_subtitle' => 'Designed specifically for African service firms, agencies, distributors, and modern enterprises seeking frictionless scale.',
            'home_feature_1_title' => 'Guaranteed Cash Flow & Zero Payment Leakages',
            'home_feature_1_desc' => 'Get paid faster with automated reconciliation. Every invoice generates dedicated payment rails, instantly confirming payments and receipts without you checking bank apps.',
            'home_feature_2_title' => 'On-Time Deliverables & Zero Missed Deadlines',
            'home_feature_2_desc' => 'Eliminate confusion across your team. Visual sprint boards and real-time work session tracking ensure projects ship on schedule and clients stay delighted.',
            'home_feature_3_title' => 'Complete Staff Accountability & Payroll Peace of Mind',
            'home_feature_3_desc' => 'Keep remote and on-site staff aligned. Track daily attendance, measure output scorecards, and disburse accurate salaries and stipends with zero dispute.',
            'home_feature_4_title' => 'Instant Market Credibility with Your Branded Digital Hub',
            'home_feature_4_desc' => 'Command higher rates and win prestigious clients. Every account includes a sleek public portal and storefront with custom domain integration and automated quote generation.',
            'home_feature_5_title' => 'Scale Operations 5x Without Extra Overhead',
            'home_feature_5_desc' => 'Deploy intelligent AI assistants to handle customer inquiries, draft proposals, and score job candidates—giving you the leverage of a 50-person enterprise.',

            // Pricing & Hybrid African Model
            'home_pricing_badge' => 'Flexible Pricing for African Business',
            'home_pricing_title' => 'Fair, Transparent Pricing Built for Africa',
            'home_pricing_subtitle' => 'Choose how you pay: top up an operating wallet and pay small micro-fees per transaction, or pick an unlimited flat monthly plan for scaling teams.',
            'pricing_payg_badge' => '⚡ The African Model • Zero Monthly Lock-in',
            'pricing_payg_title' => 'Pay-As-You-Transact (Operating Wallet)',
            'pricing_payg_subtitle' => 'No monthly commitments. Top up your operating wallet from ₦500 and only pay tiny micro-fees when you actually issue an invoice or perform an operation. Zero usage = ₦0.',
            'pricing_payg_invoice_fee' => '50',
            'pricing_payg_quote_fee' => '30',
            'pricing_payg_team_fee' => '20',
            'pricing_payg_inflow_fee' => '1% (Capped at ₦1,000)',

            // Bottom CTA
            'home_bottom_cta_headline' => 'Ready to Run a Smoother, More Profitable Business?',
            'home_bottom_cta_subheadline' => 'Join ambitious business leaders across Africa who traded administrative headaches for automated growth, effortless collections, and total operational peace of mind.',
            'home_bottom_cta_btn' => 'Launch Your Workspace &rarr;',
            'home_bottom_cta_guarantee' => 'Instant Digital Onboarding • Full Access to All Modules • Dedicated African Concierge Support',

            // Footer
            'home_footer_bio' => 'The all-in-one business management and invoicing platform. Instant payment confirmation, automated invoice reconciliation, project tracking, staff payroll, and digital storefronts.',
            'home_footer_copyright' => 'Bizpaddi Technologies Ltd. All rights reserved.',
            'home_support_whatsapp' => '2349030117230',

            // Global SEO & Social Sharing (WhatsApp, Facebook, Twitter, LinkedIn)
            'seo_meta_title' => 'Bizpaddi | All-in-One Business Operating System for African Commerce',
            'seo_meta_description' => 'Run your entire business on autopilot. Smart invoicing with automated payment reconciliation, Kanban project pipelines, staff payroll, omnichannel CRM, and dedicated digital storefronts.',
            'seo_meta_keywords' => 'business management platform, invoicing software Nigeria, automated bank settlement, virtual bank accounts, payroll software Africa, digital storefront, CRM Africa, Bizpaddi',
            'seo_og_image' => '/images/bizpaddi/og-preview.png',
            'seo_twitter_handle' => '@bizpaddi',
        ];
    }

    public function index()
    {
        $defaults = self::defaults();
        $content = [];

        foreach ($defaults as $key => $default) {
            $content[$key] = Setting::get($key, $default);
        }

        return view('superadmin::website-content.index', compact('content'));
    }

    public function update(Request $request)
    {
        $defaults = self::defaults();

        foreach ($defaults as $key => $default) {
            if ($request->has($key)) {
                $val = trim((string) $request->input($key));
                Setting::set($key, $val, 'string', 'Public landing page copy');
                Cache::forget("settings.{$key}");
            }
        }

        // Keep Modules\SEO\Models\SeoMeta in sync for global route
        try {
            \Modules\SEO\Models\SeoMeta::updateOrCreate(
                ['route_name' => 'global'],
                [
                    'title' => $request->input('seo_meta_title', 'Bizpaddi | All-in-One Business Operating System for African Commerce'),
                    'description' => $request->input('seo_meta_description', 'Run your entire business on autopilot. Smart invoicing with automated payment reconciliation, Kanban project pipelines, staff payroll, omnichannel CRM, and dedicated digital storefronts.'),
                    'keywords' => $request->input('seo_meta_keywords'),
                    'og_image' => $request->input('seo_og_image') ?: '/images/bizpaddi/og-preview.png',
                ]
            );
        } catch (\Throwable $e) {
            // Ignore if seo_meta table unavailable
        }

        // Also clear view cache so all blades re-render fresh
        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        } catch (\Throwable $e) {
            // Ignore
        }

        return back()->with('success', '🎉 Website copies updated successfully! Changes are now live on the homepage.');
    }
}
