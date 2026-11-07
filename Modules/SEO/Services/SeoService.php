<?php

namespace Modules\SEO\Services;

use Modules\SEO\Models\SeoMeta;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Render the SEO meta tags.
     *
     * @param mixed $model Optional model to fetch specific SEO data for.
     * @return string
     */
    public function render($model = null)
    {
        $seo = null;

        // 1. Try to get SEO from the Model (polymorphic)
        if ($model && method_exists($model, 'seo')) {
            $seo = $model->seo;
        }

        // 2. If no model SEO, try Global
        if (!$seo) {
            try {
                $seo = SeoMeta::where('route_name', 'global')->first();
            } catch (\Throwable $e) {
                // Table might not exist yet
                $seo = null;
            }
        }

        // Global Setting fallbacks
        $globalSettingTitle = class_exists(\Modules\Settings\Models\Setting::class) ? \Modules\Settings\Models\Setting::get('seo_meta_title') : null;
        $globalSettingDesc = class_exists(\Modules\Settings\Models\Setting::class) ? \Modules\Settings\Models\Setting::get('seo_meta_description') : null;
        $globalSettingKeywords = class_exists(\Modules\Settings\Models\Setting::class) ? \Modules\Settings\Models\Setting::get('seo_meta_keywords') : null;
        $globalSettingOgImage = class_exists(\Modules\Settings\Models\Setting::class) ? \Modules\Settings\Models\Setting::get('seo_og_image') : null;
        $twitterHandle = class_exists(\Modules\Settings\Models\Setting::class) ? \Modules\Settings\Models\Setting::get('seo_twitter_handle', '@bizpaddi') : '@bizpaddi';

        // 3. Fallback defaults
        $fallbackTitle = 'Bizpaddi | All-in-One Business Operating System for African Commerce';
        $fallbackDesc = 'Run your entire business on autopilot. Smart invoicing with automated payment reconciliation, Kanban project pipelines, staff payroll, omnichannel CRM, and dedicated digital storefronts.';
        $fallbackKeywords = 'business management platform, invoicing software Nigeria, automated bank settlement, virtual bank accounts, payroll software Africa, digital storefront, CRM Africa, Bizpaddi';

        $title = $globalSettingTitle ?: ($seo->title ?? null);
        if (empty($title) || $title === config('app.name')) {
            $title = $fallbackTitle;
        }

        $description = $globalSettingDesc ?: ($seo->description ?? null);
        if (empty($description) || $description === 'Default Description' || $description === 'Default description') {
            $description = $fallbackDesc;
        }

        $keywords = $globalSettingKeywords ?: ($seo->keywords ?? $fallbackKeywords);

        // Resolve absolute OG Image
        $ogImage = $globalSettingOgImage ?: ($seo->og_image ?? null);
        if (empty($ogImage) || str_contains($ogImage, 'default-og.jpg') || $ogImage === '/images/bizpaddi/logo.png') {
            $ogImage = '/images/bizpaddi/og-preview.png';
        }

        $url = url()->current();
        if (request()->header('x-forwarded-proto') === 'https' || request()->isSecure() || str_contains($url, 'bizpaddi.com')) {
            $url = str_replace('http://', 'https://', $url);
            if (str_starts_with($ogImage, 'http://')) {
                $ogImage = str_replace('http://', 'https://', $ogImage);
            }
        }

        if (!str_starts_with($ogImage, 'http://') && !str_starts_with($ogImage, 'https://')) {
            $base = (str_starts_with($url, 'https://') || request()->header('x-forwarded-proto') === 'https' || str_contains($url, 'bizpaddi.com')) 
                ? 'https://' . request()->getHost() 
                : url('/');
            $ogImage = rtrim($base, '/') . '/' . ltrim($ogImage, '/');
        }

        // 4. If we have a model, check for direct SEO fields (like blog posts)
        if ($model) {
            if (isset($model->meta_title) && !empty($model->meta_title)) {
                $title = strip_tags(html_entity_decode($model->meta_title));
            } elseif (isset($model->title) && !empty($model->title)) {
                $title = strip_tags(html_entity_decode($model->title)) . ' | ' . config('app.name', 'Bizpaddi');
            } elseif (isset($model->name) && !empty($model->name)) {
                $title = strip_tags(html_entity_decode($model->name)) . ' | ' . config('app.name', 'Bizpaddi');
            }

            if (isset($model->meta_description) && !empty($model->meta_description)) {
                $description = strip_tags(html_entity_decode($model->meta_description));
                $description = Str::limit($description, 160);
            } elseif (isset($model->excerpt) && !empty($model->excerpt)) {
                $description = Str::limit(strip_tags(html_entity_decode($model->excerpt)), 160);
            } elseif (isset($model->content) && !empty($model->content)) {
                $description = Str::limit(strip_tags(html_entity_decode($model->content)), 160);
            }

            if (isset($model->meta_keywords) && !empty($model->meta_keywords)) {
                $keywords = strip_tags(html_entity_decode($model->meta_keywords));
            }

            // Check for featured_image (blog posts)
            if (isset($model->featured_image) && !empty($model->featured_image)) {
                $ogImage = asset('storage/' . $model->featured_image);
            }
        }

        $html = [];

        // Primary SEO Meta Tags
        $html[] = '<title>' . e($title) . '</title>';
        $html[] = '<meta name="title" content="' . e($title) . '">';
        $html[] = '<meta name="description" content="' . e($description) . '">';
        if ($keywords) {
            $html[] = '<meta name="keywords" content="' . e($keywords) . '">';
        }
        $html[] = '<meta name="author" content="Bizpaddi Technologies">';
        $html[] = '<meta name="theme-color" content="#08110e">';
        $html[] = '<meta name="msapplication-TileColor" content="#08110e">';

        // Robots
        $robots = [];
        if ($noIndex) $robots[] = 'noindex';
        if ($noFollow) $robots[] = 'nofollow';
        if (!empty($robots)) {
            $html[] = '<meta name="robots" content="' . implode(', ', $robots) . '">';
        } else {
            $html[] = '<meta name="robots" content="index, follow">';
        }

        // Canonical
        $html[] = '<link rel="canonical" href="' . $url . '">';

        // Open Graph / Facebook / WhatsApp / LinkedIn
        $html[] = '<meta property="og:type" content="website">';
        $html[] = '<meta property="og:url" content="' . $url . '">';
        $html[] = '<meta property="og:site_name" content="Bizpaddi">';
        $html[] = '<meta property="og:locale" content="en_US">';
        $html[] = '<meta property="og:title" content="' . e($title) . '">';
        $html[] = '<meta property="og:description" content="' . e($description) . '">';
        $html[] = '<meta property="og:image" content="' . $ogImage . '">';
        $html[] = '<meta property="og:image:secure_url" content="' . $ogImage . '">';
        $html[] = '<meta property="og:image:type" content="image/png">';
        $html[] = '<meta property="og:image:width" content="1200">';
        $html[] = '<meta property="og:image:height" content="630">';
        $html[] = '<meta property="og:image:alt" content="' . e($title) . '">';

        // Twitter / X Cards
        $html[] = '<meta name="twitter:card" content="' . $twitterCard . '">';
        $html[] = '<meta name="twitter:site" content="' . e($twitterHandle) . '">';
        $html[] = '<meta name="twitter:creator" content="' . e($twitterHandle) . '">';
        $html[] = '<meta name="twitter:url" content="' . $url . '">';
        $html[] = '<meta name="twitter:title" content="' . e($title) . '">';
        $html[] = '<meta name="twitter:description" content="' . e($description) . '">';
        $html[] = '<meta name="twitter:image" content="' . $ogImage . '">';

        return implode("\n", $html);
    }
}
