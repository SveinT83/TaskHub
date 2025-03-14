<?php

namespace Modules\AuditLogs\Http\Controllers;

use Modules\AuditLogs\src\Models\AuditLog;
use Illuminate\Routing\Controller;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('audit_logs.index', ['logs' => AuditLog::all()]);
    }
}