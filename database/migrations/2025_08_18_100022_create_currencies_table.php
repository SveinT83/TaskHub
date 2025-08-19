<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only create table if it doesn't exist
        if (!Schema::hasTable('currencies')) {
            Schema::create('currencies', function (Blueprint $table) {
                $table->id();
                $table->string('code', 3)->unique();
                $table->string('name');
                $table->string('symbol', 10);
                $table->decimal('exchange_rate', 10, 6)->default(1.0);
                $table->timestamp('rate_updated_at')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });

            // Add default currencies
            $this->seedDefaultCurrencies();
        }

        // Add currency settings to settings table
        if (Schema::hasTable('settings')) {
            // Check if group column exists
            $hasGroupColumn = Schema::hasColumn('settings', 'group');
            
            $settings = [
                [
                    'name' => 'default_currency',
                    'value' => 'EUR',
                    'description' => 'Default currency for the system',
                    'type' => 'string',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'exchange_rate_provider',
                    'value' => 'exchangerate-api',
                    'description' => 'Exchange rate API provider service',
                    'type' => 'string',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'exchange_rate_api_key',
                    'value' => '',
                    'description' => 'API key for exchange rate services',
                    'type' => 'string',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            // Add group column if it exists
            if ($hasGroupColumn) {
                foreach ($settings as &$setting) {
                    $setting['group'] = 'financial';
                }
            }

            DB::table('settings')->insertOrIgnore($settings);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
        
        if (Schema::hasTable('settings')) {
            $hasGroupColumn = Schema::hasColumn('settings', 'group');
            
            $query = DB::table('settings')->whereIn('name', ['default_currency', 'exchange_rate_provider', 'exchange_rate_api_key']);
            
            if ($hasGroupColumn) {
                $query->where('group', 'financial');
            }
            
            $query->delete();
        }
    }

    /**
     * Seed default currencies.
     */
    protected function seedDefaultCurrencies(): void
    {
        $currencies = [
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'exchange_rate' => 1.0,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'rate_updated_at' => now(),
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'exchange_rate' => 1.09, // Example rate
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'rate_updated_at' => now(),
            ],
            [
                'code' => 'NOK',
                'name' => 'Norwegian Krone',
                'symbol' => 'kr',
                'exchange_rate' => 11.50, // Example rate
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'rate_updated_at' => now(),
            ],
            [
                'code' => 'GBP',
                'name' => 'British Pound',
                'symbol' => '£',
                'exchange_rate' => 0.85, // Example rate
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'rate_updated_at' => now(),
            ],
            [
                'code' => 'SEK',
                'name' => 'Swedish Krona',
                'symbol' => 'kr',
                'exchange_rate' => 11.20, // Example rate
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'rate_updated_at' => now(),
            ],
            [
                'code' => 'DKK',
                'name' => 'Danish Krone',
                'symbol' => 'kr',
                'exchange_rate' => 7.46, // Example rate
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'rate_updated_at' => now(),
            ],
        ];

        DB::table('currencies')->insert($currencies);
    }
};
