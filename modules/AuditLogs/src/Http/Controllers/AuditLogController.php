<?php

namespace Modules\AuditLogs\Http\Controllers;


use Illuminate\Http\Request;
use Modules\AuditLogs\Models\AuditLog;
use Illuminate\Routing\Controller;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::latest()->get();
        return view('auditlogs::index', compact('logs'));
    }
}