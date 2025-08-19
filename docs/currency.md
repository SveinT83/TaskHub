# Currency Management System

> **Version:** 1.1  |  **Last updated:** August 18, 2025

TaskHub Core provides a comprehensive currency management system that can be used by various modules needing financial functionality. This document outlines the currency system features and how to integrate with it.

## Overview

The currency management system provides:

- Storage of currency information (code, name, symbol)
- Exchange rate tracking and automatic updates with fallback to last known rates
- Default currency settings (EUR by default)
- Conversion utilities between currencies
- Permission-based access control

## Database Structure

The system uses the following database tables:

### `currencies` Table

| Field | Type | Description |
|-------|------|-------------|
| `id` | bigint | Primary key |
| `code` | string(3) | ISO 4217 currency code (e.g., "EUR", "USD", "NOK") |
| `name` | string | Full name of the currency |
| `symbol` | string(10) | Currency symbol (e.g., "€", "$", "kr") |
| `exchange_rate` | decimal(10,6) | Exchange rate relative to the default currency |
| `rate_updated_at` | timestamp | When the exchange rate was last updated |
| `active` | boolean | Whether the currency is active in the system |
| `created_at` | timestamp | Creation timestamp |
| `updated_at` | timestamp | Update timestamp |

### Migration

The currency system uses a robust migration approach that:

1. Checks if the `currencies` table already exists before attempting to create it
2. Verifies if currency data is present, and adds default currencies if needed (EUR, USD, NOK, etc.)
3. Sets up required settings entries in the existing `settings` table

The migration (`database/migrations/2023_08_18_000001_create_currencies_table.php`) follows this pattern and ensures compatibility with existing TaskHub Core infrastructure.

### Existing Table Dependencies

The currency system integrates seamlessly with existing TaskHub Core tables:

#### `settings` Table Structure
The existing settings table includes:
- `name` (unique key for each setting)
- `group` (for organizing settings, e.g., 'financial') 
- `value` (setting value)
- `description` (optional description)
- `type` (data type, defaults to 'string')

#### `permissions` Table Structure  
The existing permissions table includes:
- `name` (permission name)
- `guard_name` (authentication guard, typically 'web')

#### `roles` Table Structure
The existing roles table with `superadmin` role for administrative access.

This ensures proper operation regardless of whether:
- This is a fresh installation
- The system is being upgraded  
- The table structure exists but is not registered as migrated

### Settings

The following settings are stored in the core `settings` table:

| Name | Group | Description |
|-----|-------|-------------|
| `default_currency` | financial | The default currency code for the system (default: "EUR") |
| `exchange_rate_api_key` | financial | API key for exchange rate services |
| `exchange_rate_provider` | financial | Selected exchange rate provider service |

## Permissions

The currency system includes the following permissions that are automatically added to the `superadmin` role:

| Permission | Description |
|------------|-------------|
| `currency.view` | View currency listings and details |
| `currency.create` | Create new currencies |
| `currency.edit` | Edit existing currencies |
| `currency.delete` | Delete currencies |

## API Usage### Using the Currency Facade

The system provides a `Currency` facade for easy access:

```php
use App\Facades\Currency;

// Get the default currency
$defaultCurrency = Currency::getDefaultCurrency();

// Get all active currencies
$currencies = Currency::getActiveCurrencies();

// Search currencies by name or code
$results = Currency::searchCurrencies('eur');

// Update exchange rates from API
$success = Currency::updateExchangeRates();
```

### Currency Model Methods

```php
use App\Models\Currency;

// Find a currency
$currency = Currency::where('code', 'USD')->first();

// Convert an amount from this currency to the default currency
$amountInDefaultCurrency = $currency->convertToBase(100);

// Convert an amount from the default currency to this currency
$amountInThisCurrency = $currency->convertFromBase(100);

// Get formatted display name
echo $currency->display_name; // "US Dollar (USD) $"
```

## Exchange Rate APIs

The system supports multiple exchange rate providers:

1. **ExchangeRate-API** (https://www.exchangerate-api.com/)
   - Free tier available with limited requests
   - Simple integration

2. **Open Exchange Rates** (https://openexchangerates.org/)
   - More comprehensive API
   - Various subscription tiers

To use either provider, add your API key in the admin settings.

## Configuration

Exchange rates can be configured to update automatically via the task scheduler. The default schedule updates rates daily at midnight:

```php
// In App\Console\Kernel
protected function schedule(Schedule $schedule): void
{
    $schedule->call(function () {
        Currency::updateExchangeRates();
    })->dailyAt('00:00');
}
```

## Admin Interface

The currency management interface is available in the admin panel under **Configurations > Currency** (menu ID: 12, URL: `/admin/configurations/currency`, icon: `bi bi-cash-coin`). From there, administrators can:

- Add/edit/deactivate currencies
- Set the default system currency
- Configure exchange rate API settings
- Manually trigger exchange rate updates

### Controller & Views

The currency management system uses a dedicated controller:
- **Controller:** `App\Http\Controllers\Admin\Configurations\CurrencyController.php`
- **Views:** Standard Blade views with Livewire components where necessary

The views follow the standard TaskHub architecture, using Blade templates as the primary structure with targeted Livewire components for interactive elements rather than full Livewire pages.

### Language Support

All currency interface text is translatable through TaskHub's language system:

- **Language files:** Uses existing `resources/lang/{locale}/core.php`
- **Namespace:** Uses `core::` namespace for consistency with other core features
- **Menu integration:** Menu item title uses existing pattern: `'title' => 'currency'` (translatable via `__('core::currency')` in views)

Common text strings reuse existing core translations where possible:

```php
// Reused from existing core translations
__('core::ui.save')           // Save button
__('core::ui.edit')           // Edit button  
__('core::ui.delete')         // Delete button
__('core::ui.cancel')         // Cancel button
__('core::ui.search')         // Search placeholder
__('core::ui.active')         // Active status
__('core::ui.name')           // Name field
__('core::ui.created_at')     // Created date
__('core::ui.updated_at')     // Updated date

// Currency-specific additions to core.php
__('core::currency.title')              // "Currency Management"  
__('core::currency.add_currency')       // "Add Currency"
__('core::currency.code')               // "Currency Code"
__('core::currency.symbol')             // "Symbol"
__('core::currency.exchange_rate')      // "Exchange Rate"
__('core::currency.rate_updated_at')    // "Rate Updated"
__('core::currency.default_currency')   // "Default Currency"
__('core::currency.api_provider')       // "API Provider"
__('core::currency.api_key')            // "API Key"
__('core::currency.update_rates')       // "Update Exchange Rates"
__('core::currency.last_update')        // "Last Updated"
__('core::currency.update_success')     // "Exchange rates updated successfully"
__('core::currency.update_failed')      // "Failed to update exchange rates"
__('core::currency.set_default')        // "Set as Default"
```

For detailed information about TaskHub's language system, including GUI editor workflow, CLI utilities, and best practices, see [Language Documentation](langue.md).

### Permissions

The currency system uses the following permissions:

| Permission | Description |
|------------|-------------|
| `currency.view` | View currency information and exchange rates |
| `currency.edit` | Add, edit, or deactivate currencies; change system currency settings |

By default, users with the `admin` or `superuser` roles automatically have all currency permissions.

## Using in Modules

Modules that require currency functionality can easily integrate with the core currency system:

```php
use TaskHub\Facades\Currency;
use TaskHub\Models\Currency as CurrencyModel;

class PriceCalculator
{
    public function convertPrice(float $amount, string $fromCurrency, string $toCurrency): float
    {
        $from = CurrencyModel::where('code', $fromCurrency)->firstOrFail();
        $to = CurrencyModel::where('code', $toCurrency)->firstOrFail();
        
        // First convert to base currency
        $amountInBase = $from->convertToBase($amount);
        
        // Then convert from base to target currency
        return $to->convertFromBase($amountInBase);
    }
}
```

## UI Components

The system includes a Livewire component for selecting currencies with autocomplete functionality:

```php
@livewire('currency-selector', ['selected' => 'NOK'])
```

This component allows users to search and select from available currencies with real-time suggestions.

### Locale-based Currency Formatting

The system respects the user's locale settings when formatting currency values:

```php
// Format based on the authenticated user's locale
$formattedAmount = Currency::formatLocalized(100.50, 'EUR');

// Format for a specific locale
$formattedAmount = Currency::formatLocalized(100.50, 'EUR', 'nb-NO');
```

Format examples:
- Norwegian (nb-NO): "100,50 €"
- US English (en-US): "€100.50"
- French (fr-FR): "100,50 €"

The formatting respects:
- Decimal and thousands separators based on locale
- Symbol placement (before/after amount)
- Space between symbol and amount
- Right-to-left text direction for applicable locales

## Error Handling & Caching

### Error Handling Strategy
When exchange rate updates fail, the system:
1. Logs detailed error information to the application log
2. Continues using the last successfully retrieved rates (stored in the `exchange_rate` field)
3. Shows an admin notification about the failure (only to users with `currency.edit` permission)
4. Sets a system status flag that modules can check to display appropriate notices

### Caching Strategy
To optimize performance, the currency system implements the following caching:

| Data | Cache Duration | Invalidation Triggers |
|------|---------------|------------------------|
| Active currencies list | 24 hours | When currencies are added/edited/deactivated |
| Currency rates | 1 hour | After successful exchange rate updates |
| Default currency | 24 hours | When default currency setting changes |
| Conversion results | 1 hour | When rates are updated or settings change |

## Events

The currency system dispatches the following events that modules can listen for:

| Event | Description | Payload |
|-------|-------------|---------|
| `CurrencyDefaultChanged` | Fired when the default currency is changed | `oldCurrency`, `newCurrency` |
| `CurrencyRatesUpdated` | Fired when exchange rates are updated | `updatedCurrencies` (array of currency codes) |
| `CurrencyCreated` | Fired when a new currency is added | `currency` |
| `CurrencyUpdated` | Fired when a currency is updated | `currency`, `changes` |
| `CurrencyActivationChanged` | Fired when a currency is activated/deactivated | `currency`, `isActive` |

Example of listening for a currency event:

```php
// In a service provider
Event::listen(CurrencyDefaultChanged::class, function ($event) {
    // Update cached conversions
    Cache::forget('product_prices_converted');
    
    // Log the change
    Log::info("Default currency changed from {$event->oldCurrency} to {$event->newCurrency}");
});
```

## Bulk Operations

For modules dealing with large datasets, the system provides efficient bulk operations:

```php
// Convert multiple amounts at once
$convertedAmounts = Currency::bulkConvert([100, 200, 300], 'USD', 'EUR');

// Format multiple amounts at once
$formattedAmounts = Currency::bulkFormat([100, 200, 300], 'USD');

// Convert prices in a collection
$products->transform(function ($product) {
    $product->price_nok = Currency::bulkConvert([$product->price], $product->currency, 'NOK')[0];
    return $product;
});
```

Bulk operations use a single exchange rate lookup and maintain the array keys from the input.

## Best Practices

1. **Always use the Currency facade** for system-level operations
2. **Cache expensive operations** like conversions of large datasets
3. **Handle exchange rate failures gracefully** - the system will use last known rates
4. **Use the default currency for data storage** and convert only for display when possible
5. **Check if currencies are active** before using them in customer-facing interfaces
6. **Listen for currency events** to keep your module's data in sync
7. **Use bulk operations** when converting multiple values to improve performance

## Troubleshooting

- If exchange rates fail to update, check API key and provider settings
- For performance issues, use the provided caching mechanisms
- If a currency is missing, it can be manually added through the admin interface
