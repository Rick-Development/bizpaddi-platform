@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 pb-16">
    <!-- Header with Preview Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#10231C]">Website Copy & CMS</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#0E5E4A]/10 text-[#0E5E4A] border border-[#0E5E4A]/20">
                    Live Dynamic Content
                </span>
            </div>
            <p class="text-sm text-[#10231C]/70 mt-1">
                Customize every headline, value proposition, feature card, metric, and CTA on the public website without touching code.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}" target="_blank" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs font-bold bg-white text-[#10231C] border border-[#10231C]/15 hover:bg-gray-50 shadow-sm transition">
                <span>Preview Live Website</span>
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <button type="submit" form="website-content-form" 
                    class="px-5 py-2.5 rounded-xl text-xs font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-md transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center space-x-3">
        <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <form id="website-content-form" action="{{ request()->routeIs('platform-admin.*') ? route('platform-admin.website-content.update') : route('super-admin.website-content.update') }}" method="POST" class="space-y-8">
        @csrf

        <!-- 1. Hero Section -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#10231C]/10 space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                <div class="flex items-center space-x-3">
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 font-black text-sm flex items-center justify-center border border-emerald-200">
                        1
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-[#10231C]">Homepage Hero Section</h2>
                        <p class="text-xs text-gray-500">Above-the-fold headline, gradient accent, and primary call-to-action buttons.</p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-amber-50 text-amber-800 border border-amber-200">
                    Highest Visibility
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Headline Line 1</label>
                    <input type="text" name="home_hero_headline_1" value="{{ old('home_hero_headline_1', $content['home_hero_headline_1']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
                    <p class="text-[11px] text-gray-400 mt-1">e.g. "Run Your Entire Business."</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Gradient Highlight Text</label>
                    <input type="text" name="home_hero_headline_gradient" value="{{ old('home_hero_headline_gradient', $content['home_hero_headline_gradient']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-emerald-400 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-semibold bg-emerald-50/30">
                    <p class="text-[11px] text-emerald-600 mt-1">Highlighted in gradient gold & emerald</p>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Headline Line 2</label>
                    <input type="text" name="home_hero_headline_2" value="{{ old('home_hero_headline_2', $content['home_hero_headline_2']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
                    <p class="text-[11px] text-gray-400 mt-1">e.g. "with One Software."</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Hero Subheadline / Value Pitch</label>
                <textarea name="home_hero_subheadline" rows="3" required
                          class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm leading-relaxed">{{ old('home_hero_subheadline', $content['home_hero_subheadline']) }}</textarea>
                <p class="text-[11px] text-gray-400 mt-1">Concisely explain what Bizpaddi does in 1-2 clear, converting sentences.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Primary CTA Button Text</label>
                    <input type="text" name="home_hero_primary_cta_text" value="{{ old('home_hero_primary_cta_text', $content['home_hero_primary_cta_text']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-bold">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Secondary CTA Button Text</label>
                    <input type="text" name="home_hero_secondary_cta_text" value="{{ old('home_hero_secondary_cta_text', $content['home_hero_secondary_cta_text']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-2">Trust Micro-Badges (Under CTA)</label>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <input type="text" name="home_trust_badge_1" value="{{ old('home_trust_badge_1', $content['home_trust_badge_1']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300">
                    <input type="text" name="home_trust_badge_2" value="{{ old('home_trust_badge_2', $content['home_trust_badge_2']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300">
                    <input type="text" name="home_trust_badge_3" value="{{ old('home_trust_badge_3', $content['home_trust_badge_3']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300">
                    <input type="text" name="home_trust_badge_4" value="{{ old('home_trust_badge_4', $content['home_trust_badge_4']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300">
                </div>
            </div>
        </div>

        <!-- 2. Trust Metrics Bar -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#10231C]/10 space-y-6">
            <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 font-black text-sm flex items-center justify-center border border-amber-200">
                    2
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#10231C]">Platform Metrics & Social Proof Bar</h2>
                    <p class="text-xs text-gray-500">The 4 key statistics displayed below the hero section.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                    <span class="text-xs font-bold uppercase text-gray-500 block">Metric 1</span>
                    <input type="text" name="home_metric_1_val" value="{{ old('home_metric_1_val', $content['home_metric_1_val']) }}" class="w-full px-3 py-1.5 text-sm font-black text-amber-600 rounded-lg border border-gray-300">
                    <input type="text" name="home_metric_1_label" value="{{ old('home_metric_1_label', $content['home_metric_1_label']) }}" class="w-full px-3 py-1.5 text-xs text-gray-700 rounded-lg border border-gray-300">
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                    <span class="text-xs font-bold uppercase text-gray-500 block">Metric 2</span>
                    <input type="text" name="home_metric_2_val" value="{{ old('home_metric_2_val', $content['home_metric_2_val']) }}" class="w-full px-3 py-1.5 text-sm font-black text-gray-900 rounded-lg border border-gray-300">
                    <input type="text" name="home_metric_2_label" value="{{ old('home_metric_2_label', $content['home_metric_2_label']) }}" class="w-full px-3 py-1.5 text-xs text-gray-700 rounded-lg border border-gray-300">
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                    <span class="text-xs font-bold uppercase text-gray-500 block">Metric 3</span>
                    <input type="text" name="home_metric_3_val" value="{{ old('home_metric_3_val', $content['home_metric_3_val']) }}" class="w-full px-3 py-1.5 text-sm font-black text-emerald-600 rounded-lg border border-gray-300">
                    <input type="text" name="home_metric_3_label" value="{{ old('home_metric_3_label', $content['home_metric_3_label']) }}" class="w-full px-3 py-1.5 text-xs text-gray-700 rounded-lg border border-gray-300">
                </div>
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-2">
                    <span class="text-xs font-bold uppercase text-gray-500 block">Metric 4</span>
                    <input type="text" name="home_metric_4_val" value="{{ old('home_metric_4_val', $content['home_metric_4_val']) }}" class="w-full px-3 py-1.5 text-sm font-black text-gray-900 rounded-lg border border-gray-300">
                    <input type="text" name="home_metric_4_label" value="{{ old('home_metric_4_label', $content['home_metric_4_label']) }}" class="w-full px-3 py-1.5 text-xs text-gray-700 rounded-lg border border-gray-300">
                </div>
            </div>
        </div>

        <!-- 3. Value Proposition / Advantage -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#10231C]/10 space-y-6">
            <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 font-black text-sm flex items-center justify-center border border-purple-200">
                    3
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#10231C]">Before vs. After Comparison Header</h2>
                    <p class="text-xs text-gray-500">The section contrasting fragmented chaos with the unified Bizpaddi platform.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Section Badge</label>
                    <input type="text" name="home_advantage_badge" value="{{ old('home_advantage_badge', $content['home_advantage_badge']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-bold text-amber-600">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Section Title</label>
                    <input type="text" name="home_advantage_title" value="{{ old('home_advantage_title', $content['home_advantage_title']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-bold">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Section Subtitle</label>
                <input type="text" name="home_advantage_subtitle" value="{{ old('home_advantage_subtitle', $content['home_advantage_subtitle']) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>
        </div>

        <!-- 4. Features Showcase Cards -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#10231C]/10 space-y-6">
            <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 font-black text-sm flex items-center justify-center border border-blue-200">
                    4
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#10231C]">Feature Showcase Cards (Bento Grid)</h2>
                    <p class="text-xs text-gray-500">Edit titles and descriptions for all 5 core platform capability cards.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Features Badge</label>
                    <input type="text" name="home_features_badge" value="{{ old('home_features_badge', $content['home_features_badge']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-bold text-emerald-600">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Features Main Title</label>
                    <input type="text" name="home_features_title" value="{{ old('home_features_title', $content['home_features_title']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Features Subtitle</label>
                <input type="text" name="home_features_subtitle" value="{{ old('home_features_subtitle', $content['home_features_subtitle']) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm">
            </div>

            <!-- 5 Feature Cards -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <!-- Card 1 -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                    <span class="text-xs font-bold uppercase text-emerald-700 flex items-center space-x-1.5">
                        <span>💳</span>
                        <span>Card 1: Smart Invoicing & Collections</span>
                    </span>
                    <input type="text" name="home_feature_1_title" value="{{ old('home_feature_1_title', $content['home_feature_1_title']) }}" required
                           class="w-full px-3 py-2 text-sm font-bold rounded-lg border border-gray-300">
                    <textarea name="home_feature_1_desc" rows="2" required
                              class="w-full px-3 py-2 text-xs rounded-lg border border-gray-300 leading-relaxed">{{ old('home_feature_1_desc', $content['home_feature_1_desc']) }}</textarea>
                </div>

                <!-- Card 2 -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                    <span class="text-xs font-bold uppercase text-amber-700 flex items-center space-x-1.5">
                        <span>📊</span>
                        <span>Card 2: Project Tracking & Kanban</span>
                    </span>
                    <input type="text" name="home_feature_2_title" value="{{ old('home_feature_2_title', $content['home_feature_2_title']) }}" required
                           class="w-full px-3 py-2 text-sm font-bold rounded-lg border border-gray-300">
                    <textarea name="home_feature_2_desc" rows="2" required
                              class="w-full px-3 py-2 text-xs rounded-lg border border-gray-300 leading-relaxed">{{ old('home_feature_2_desc', $content['home_feature_2_desc']) }}</textarea>
                </div>

                <!-- Card 3 -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                    <span class="text-xs font-bold uppercase text-emerald-700 flex items-center space-x-1.5">
                        <span>👥</span>
                        <span>Card 3: Staff Attendance, Payroll & Utilities</span>
                    </span>
                    <input type="text" name="home_feature_3_title" value="{{ old('home_feature_3_title', $content['home_feature_3_title']) }}" required
                           class="w-full px-3 py-2 text-sm font-bold rounded-lg border border-gray-300">
                    <textarea name="home_feature_3_desc" rows="2" required
                              class="w-full px-3 py-2 text-xs rounded-lg border border-gray-300 leading-relaxed">{{ old('home_feature_3_desc', $content['home_feature_3_desc']) }}</textarea>
                </div>

                <!-- Card 4 -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                    <span class="text-xs font-bold uppercase text-blue-700 flex items-center space-x-1.5">
                        <span>🌐</span>
                        <span>Card 4: Storefront & Subdomains</span>
                    </span>
                    <input type="text" name="home_feature_4_title" value="{{ old('home_feature_4_title', $content['home_feature_4_title']) }}" required
                           class="w-full px-3 py-2 text-sm font-bold rounded-lg border border-gray-300">
                    <textarea name="home_feature_4_desc" rows="2" required
                              class="w-full px-3 py-2 text-xs rounded-lg border border-gray-300 leading-relaxed">{{ old('home_feature_4_desc', $content['home_feature_4_desc']) }}</textarea>
                </div>

                <!-- Card 5 -->
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 space-y-3">
                    <span class="text-xs font-bold uppercase text-amber-700 flex items-center space-x-1.5">
                        <span>🤖</span>
                        <span>Card 5: Autonomous AI Workforce</span>
                    </span>
                    <input type="text" name="home_feature_5_title" value="{{ old('home_feature_5_title', $content['home_feature_5_title']) }}" required
                           class="w-full px-3 py-2 text-sm font-bold rounded-lg border border-gray-300">
                    <textarea name="home_feature_5_desc" rows="2" required
                              class="w-full px-3 py-2 text-xs rounded-lg border border-gray-300 leading-relaxed">{{ old('home_feature_5_desc', $content['home_feature_5_desc']) }}</textarea>
                </div>
            </div>
        </div>

        <!-- 5. Pricing Header & Bottom CTA -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#10231C]/10 space-y-6">
            <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 font-black text-sm flex items-center justify-center border border-emerald-200">
                    5
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#10231C]">Pricing Header & Bottom Conversion Banner</h2>
                    <p class="text-xs text-gray-500">The final high-converting call-to-action banner at the bottom of the page.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Pricing Badge</label>
                    <input type="text" name="home_pricing_badge" value="{{ old('home_pricing_badge', $content['home_pricing_badge']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-bold">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Pricing Title</label>
                    <input type="text" name="home_pricing_title" value="{{ old('home_pricing_title', $content['home_pricing_title']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Pricing Subtitle</label>
                <input type="text" name="home_pricing_subtitle" value="{{ old('home_pricing_subtitle', $content['home_pricing_subtitle']) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">
            </div>

            <!-- Pay-As-You-Transact (African Micro-Fees) Configuration -->
            <div class="pt-4 border-t border-gray-100 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-[#0E5E4A] flex items-center space-x-1.5">
                            <span>⚡ Pay-As-You-Transact (Prepaid Wallet Micro-Fees)</span>
                        </h3>
                        <p class="text-xs text-gray-500">Fine-tune the per-action micro-rates charged to businesses when using the operational wallet.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">PAYG Badge</label>
                        <input type="text" name="pricing_payg_badge" value="{{ old('pricing_payg_badge', $content['pricing_payg_badge'] ?? '⚡ The African Model • Zero Monthly Lock-in') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">PAYG Title</label>
                        <input type="text" name="pricing_payg_title" value="{{ old('pricing_payg_title', $content['pricing_payg_title'] ?? 'Pay-As-You-Transact (Operating Wallet)') }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-bold">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">PAYG Subtitle / Explainer</label>
                    <textarea name="pricing_payg_subtitle" rows="2"
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">{{ old('pricing_payg_subtitle', $content['pricing_payg_subtitle'] ?? 'No monthly commitments. Top up your operating wallet from ₦500 and only pay tiny micro-fees when you actually issue an invoice or perform an operation. Zero usage = ₦0.') }}</textarea>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100">
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Fee per Invoice (₦)</label>
                        <input type="number" name="pricing_payg_invoice_fee" value="{{ old('pricing_payg_invoice_fee', $content['pricing_payg_invoice_fee'] ?? '50') }}"
                               class="w-full px-3 py-2 rounded-lg border border-emerald-300 text-sm font-black text-[#0E5E4A]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Fee per Quote (₦)</label>
                        <input type="number" name="pricing_payg_quote_fee" value="{{ old('pricing_payg_quote_fee', $content['pricing_payg_quote_fee'] ?? '30') }}"
                               class="w-full px-3 py-2 rounded-lg border border-emerald-300 text-sm font-black text-[#0E5E4A]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Team Action Fee (₦)</label>
                        <input type="number" name="pricing_payg_team_fee" value="{{ old('pricing_payg_team_fee', $content['pricing_payg_team_fee'] ?? '20') }}"
                               class="w-full px-3 py-2 rounded-lg border border-emerald-300 text-sm font-black text-[#0E5E4A]">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase text-emerald-900 mb-1">Inflow Fee Rate</label>
                        <input type="text" name="pricing_payg_inflow_fee" value="{{ old('pricing_payg_inflow_fee', $content['pricing_payg_inflow_fee'] ?? '1% (Capped at ₦1,000)') }}"
                               class="w-full px-3 py-2 rounded-lg border border-emerald-300 text-sm font-black text-[#0E5E4A]">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Bottom Banner Headline</label>
                    <input type="text" name="home_bottom_cta_headline" value="{{ old('home_bottom_cta_headline', $content['home_bottom_cta_headline']) }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-extrabold text-[#10231C]">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Bottom Banner Subheadline</label>
                    <textarea name="home_bottom_cta_subheadline" rows="2" required
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">{{ old('home_bottom_cta_subheadline', $content['home_bottom_cta_subheadline']) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Bottom CTA Button Label</label>
                        <input type="text" name="home_bottom_cta_btn" value="{{ old('home_bottom_cta_btn', $content['home_bottom_cta_btn']) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Guarantee / Micro-Copy</label>
                        <input type="text" name="home_bottom_cta_guarantee" value="{{ old('home_bottom_cta_guarantee', $content['home_bottom_cta_guarantee']) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. Footer & Support Contact -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-[#10231C]/10 space-y-6">
            <div class="flex items-center space-x-3 border-b border-gray-100 pb-4">
                <span class="w-9 h-9 rounded-xl bg-gray-100 text-gray-800 font-black text-sm flex items-center justify-center border border-gray-200">
                    6
                </span>
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-[#10231C]">Footer & Support Contacts</h2>
                    <p class="text-xs text-gray-500">Global footer bio, copyright, and concierge WhatsApp number.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Footer Bio Description</label>
                    <textarea name="home_footer_bio" rows="3" required
                              class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">{{ old('home_footer_bio', $content['home_footer_bio']) }}</textarea>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Footer Copyright Line</label>
                        <input type="text" name="home_footer_copyright" value="{{ old('home_footer_copyright', $content['home_footer_copyright']) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-700 mb-1.5">Concierge WhatsApp Number</label>
                        <input type="text" name="home_support_whatsapp" value="{{ old('home_support_whatsapp', $content['home_support_whatsapp']) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 text-sm font-mono" placeholder="2349030117230">
                        <p class="text-[11px] text-gray-400 mt-1">Country code format without + (e.g. 2349030117230)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Save Floating Bar -->
        <div class="sticky bottom-6 z-40 bg-[#10231C] text-white p-4 rounded-2xl shadow-2xl border border-white/10 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Any saved change takes effect immediately on the homepage without deployment.</span>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ url('/') }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/15 text-white transition">
                    Preview
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg transition">
                    Publish Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
