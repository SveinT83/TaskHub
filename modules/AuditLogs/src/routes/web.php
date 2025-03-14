<?php

use Modules\AuditLogs\src\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('audit-logs')->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('audit_logs.index');
});