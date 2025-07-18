<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class LanguageSwitcher extends Component
{
    public $currentLocale;
    public $availableLocales;

    public function mount()
    {
        $this->currentLocale = App::getLocale();
        $this->availableLocales = config('app.supported_locales', []);
    }

    public function switchLanguage($locale)
    {
        // Validate locale
        if (!array_key_exists($locale, $this->availableLocales)) {
            return;
        }

        // Update user preference if logged in
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }

        // Set session for guest users
        session(['locale' => $locale]);

        // Set current locale
        App::setLocale($locale);
        $this->currentLocale = $locale;

        // Refresh the page to apply new locale
        return redirect()->refresh();
    }

    public function render()
    {
        return view('livewire.components.language-switcher');
    }
}
