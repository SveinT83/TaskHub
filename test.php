<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;
use Modules\CredentialsBank\Http\Controllers\CredentialsBankController;

// Load Composer autoload
require __DIR__ . '/vendor/autoload.php';

// Load Laravel bootstrap
$app = require __DIR__ . '/bootstrap/app.php';

// Run the kernel to fully boot the framework
$app->make(Kernel::class)->bootstrap();

// Check database connection
try {
    DB::connection()->getPdo();
    echo "✅ Database connection is successful!\n";
} catch (\Exception $e) {
    die("❌ Database connection failed: " . $e->getMessage() . "\n");
}

// Now try loading the controller
$controller = new CredentialsBankController();
var_dump($controller);
