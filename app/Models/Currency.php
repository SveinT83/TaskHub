<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Currency extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'rate_updated_at',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'rate_updated_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * Get the default currency.
     *
     * @return self
     */
    public static function getDefaultCurrency()
    {
        $defaultCode = setting('default_currency', 'financial', 'NOK');
        return self::where('code', $defaultCode)->first() ?? self::where('active', true)->first();
    }

    /**
     * Convert amount from one currency to another.
     *
     * @param float $amount Amount to convert
     * @param string $fromCurrency Source currency code
     * @param string|null $toCurrency Target currency code (uses default if null)
     * @return float Converted amount
     */
    public static function convert(float $amount, string $fromCurrency, ?string $toCurrency = null): float
    {
        if (empty($toCurrency)) {
            $toCurrency = self::getDefaultCurrency()->code;
        }

        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        $from = self::where('code', $fromCurrency)->firstOrFail();
        $to = self::where('code', $toCurrency)->firstOrFail();

        // Both currencies are compared against the default currency's exchange rate
        return $amount * ($to->exchange_rate / $from->exchange_rate);
    }

    /**
     * Format a number as a monetary value with the currency symbol.
     *
     * @param float $amount
     * @param string|null $currencyCode
     * @return string
     */
    public static function format(float $amount, ?string $currencyCode = null): string
    {
        if (empty($currencyCode)) {
            $currency = self::getDefaultCurrency();
        } else {
            $currency = self::where('code', $currencyCode)->first() ?? self::getDefaultCurrency();
        }

        return $currency->symbol . ' ' . number_format($amount, 2);
    }

    /**
     * Update exchange rates from the configured API provider.
     *
     * @return bool Success status
     */
    public static function updateExchangeRates(): bool
    {
        $provider = setting('exchange_rate_provider', 'financial', 'exchangerate-api');
        $apiKey = setting('exchange_rate_api_key', 'financial', '');

        if (empty($apiKey)) {
            Log::warning('Currency exchange rate update failed: No API key configured');
            throw new \Exception(__('core.currency.no_api_key_configured'));
        }

        try {
            $defaultCurrency = self::getDefaultCurrency();
            $baseCurrency = $defaultCurrency->code;

            switch ($provider) {
                case 'exchangerate-api':
                    return self::updateFromExchangeRateApi($apiKey, $baseCurrency);

                case 'openexchangerates':
                    return self::updateFromOpenExchangeRates($apiKey, $baseCurrency);

                default:
                    Log::error("Unsupported exchange rate provider: {$provider}");
                    return false;
            }
        } catch (\Exception $e) {
            Log::error('Currency exchange rate update failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update rates from ExchangeRate-API.
     *
     * @param string $apiKey
     * @param string $baseCurrency
     * @return bool
     */
    protected static function updateFromExchangeRateApi(string $apiKey, string $baseCurrency): bool
    {
        $response = Http::get("https://v6.exchangerate-api.com/v6/{$apiKey}/latest/{$baseCurrency}");

        if (!$response->successful()) {
            Log::error('ExchangeRate-API request failed: ' . $response->body());
            return false;
        }

        $data = $response->json();
        $rates = $data['conversion_rates'] ?? [];

        if (empty($rates)) {
            Log::error('ExchangeRate-API returned no rates');
            return false;
        }

        return self::saveExchangeRates($rates);
    }

    /**
     * Update rates from Open Exchange Rates.
     *
     * @param string $apiKey
     * @param string $baseCurrency
     * @return bool
     */
    protected static function updateFromOpenExchangeRates(string $apiKey, string $baseCurrency): bool
    {
        // Open Exchange Rates free plan only supports USD as base
        $response = Http::get("https://openexchangerates.org/api/latest.json", [
            'app_id' => $apiKey
        ]);

        if (!$response->successful()) {
            Log::error('OpenExchangeRates API request failed: ' . $response->body());
            return false;
        }

        $data = $response->json();
        $rates = $data['rates'] ?? [];

        if (empty($rates)) {
            Log::error('OpenExchangeRates API returned no rates');
            return false;
        }

        // If base currency is not USD, we need to convert all rates
        if ($baseCurrency !== 'USD') {
            $baseRate = $rates[$baseCurrency] ?? null;
            if (!$baseRate) {
                Log::error("OpenExchangeRates API doesn't include rate for {$baseCurrency}");
                return false;
            }

            // Convert all rates relative to the base currency
            $convertedRates = [];
            foreach ($rates as $code => $rate) {
                $convertedRates[$code] = $rate / $baseRate;
            }
            $rates = $convertedRates;
        }

        return self::saveExchangeRates($rates);
    }

    /**
     * Save exchange rates to the database.
     *
     * @param array $rates
     * @return bool
     */
    protected static function saveExchangeRates(array $rates): bool
    {
        $now = now();
        $updated = 0;

        foreach ($rates as $code => $rate) {
            $currency = self::where('code', $code)->first();

            // Skip if currency doesn't exist in our database
            if (!$currency) {
                continue;
            }

            $currency->exchange_rate = $rate;
            $currency->rate_updated_at = $now;
            $currency->save();

            $updated++;
        }

        Log::info("Updated exchange rates for {$updated} currencies");
        return $updated > 0;
    }
}
