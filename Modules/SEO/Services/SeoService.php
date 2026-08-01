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
            $seo = SeoMeta::where('route_name', 'global')->first();
        }

        // 3. Fallback defaults
        $title = $seo->title ?? config('app.name');
        $description = $seo->description ?? 'Default Description';
        $keywords = $seo->keywords ?? '';
        $ogImage = $seo->og_image ?? asset('images/default-og.jpg');
        $twitterCard = $seo->twitter_card ?? 'summary_large_image';
        $noIndex = $seo->noindex ?? false;
        $noFollow = $seo->nofollow ?? false;
        $url = url()->current();

        // 4. If we have a model, check for direct SEO fields (like blog posts)
        if ($model) {
            // Check for meta_title, meta_description, meta_keywords (Blog Post pattern)
            if (isset($model->meta_title) && !empty($model->meta_title)) {
                $title = strip_tags(html_entity_decode($model->meta_title));
            } elseif (isset($model->title) && !empty($model->title)) {
                $title = strip_tags(html_entity_decode($model->title)) . ' | ' . config('app.name');
            } elseif (isset($model->name) && !empty($model->name)) {
                $title = strip_tags(html_entity_decode($model->name)) . ' | ' . config('app.name');
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

        // Basic
        $html[] = '<title>' . e($title) . '</title>';
        $html[] = '<meta name="description" content="' . e($description) . '">';
        if ($keywords) {
            $html[] = '<meta name="keywords" content="' . e($keywords) . '">';
        }

        // Robots
        $robots = [];
        if ($noIndex) $robots[] = 'noindex';
        if ($noFollow) $robots[] = 'nofollow';
        if (!empty($robots)) {
            $html[] = '<meta name="robots" content="' . implode(', ', $robots) . '">';
        }

        // Canonical
        $html[] = '<link rel="canonical" href="' . $url . '">';

        // Open Graph
        $html[] = '<meta property="og:title" content="' . e($title) . '">';
        $html[] = '<meta property="og:description" content="' . e($description) . '">';
        $html[] = '<meta property="og:image" content="' . $ogImage . '">';
        $html[] = '<meta property="og:url" content="' . $url . '">';
        $html[] = '<meta property="og:type" content="website">';

        // Twitter
        $html[] = '<meta name="twitter:card" content="' . $twitterCard . '">';
        $html[] = '<meta name="twitter:title" content="' . e($title) . '">';
        $html[] = '<meta name="twitter:description" content="' . e($description) . '">';
        $html[] = '<meta name="twitter:image" content="' . $ogImage . '">';

        return implode("\n", $html);
    }
}
