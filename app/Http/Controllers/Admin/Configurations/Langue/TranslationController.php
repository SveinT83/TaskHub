<?php

namespace App\Http\Controllers\Admin\Configurations\Langue;

use App\Http\Controllers\Controller;
use App\Helpers\LanguageHelper;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index()
    {
        return view('admin.translations.index');
    }

    public function stats()
    {
        $locales = config('app.supported_locales', []);
        $stats = [];

        foreach ($locales as $locale => $name) {
            $stats[$locale] = [
                'name' => $name,
                'stats' => LanguageHelper::getTranslationStats($locale),
            ];
        }

        return view('admin.translations.stats', compact('stats'));
    }
}
