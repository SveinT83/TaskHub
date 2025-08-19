<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class CurrencyService
{
    /**
     * Get the default currency
     */
    public function getDefaultCurrency(): ?Currency
    {
        return Cache::remember('default_currency', 86400, function () {
            $defaultCode = Setting::where('name', 'default_currency')
                ->where('group', 'financial')
                ->value('value') ?? 'EUR';
            
            return Currency::where('code', $defaultCode)->first();
        });
    }

    /**
     * Get all active currencies
     */
    public function getActiveCurrencies(): Collection
    {
        return Cache::remember('active_currencies', 86400, function () {
            return Currency::where('active', true)->orderBy('code')->get();
        });
    }

    /**
     * Search currencies by name or code
     */
    public function searchCurrencies(string $search): Collection
    {
        return Currency::where(function ($query) use ($search) {
            $query->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
        })
        ->where('active', true)
        ->orderBy('code')
        ->get();
    }

    /**
     * Update exchange rates from API
     */
    public function updateExchangeRates(): bool
    {
        return Currency::updateExchangeRates();
    }

    /**
     * Convert amount between currencies
     */
    public function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        return Currency::convert($amount, $fromCurrency, $toCurrency);
    }

    /**
     * Format currency amount
     */
    public function format(float $amount, ?string $currencyCode = null): string
    {
        return Currency::format($amount, $currencyCode);
    }

    /**
     * Convert multiple amounts at once (bulk operation)
     */
    public function bulkConvert(array $amounts, string $fromCurrency, string $toCurrency): array
    {
        if ($fromCurrency === $toCurrency) {
            return $amounts;
        }

        $from = Currency::where('code', $fromCurrency)->firstOrFail();
        $to = Currency::where('code', $toCurrency)->firstOrFail();
        
        $rate = $to->exchange_rate / $from->exchange_rate;
        
        return array_map(function ($amount) use ($rate) {
            return $amount * $rate;
        }, $amounts);
    }

    /**
     * Format multiple amounts at once (bulk operation)
     */
    public function bulkFormat(array $amounts, string $currencyCode): array
    {
        $currency = Currency::where('code', $currencyCode)->firstOrFail();
        
        return array_map(function ($amount) use ($currency) {
            return $currency->symbol . ' ' . number_format($amount, 2);
        }, $amounts);
    }

    /**
     * Set default currency
     */
    public function setDefaultCurrency(string $currencyCode): bool
    {
        $currency = Currency::where('code', $currencyCode)->where('active', true)->first();
        
        if (!$currency) {
            return false;
        }

        $setting = Setting::where('name', 'default_currency')->where('group', 'financial')->first();
        
        if ($setting) {
            $setting->update(['value' => $currencyCode]);
        } else {
            Setting::create([
                'name' => 'default_currency',
                'value' => $currencyCode,
                'group' => 'financial',
                'description' => 'Default currency for the system',
                'type' => 'string',
            ]);
        }

        // Clear cache
        Cache::forget('default_currency');
        
        return true;
    }

    /**
     * Clear all currency caches
     */
    public function clearCache(): void
    {
        Cache::forget('default_currency');
        Cache::forget('active_currencies');
        Cache::tags(['currency'])->flush();
    }
}
