<?php

namespace App\Http\Controllers\Admin\Configurations;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Setting;
use App\Facades\Currency as CurrencyFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    public function __construct()
    {
        // No middleware - following pattern from other admin controllers
    }

    /**
     * Display a listing of currencies
     */
    public function index()
    {
        $currencies = Currency::orderBy('active', 'desc')->orderBy('code')->paginate(20);
        $defaultCurrency = CurrencyFacade::getDefaultCurrency();

        return view('admin.configurations.currency.index', compact('currencies', 'defaultCurrency'));
    }

    /**
     * Show the form for creating a new currency
     */
    public function create()
    {
        return view('admin.configurations.currency.create');
    }

    /**
     * Store a newly created currency
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:3|unique:currencies,code',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'active' => 'boolean',
        ]);

        $currency = Currency::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'symbol' => $request->symbol,
            'exchange_rate' => $request->exchange_rate,
            'active' => $request->boolean('active', true),
            'rate_updated_at' => now(),
        ]);

        CurrencyFacade::clearCache();

        return redirect()
            ->route('currency.index')
            ->with('success', __('core.currency.created_successfully'));
    }

    /**
     * Show the form for editing a currency
     */
    public function edit(Currency $currency)
    {
        return view('admin.configurations.currency.edit', compact('currency'));
    }

    /**
     * Update the specified currency
     */
    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code' => 'required|string|size:3|unique:currencies,code,' . $currency->id,
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.000001',
            'active' => 'boolean',
        ]);

        $currency->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'symbol' => $request->symbol,
            'exchange_rate' => $request->exchange_rate,
            'active' => $request->boolean('active', true),
        ]);

        CurrencyFacade::clearCache();

        return redirect()
            ->route('currency.index')
            ->with('success', __('core.currency.updated_successfully'));
    }

    /**
     * Remove the specified currency
     */
    public function destroy(Currency $currency)
    {
        $defaultCurrency = CurrencyFacade::getDefaultCurrency();

        if ($currency->code === $defaultCurrency?->code) {
            return redirect()
                ->route('currency.index')
                ->with('error', __('core.currency.cannot_delete_default'));
        }

        $currency->delete();
        CurrencyFacade::clearCache();

        return redirect()
            ->route('currency.index')
            ->with('success', __('core.currency.deleted_successfully'));
    }

    /**
     * Set currency as default
     */
    public function setDefault(Currency $currency)
    {
        if (!$currency->active) {
            return redirect()
                ->route('currency.index')
                ->with('error', __('core.currency.cannot_set_inactive_as_default'));
        }

        $success = CurrencyFacade::setDefaultCurrency($currency->code);

        if ($success) {
            return redirect()
                ->route('currency.index')
                ->with('success', __('core.currency.default_set_successfully', ['currency' => $currency->code]));
        }

        return redirect()
            ->route('currency.index')
            ->with('error', __('core.currency.default_set_failed'));
    }

    /**
     * Update exchange rates from API
     */
    public function updateRates()
    {
        try {
            $success = CurrencyFacade::updateExchangeRates();

            if ($success) {
                return redirect()
                    ->route('currency.index')
                    ->with('success', __('core.currency.rates_updated_successfully'));
            }

            return redirect()
                ->route('currency.index')
                ->with('error', __('core.currency.rates_update_failed'));
        } catch (\Exception $e) {
            return redirect()
                ->route('currency.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show currency settings
     */
    public function settings()
    {
        $settings = [
            'exchange_rate_provider' => Setting::where('name', 'exchange_rate_provider')->where('group', 'financial')->first(),
            'exchange_rate_api_key' => Setting::where('name', 'exchange_rate_api_key')->where('group', 'financial')->first(),
            'default_currency' => Setting::where('name', 'default_currency')->where('group', 'financial')->first(),
        ];

        return view('admin.configurations.currency.settings', compact('settings'));
    }

    /**
     * Update currency settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'exchange_rate_provider' => 'required|in:exchangerate-api,openexchangerates',
            'exchange_rate_api_key' => 'nullable|string|max:255',
        ]);

        foreach (['exchange_rate_provider', 'exchange_rate_api_key'] as $setting) {
            Setting::updateOrCreate(
                ['name' => $setting, 'group' => 'financial'],
                [
                    'value' => $request->get($setting),
                    'description' => $setting === 'exchange_rate_provider'
                        ? 'Exchange rate API provider service'
                        : 'API key for exchange rate services',
                    'type' => 'string',
                ]
            );
        }

        return redirect()
            ->route('admin.configurations.currency.settings')
            ->with('success', __('core.currency.settings_updated_successfully'));
    }
}
