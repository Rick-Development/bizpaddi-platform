<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        // Support more popular languages
        $allowedLocales = ['en', 'es', 'fr', 'de', 'zh', 'ar', 'pt', 'ru', 'ja', 'hi'];
        
        if (in_array($locale, $allowedLocales)) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }
        
        return back();
    }
}
