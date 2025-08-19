# TaskHub Store API - Architecture & Implementation Guide

> **Version:** 1.0  |  **Last updated:** 2025-07-20  
> **Purpose:** Complete specification for building the TaskHub Store API as a separate Laravel application

This document provides the complete architecture and implementation guide for creating the TaskHub Store - a centralized API-based module marketplace that TaskHub installations can connect to for browsing, downloading, and installing modules.

---

## 🎯 Project Overview

### What is TaskHub Store?
TaskHub Store is a **separate Laravel application** that serves as a centralized marketplace for TaskHub modules. It provides:
- **Public API** for TaskHub installations to browse and download modules
- **Admin panel** for module developers to publish and manage their modules
- **Analytics & statistics** for tracking downloads and usage
- **License management** for premium modules

### Architecture Pattern
Following industry standards like:
- WordPress Plugin Directory (wordpress.org/plugins)
- Laravel Nova Store
- VS Code Marketplace
- npm Registry

---

## 🏗️ Application Structure

### Recommended Directory Layout
```
taskhub-store/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   ├── ModulesController.php
│   │   │   │   ├── CategoriesController.php
│   │   │   │   ├── DownloadsController.php
│   │   │   │   └── AnalyticsController.php
│   │   │   └── Admin/
│   │   │       ├── ModulesController.php
│   │   │       └── DashboardController.php
│   │   └── Middleware/
│   │       └── ApiAuth.php
│   ├── Models/
│   │   ├── StoreModule.php
│   │   ├── StoreCategory.php
│   │   ├── StoreReview.php
│   │   ├── ModuleDownload.php
│   │   └── StoreAnalytic.php
│   ├── Jobs/
│   │   ├── ProcessModuleUpload.php
│   │   └── GenerateAnalyticsReport.php
│   └── Services/
│       ├── ModuleValidationService.php
│       └── LicenseService.php
├── database/
│   ├── migrations/
│   │   ├── create_store_modules_table.php
│   │   ├── create_store_categories_table.php
│   │   ├── create_store_reviews_table.php
│   │   ├── create_module_downloads_table.php
│   │   └── create_store_analytics_table.php
│   └── seeders/
│       └── StoreSeeder.php
├── routes/
│   ├── api.php
│   ├── web.php
│   └── admin.php
└── resources/
    ├── views/
    │   ├── admin/
    │   └── public/
    └── js/
        └── store-frontend.js
```

---

## 📊 Database Schema

### Core Tables Migration

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Store Registry - Available modules in the store
        Schema::create('store_modules', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description');
            $table->text('long_description')->nullable();
            $table->string('version');
            $table->string('author');
            $table->string('author_email')->nullable();
            $table->string('author_website')->nullable();
            $table->string('category');
            $table->decimal('price', 8, 2)->default(0.00);
            $table->enum('license', ['free', 'premium', 'enterprise']);
            $table->json('screenshots')->nullable(); // Array of image URLs
            $table->json('dependencies')->nullable(); // Required modules
            $table->json('compatibility')->nullable(); // TaskHub version compatibility
            $table->string('download_url')->nullable(); // Secured download URL
            $table->string('repository_url')->nullable(); // GitHub/GitLab repo
            $table->string('documentation_url')->nullable();
            $table->integer('downloads')->default(0);
            $table->decimal('rating', 2, 1)->default(0.0); // 0.0 to 5.0
            $table->integer('rating_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_verified')->default(false); // Quality checked
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['category', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['rating', 'is_active']);
            $table->index('published_at');
        });

        // Module Categories
        Schema::create('store_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('icon')->nullable(); // Bootstrap icon class
            $table->string('color')->nullable(); // Hex color for UI
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Module Reviews & Ratings
        Schema::create('store_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('module_slug');
            $table->string('reviewer_name');
            $table->string('reviewer_email');
            $table->string('site_url')->nullable(); // TaskHub installation URL
            $table->integer('rating'); // 1-5 stars
            $table->text('review')->nullable();
            $table->boolean('is_verified_purchase')->default(false);
            $table->boolean('is_approved')->default(false); // Moderation
            $table->timestamps();
            
            $table->foreign('module_slug')->references('slug')->on('store_modules')->onDelete('cascade');
            $table->index(['module_slug', 'is_approved']);
        });

        // Download Tracking & License Validation
        Schema::create('module_downloads', function (Blueprint $table) {
            $table->id();
            $table->uuid('download_token')->unique();
            $table->string('module_slug');
            $table->string('version');
            $table->string('site_url'); // TaskHub installation URL
            $table->string('license_key')->nullable(); // For premium modules
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'expired'])->default('pending');
            $table->timestamp('expires_at');
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamps();
            
            $table->foreign('module_slug')->references('slug')->on('store_modules')->onDelete('cascade');
            $table->index(['download_token', 'status']);
            $table->index(['module_slug', 'created_at']);
        });

        // Store Analytics & Statistics
        Schema::create('store_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('module_slug');
            $table->date('date');
            $table->integer('views')->default(0);
            $table->integer('downloads')->default(0);
            $table->integer('installations')->default(0);
            $table->json('countries')->nullable(); // Country breakdown
            $table->json('taskhub_versions')->nullable(); // Version breakdown
            $table->timestamps();
            
            $table->foreign('module_slug')->references('slug')->on('store_modules')->onDelete('cascade');
            $table->unique(['module_slug', 'date']);
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_analytics');
        Schema::dropIfExists('module_downloads');
        Schema::dropIfExists('store_reviews');
        Schema::dropIfExists('store_categories');
        Schema::dropIfExists('store_modules');
    }
};
```

---

## 🔌 API Endpoints

### Public API Routes (routes/api.php)

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ModulesController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\DownloadsController;
use App\Http\Controllers\Api\AnalyticsController;

Route::prefix('v1')->group(function () {
    
    // Browse modules
    Route::get('/modules', [ModulesController::class, 'index'])
         ->name('api.modules.index');
    
    Route::get('/modules/featured', [ModulesController::class, 'featured'])
         ->name('api.modules.featured');
    
    Route::get('/modules/search', [ModulesController::class, 'search'])
         ->name('api.modules.search');
    
    Route::get('/modules/{slug}', [ModulesController::class, 'show'])
         ->name('api.modules.show');
    
    Route::get('/modules/category/{category}', [ModulesController::class, 'byCategory'])
         ->name('api.modules.category');
    
    // Categories
    Route::get('/categories', [CategoriesController::class, 'index'])
         ->name('api.categories.index');
    
    // Downloads (secured with tokens)
    Route::post('/modules/{slug}/download', [DownloadsController::class, 'initiate'])
         ->name('api.modules.download.initiate');
    
    Route::get('/downloads/{token}', [DownloadsController::class, 'download'])
         ->name('api.downloads.file');
    
    // Analytics tracking
    Route::post('/modules/{slug}/view', [AnalyticsController::class, 'trackView'])
         ->name('api.modules.track.view');
    
    Route::post('/modules/{slug}/install', [AnalyticsController::class, 'trackInstall'])
         ->name('api.modules.track.install');
    
    // Reviews
    Route::post('/modules/{slug}/reviews', [ReviewsController::class, 'store'])
         ->name('api.modules.reviews.store');
});
```

### Example API Responses

#### GET /api/v1/modules
```json
{
    "data": [
        {
            "slug": "td-advanced-reporting",
            "name": "Advanced Reporting Suite",
            "description": "Create powerful reports and dashboards with advanced analytics.",
            "version": "2.1.0",
            "author": "TaskHub Team",
            "category": "reporting",
            "price": 49.99,
            "license": "premium",
            "screenshots": [
                "https://store.taskhub.com/screenshots/td-advanced-reporting/1.jpg",
                "https://store.taskhub.com/screenshots/td-advanced-reporting/2.jpg"
            ],
            "dependencies": ["core", "td-clients"],
            "compatibility": {
                "min_version": "1.0.0",
                "max_version": null
            },
            "downloads": 1542,
            "rating": 4.8,
            "rating_count": 127,
            "is_featured": true,
            "is_verified": true,
            "published_at": "2024-12-20T00:00:00Z",
            "updated_at": "2025-01-15T10:30:00Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 12,
        "total": 45,
        "last_page": 4
    }
}
```

#### GET /api/v1/categories
```json
{
    "data": [
        {
            "slug": "productivity",
            "name": "Productivity",
            "description": "Tools to boost productivity and efficiency",
            "icon": "bi bi-speedometer",
            "color": "#28a745",
            "module_count": 12
        },
        {
            "slug": "crm",
            "name": "CRM & Sales",
            "description": "Customer relationship management tools",
            "icon": "bi bi-people",
            "color": "#007bff",
            "module_count": 8
        }
    ]
}
```

---

## 🎮 Controllers

### API/ModulesController.php

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreModule;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ModulesController extends Controller
{
    /**
     * Browse all modules with filtering
     */
    public function index(Request $request): JsonResponse
    {
        $query = StoreModule::with('reviews')->active();
        
        // Category filter
        if ($request->has('category')) {
            $query->where('category', $request->get('category'));
        }
        
        // License filter
        if ($request->has('license')) {
            $query->where('license', $request->get('license'));
        }
        
        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'featured');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'rating':
                $query->orderByDesc('rating')->orderByDesc('rating_count');
                break;
            case 'downloads':
                $query->orderByDesc('downloads');
                break;
            case 'newest':
                $query->orderByDesc('published_at');
                break;
            case 'featured':
            default:
                $query->orderByDesc('is_featured')
                      ->orderByDesc('rating')
                      ->orderByDesc('downloads');
                break;
        }
        
        $modules = $query->paginate($request->get('per_page', 12));
        
        return response()->json($modules);
    }
    
    /**
     * Get featured modules
     */
    public function featured(): JsonResponse
    {
        $modules = StoreModule::featured()
                             ->active()
                             ->orderByDesc('rating')
                             ->limit(6)
                             ->get();
        
        return response()->json(['data' => $modules]);
    }
    
    /**
     * Get single module details
     */
    public function show(string $slug): JsonResponse
    {
        $module = StoreModule::with(['reviews' => function($query) {
            $query->approved()->latest()->limit(10);
        }])->where('slug', $slug)->active()->firstOrFail();
        
        // Track view
        $module->increment('views');
        
        return response()->json(['data' => $module]);
    }
    
    /**
     * Search modules
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);
        
        $query = $request->get('q');
        
        $modules = StoreModule::where('name', 'like', "%{$query}%")
                             ->orWhere('description', 'like', "%{$query}%")
                             ->orWhere('author', 'like', "%{$query}%")
                             ->active()
                             ->orderByDesc('rating')
                             ->limit(20)
                             ->get();
        
        return response()->json(['data' => $modules]);
    }
    
    /**
     * Get modules by category
     */
    public function byCategory(string $category): JsonResponse
    {
        $modules = StoreModule::where('category', $category)
                             ->active()
                             ->orderByDesc('is_featured')
                             ->orderByDesc('rating')
                             ->paginate(12);
        
        return response()->json($modules);
    }
}
```

### API/DownloadsController.php

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StoreModule;
use App\Models\ModuleDownload;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    /**
     * Initiate module download - returns secure token
     */
    public function initiate(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'version' => 'required|string',
            'site_url' => 'required|url',
            'license_key' => 'nullable|string', // Required for premium modules
        ]);
        
        $module = StoreModule::where('slug', $slug)->active()->firstOrFail();
        
        // Check license for premium modules
        if ($module->license !== 'free') {
            if (!$request->has('license_key')) {
                return response()->json([
                    'error' => 'License key required for premium modules'
                ], 402); // Payment Required
            }
            
            // Validate license (implement license validation logic)
            if (!$this->validateLicense($request->license_key, $slug, $request->site_url)) {
                return response()->json([
                    'error' => 'Invalid license key'
                ], 403);
            }
        }
        
        // Create download record
        $download = ModuleDownload::create([
            'download_token' => Str::uuid(),
            'module_slug' => $slug,
            'version' => $request->version,
            'site_url' => $request->site_url,
            'license_key' => $request->license_key,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'expires_at' => now()->addMinutes(30), // Token expires in 30 minutes
        ]);
        
        return response()->json([
            'download_token' => $download->download_token,
            'download_url' => route('api.downloads.file', $download->download_token),
            'expires_at' => $download->expires_at,
        ]);
    }
    
    /**
     * Download module file using secure token
     */
    public function download(string $token)
    {
        $download = ModuleDownload::where('download_token', $token)
                                 ->where('status', 'pending')
                                 ->where('expires_at', '>', now())
                                 ->firstOrFail();
        
        $module = StoreModule::where('slug', $download->module_slug)->firstOrFail();
        
        // Update download record
        $download->update([
            'status' => 'completed',
            'downloaded_at' => now(),
        ]);
        
        // Increment download counter
        $module->increment('downloads');
        
        // Return file download
        $filePath = "modules/{$download->module_slug}/{$download->version}.zip";
        
        if (!Storage::disk('modules')->exists($filePath)) {
            abort(404, 'Module file not found');
        }
        
        return Storage::disk('modules')->download($filePath, "{$download->module_slug}-{$download->version}.zip");
    }
    
    /**
     * Validate license key for premium modules
     */
    private function validateLicense(string $licenseKey, string $moduleSlug, string $siteUrl): bool
    {
        // Implement your license validation logic here
        // This could check against a licenses database table
        // or call an external license server
        
        return true; // Placeholder
    }
}
```

---

## 📱 Models

### StoreModule.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class StoreModule extends Model
{
    protected $fillable = [
        'slug',
        'name', 
        'description',
        'long_description',
        'version',
        'author',
        'author_email',
        'author_website',
        'category',
        'price',
        'license',
        'screenshots',
        'dependencies',
        'compatibility',
        'download_url',
        'repository_url',
        'documentation_url',
        'downloads',
        'rating',
        'rating_count',
        'is_featured',
        'is_verified',
        'is_active',
        'published_at',
    ];

    protected $casts = [
        'screenshots' => 'array',
        'dependencies' => 'array',
        'compatibility' => 'array',
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'price_formatted',
        'is_free',
        'is_premium',
    ];

    /**
     * Relationships
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(StoreReview::class, 'module_slug', 'slug');
    }

    public function downloads(): HasMany
    {
        return $this->hasMany(ModuleDownload::class, 'module_slug', 'slug');
    }

    public function analytics(): HasMany
    {
        return $this->hasMany(StoreAnalytic::class, 'module_slug', 'slug');
    }

    /**
     * Query Scopes
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_verified', true);
    }

    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    public function scopeByLicense(Builder $query, string $license): Builder
    {
        return $query->where('license', $license);
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('license', 'free');
    }

    public function scopePremium(Builder $query): Builder
    {
        return $query->whereIn('license', ['premium', 'enterprise']);
    }

    /**
     * Accessors
     */
    public function getPriceFormattedAttribute(): string
    {
        if ($this->price == 0) {
            return 'Free';
        }
        
        return '$' . number_format($this->price, 2);
    }

    public function getIsFreeAttribute(): bool
    {
        return $this->license === 'free';
    }

    public function getIsPremiumAttribute(): bool
    {
        return in_array($this->license, ['premium', 'enterprise']);
    }

    /**
     * Update module rating based on reviews
     */
    public function updateRating(): void
    {
        $approvedReviews = $this->reviews()->approved()->get();
        
        if ($approvedReviews->count() > 0) {
            $this->update([
                'rating' => round($approvedReviews->avg('rating'), 1),
                'rating_count' => $approvedReviews->count(),
            ]);
        }
    }
}
```

---

## 🚀 TaskHub Client Integration

### Updated TaskHub StoreController

```php
<?php

namespace App\Http\Controllers\Admin\Modules;

use App\Http\Controllers\Controller;
use App\Models\ModuleInstallation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    protected string $storeApiUrl;
    
    public function __construct()
    {
        $this->storeApiUrl = config('taskhub.store_api_url', 'https://store.taskhub.com/api/v1');
    }
    
    /**
     * Display the module store
     */
    public function store()
    {
        try {
            // Fetch data from TaskHub Store API
            $featuredResponse = Http::timeout(10)->get($this->storeApiUrl . '/modules/featured');
            $categoriesResponse = Http::timeout(10)->get($this->storeApiUrl . '/categories');
            
            $featured = $featuredResponse->successful() ? $featuredResponse->json('data', []) : [];
            $categories = $categoriesResponse->successful() ? $categoriesResponse->json('data', []) : [];
            
        } catch (\Exception $e) {
            // Handle API errors gracefully
            $featured = [];
            $categories = [];
            session()->flash('warning', 'Unable to connect to TaskHub Store. Please try again later.');
        }
        
        return view('admin.modules.store', compact('featured', 'categories'));
    }
    
    /**
     * Search and filter modules (AJAX)
     */
    public function lookup(Request $request)
    {
        try {
            $params = $request->only(['search', 'category', 'license', 'sort', 'page', 'per_page']);
            $response = Http::timeout(15)->get($this->storeApiUrl . '/modules', $params);
            
            if ($response->successful()) {
                return response()->json($response->json());
            }
            
        } catch (\Exception $e) {
            \Log::error('Store API lookup failed: ' . $e->getMessage());
        }
        
        return response()->json([
            'error' => 'Unable to fetch modules from store'
        ], 503);
    }
    
    /**
     * Show module details
     */
    public function show(string $slug)
    {
        try {
            $response = Http::timeout(10)->get($this->storeApiUrl . "/modules/{$slug}");
            
            if ($response->successful()) {
                $module = $response->json('data');
                return view('admin.modules.show', compact('module'));
            }
            
        } catch (\Exception $e) {
            \Log::error("Store API module show failed for {$slug}: " . $e->getMessage());
        }
        
        return redirect()->route('admin.modules.store')
                        ->with('error', 'Module not found or store unavailable.');
    }
    
    /**
     * Queue module for installation
     */
    public function install(Request $request)
    {
        $request->validate([
            'module_slug' => 'required|string',
            'version' => 'required|string',
            'license_key' => 'nullable|string',
        ]);
        
        try {
            // Request download token from store
            $downloadResponse = Http::timeout(30)->post($this->storeApiUrl . "/modules/{$request->module_slug}/download", [
                'version' => $request->version,
                'site_url' => config('app.url'),
                'license_key' => $request->license_key,
            ]);
            
            if ($downloadResponse->successful()) {
                $downloadData = $downloadResponse->json();
                
                // Create installation record
                $installation = ModuleInstallation::create([
                    'uuid' => Str::uuid(),
                    'module_slug' => $request->module_slug,
                    'version' => $request->version,
                    'download_url' => $downloadData['download_url'],
                    'download_token' => $downloadData['download_token'],
                    'status' => 'queued',
                    'progress' => ['step' => 1, 'total' => 5, 'message' => 'Queued for installation'],
                ]);
                
                // Dispatch installation job
                \App\Jobs\InstallModuleJob::dispatch($installation);
                
                return redirect()->route('admin.modules.install.log', $installation->uuid)
                               ->with('success', 'Module installation started successfully.');
            }
            
        } catch (\Exception $e) {
            \Log::error("Module installation request failed: " . $e->getMessage());
        }
        
        return redirect()->back()
                        ->with('error', 'Failed to start module installation. Please try again.');
    }
}
```

### Installation Job

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\ModuleInstallation;
use App\Models\Module;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class InstallModuleJob implements ShouldQueue
{
    use Queueable, SerializesModels, InteractsWithQueue;

    public function __construct(
        public ModuleInstallation $installation
    ) {}

    public function handle()
    {
        try {
            $this->updateProgress('downloading', 2, 'Downloading module package...');
            
            // Download module package
            $response = Http::timeout(300)->get($this->installation->download_url);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to download module package');
            }
            
            $zipPath = storage_path('app/temp/modules/' . $this->installation->module_slug . '.zip');
            File::ensureDirectoryExists(dirname($zipPath));
            File::put($zipPath, $response->body());
            
            $this->updateProgress('extracting', 3, 'Extracting module files...');
            
            // Extract module
            $extractPath = base_path('modules/' . $this->installation->module_slug);
            $this->extractModule($zipPath, $extractPath);
            
            $this->updateProgress('installing', 4, 'Installing module dependencies...');
            
            // Run module installation
            $this->installModule($extractPath);
            
            $this->updateProgress('completed', 5, 'Module installed successfully!');
            
            // Update modules database
            $this->updateModulesDatabase();
            
            // Clean up temporary files
            File::delete($zipPath);
            
        } catch (\Exception $e) {
            $this->updateProgress('failed', null, 'Installation failed: ' . $e->getMessage());
            \Log::error("Module installation failed for {$this->installation->module_slug}: " . $e->getMessage());
        }
    }
    
    private function updateProgress(string $status, ?int $step, string $message): void
    {
        $this->installation->update([
            'status' => $status,
            'progress' => [
                'step' => $step,
                'total' => 5,
                'message' => $message
            ],
            'log' => $this->installation->log . "\n[" . now() . "] " . $message
        ]);
    }
    
    private function extractModule(string $zipPath, string $extractPath): void
    {
        $zip = new ZipArchive;
        $result = $zip->open($zipPath);
        
        if ($result !== TRUE) {
            throw new \Exception("Failed to open module archive: Error code {$result}");
        }
        
        // Ensure extraction directory exists
        File::ensureDirectoryExists($extractPath);
        
        // Extract archive
        if (!$zip->extractTo($extractPath)) {
            throw new \Exception('Failed to extract module files');
        }
        
        $zip->close();
    }
    
    private function installModule(string $modulePath): void
    {
        // Check if module.json exists
        $moduleJsonPath = $modulePath . '/module.json';
        if (!File::exists($moduleJsonPath)) {
            throw new \Exception('Invalid module: module.json not found');
        }
        
        // Read module configuration
        $moduleConfig = json_decode(File::get($moduleJsonPath), true);
        if (!$moduleConfig) {
            throw new \Exception('Invalid module.json format');
        }
        
        // Run migrations if they exist
        $migrationsPath = $modulePath . '/database/migrations';
        if (File::isDirectory($migrationsPath)) {
            Artisan::call('migrate', [
                '--path' => 'modules/' . $this->installation->module_slug . '/database/migrations',
                '--force' => true,
            ]);
        }
        
        // Publish assets if they exist
        $publicPath = $modulePath . '/public';
        if (File::isDirectory($publicPath)) {
            $targetPath = public_path('vendor/' . $this->installation->module_slug);
            File::copyDirectory($publicPath, $targetPath);
        }
        
        // Run module-specific installation script if it exists
        $installScript = $modulePath . '/install.php';
        if (File::exists($installScript)) {
            require $installScript;
        }
    }
    
    private function updateModulesDatabase(): void
    {
        // Trigger module rescan to update local database
        Artisan::call('module:rescan');
    }
}
```

---

## 🛠️ Development Setup

### Environment Configuration

```env
# TaskHub Store API Configuration
STORE_API_URL=https://store.taskhub.com/api/v1
STORE_API_TIMEOUT=30

# File Storage for Module Packages
MODULES_DISK=modules
MODULES_STORAGE_PATH=/var/www/taskhub-store/storage/modules

# License Validation
LICENSE_API_URL=https://licenses.taskhub.com/api/v1
LICENSE_API_KEY=your-license-api-key
```

### Configuration File (config/taskhub.php)

```php
<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | TaskHub Store API Configuration
    |--------------------------------------------------------------------------
    */
    
    'store_api_url' => env('STORE_API_URL', 'https://store.taskhub.com/api/v1'),
    'store_api_timeout' => env('STORE_API_TIMEOUT', 30),
    
    /*
    |--------------------------------------------------------------------------
    | Module Management
    |--------------------------------------------------------------------------
    */
    
    'modules_path' => base_path('modules'),
    'modules_cache_ttl' => env('MODULES_CACHE_TTL', 3600),
    
    /*
    |--------------------------------------------------------------------------
    | License Management
    |--------------------------------------------------------------------------
    */
    
    'license_api_url' => env('LICENSE_API_URL'),
    'license_api_key' => env('LICENSE_API_KEY'),
    
];
```

---

## 📋 Implementation Checklist

### Phase 1: Core Store API
- [ ] Create new Laravel application for TaskHub Store
- [ ] Implement database schema and migrations
- [ ] Create models with relationships
- [ ] Build API controllers for modules, categories, downloads
- [ ] Implement authentication and rate limiting
- [ ] Add file storage for module packages
- [ ] Create seeder with sample data

### Phase 2: Security & Licensing
- [ ] Implement secure download tokens
- [ ] Add license validation system
- [ ] Create rate limiting for API endpoints
- [ ] Add request logging and analytics
- [ ] Implement module verification workflow

### Phase 3: TaskHub Client Integration
- [ ] Update TaskHub StoreController to consume API
- [ ] Create installation queue system
- [ ] Build module installation job
- [ ] Add progress tracking and logging
- [ ] Create store UI/UX components

### Phase 4: Advanced Features
- [ ] Module reviews and ratings system
- [ ] Analytics and reporting dashboard
- [ ] Module developer portal
- [ ] Automated testing and CI/CD
- [ ] Documentation and API docs

### Phase 5: Production Deployment
- [ ] Set up production infrastructure
- [ ] Configure CDN for module downloads
- [ ] Implement monitoring and logging
- [ ] Load testing and optimization
- [ ] SSL certificates and security hardening

---

## 🎯 Success Metrics

### Technical KPIs
- **API Response Time**: < 500ms for browse endpoints
- **Download Success Rate**: > 99.5%
- **Installation Success Rate**: > 95%
- **API Uptime**: > 99.9%

### Business KPIs
- **Module Downloads**: Track total and per-module downloads
- **Active Installations**: Number of successfully installed modules
- **Developer Adoption**: Number of published modules
- **User Satisfaction**: Average module ratings

---

## 🔧 Maintenance & Support

### Regular Tasks
- **Database Maintenance**: Clean up expired download tokens
- **Analytics Processing**: Generate daily/weekly/monthly reports
- **Module Verification**: Review and approve new modules
- **License Management**: Monitor and validate premium licenses

### Monitoring
- **API Health Checks**: Automated monitoring of all endpoints
- **Download Monitoring**: Track failed downloads and investigate
- **Error Logging**: Comprehensive logging of all errors and exceptions
- **Performance Monitoring**: Track response times and resource usage

---

## 📚 Additional Resources

### Related Documentation
- [TaskHub Module Development Guide](./modules.md)
- [TaskHub API Authentication](./api-auth.md)
- [TaskHub Deployment Guide](./deployment.md)

### External References
- [Laravel API Resources](https://laravel.com/docs/eloquent-resources)
- [Laravel Queue Jobs](https://laravel.com/docs/queues)
- [HTTP Client](https://laravel.com/docs/http-client)

---

*© 2025 TrønderData AS – TaskHub Store API Implementation Guide*

**Ready to build the future of TaskHub modules!** 🚀
