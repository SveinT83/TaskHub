<?php

use Modules\AuditLogs\Http\Controllers\AuditLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('auditlogs')->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('audit_logs.index');
});