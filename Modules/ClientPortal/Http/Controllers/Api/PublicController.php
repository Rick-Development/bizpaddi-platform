<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Legal\Models\LegalPage;
use Modules\Settings\Models\Setting;

class PublicController extends Controller
{
    public function getLegalPages()
    {
        $pages = LegalPage::select('id', 'slug', 'title', 'updated_at')->get();

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    public function getLegalPage($slug)
    {
        // Handle common short slugs for better API compatibility
        $slugMap = [
            'terms' => 'terms-conditions',
            'privacy' => 'privacy-policy',
        ];

        $actualSlug = $slugMap[$slug] ?? $slug;

        $page = LegalPage::where('slug', $actualSlug)->first();

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'title' => $page->title,
                'content' => $page->content, // Assuming raw HTML or text
                'updated_at' => $page->updated_at,
            ]
        ]);
    }

    public function getSupportSettings()
    {
        $keys = [
            'support_email',
            'support_phone',
            'support_whatsapp',
            'social_facebook',
            'social_instagram',
            'social_twitter',
            'social_linkedin'
        ];

        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = Setting::get($key, '');
        }

        return response()->json([
            'success' => true,
            'data' => $settings
        ]);
    }
}
